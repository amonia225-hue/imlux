<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\FichierPropose;
use App\Models\InvitationPromoteur;
use App\Models\SoumissionPromoteur;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Collecte de biens auprès des promoteurs, côté bureau d'études.
 *
 * On crée une invitation par promoteur, on lui transmet le lien, puis on
 * examine ce qu'il a déposé.
 */
class CollectePromoteurController extends Controller
{
    public function index(): View
    {
        return view('admin.collecte-promoteurs', [
            'invitations' => InvitationPromoteur::withCount('soumissions')
                ->with('soumissions:id,invitation_id,statut,soumise_le')
                ->latest()
                ->get(),
            'soumissions' => SoumissionPromoteur::with('biens')
                ->where('statut', '!=', SoumissionPromoteur::STATUT_BROUILLON)
                ->latest('soumise_le')
                ->get(),
            'aExaminer' => SoumissionPromoteur::where('statut', SoumissionPromoteur::STATUT_SOUMISE)->count(),
        ]);
    }

    public function storeInvitation(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'promoteur' => ['required', 'string', 'max:160'],
            'contact' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'note' => ['nullable', 'string', 'max:1000'],
            'jours_validite' => ['nullable', 'integer', 'min:1', 'max:365'],
        ], [
            'promoteur.required' => 'Indiquez le nom du promoteur.',
        ]);

        $invitation = InvitationPromoteur::create([
            ...$data,
            'token' => InvitationPromoteur::nouveauToken(),
            // Une invitation sans date de fin resterait ouverte indéfiniment ;
            // 60 jours par défaut, prolongeables en en recréant une.
            'expire_le' => now()->addDays((int) ($data['jours_validite'] ?? 60)),
            'cree_par_id' => $request->user()?->id,
        ]);

        return back()->with('ok', "Lien créé pour {$invitation->promoteur}. Copiez-le et transmettez-le.");
    }

    public function revoquer(InvitationPromoteur $invitation): RedirectResponse
    {
        // On ne supprime pas : les dépôts déjà reçus doivent rester rattachés à
        // l'invitation qui les a produits.
        $invitation->update(['revoquee_le' => now()]);

        return back()->with('ok', "Lien de {$invitation->promoteur} révoqué.");
    }

    public function show(SoumissionPromoteur $soumission): View
    {
        return view('admin.collecte-soumission', [
            'soumission' => $soumission->load('biens.lots', 'biens.fichiers', 'fichiers', 'invitation', 'traitePar'),
        ]);
    }

    public function traiter(Request $request, SoumissionPromoteur $soumission): RedirectResponse
    {
        $data = $request->validate([
            'decision' => ['required', Rule::in([
                SoumissionPromoteur::STATUT_VALIDEE,
                SoumissionPromoteur::STATUT_REJETEE,
            ])],
            'note_admin' => ['nullable', 'string', 'max:2000'],
        ]);

        // Un dépôt déjà tranché ne se rejuge pas : deux gestionnaires ouvrant
        // le même dossier écraseraient sinon mutuellement leur décision.
        if ($soumission->estTraitee()) {
            return back()->withErrors(['decision' => 'Ce dépôt a déjà été traité le '
                .$soumission->traitee_le?->format('d/m/Y').'.']);
        }

        $soumission->update([
            'statut' => $data['decision'],
            'note_admin' => $data['note_admin'] ?? null,
            'traitee_par_id' => $request->user()?->id,
            'traitee_le' => now(),
        ]);

        return redirect()->route('admin.collecte.index')
            ->with('ok', 'Dépôt de '.$soumission->promoteur.' '
                .($data['decision'] === SoumissionPromoteur::STATUT_VALIDEE ? 'validé.' : 'rejeté.'));
    }

    /**
     * Sert un fichier déposé depuis le disque privé.
     *
     * Les pièces (plan de masse, ACD) ne sont pas dans `public/` : elles ne
     * doivent être lisibles que par un gestionnaire connecté, jamais par une
     * URL devinée.
     */
    public function fichier(Request $request, FichierPropose $fichier): Response
    {
        abort_unless(Storage::disk('local')->exists($fichier->chemin), 404);

        // `?telecharger=1` force l'enregistrement ; sans ce paramètre le fichier
        // s'ouvre dans l'onglet, ce qui reste le plus pratique pour examiner.
        $disposition = $request->boolean('telecharger') ? 'attachment' : 'inline';

        return response(Storage::disk('local')->get($fichier->chemin), 200, [
            'Content-Type' => $fichier->mime,
            'Content-Disposition' => $disposition.'; filename="'.addslashes($fichier->nom_original).'"',
        ]);
    }

    /**
     * Archive ZIP de toutes les pièces d'un dépôt, rangées par bien.
     *
     * Télécharger trente photos une par une n'est pas tenable. L'archive
     * reproduit la structure du dépôt — un dossier par bien — pour que le
     * contenu soit exploitable sans avoir à deviner quelle photo va où.
     */
    public function archive(Request $request, SoumissionPromoteur $soumission): BinaryFileResponse
    {
        abort_unless(class_exists(\ZipArchive::class), 501, "L'extension zip n'est pas disponible sur ce serveur.");

        $soumission->load('biens.fichiers', 'fichiers');

        // `?bien=` restreint l'archive à un seul bien, pour le cas courant où le
        // gestionnaire ne veut que les photos du lot qu'il est en train de traiter.
        $bienId = $request->integer('bien') ?: null;
        $biens = $bienId
            ? $soumission->biens->where('id', $bienId)->values()
            : $soumission->biens;

        abort_if($bienId && $biens->isEmpty(), 404, 'Ce bien n’appartient pas à ce dépôt.');
        abort_if($soumission->fichiers->isEmpty(), 404, 'Ce dépôt ne contient aucune pièce.');

        $chemin = tempnam(sys_get_temp_dir(), 'depot').'.zip';
        $zip = new \ZipArchive;
        $zip->open($chemin, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($biens as $rang => $bien) {
            $dossier = sprintf('%02d - %s - %s', $rang + 1, $this->assainir($bien->site), $this->assainir($bien->libelle));

            foreach ($bien->fichiers as $i => $fichier) {
                $this->ajouterAuZip($zip, $fichier, $dossier, $i + 1);
            }
        }

        if (! $bienId) {
            foreach ($soumission->fichiers->whereNull('bien_propose_id')->values() as $i => $fichier) {
                $this->ajouterAuZip($zip, $fichier, 'Documents generaux', $i + 1);
            }
        }

        $zip->close();

        // Une archive emporte des pièces hors de l'application : on trace qui
        // l'a extraite, avec le journal d'audit maison du projet.
        if (auth()->user() instanceof User) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'action' => 'archive',
                'model_type' => class_basename($soumission),
                'model_id' => $soumission->getKey(),
                'summary' => 'Archive du dépôt de '.$soumission->promoteur
                    .($bienId ? ' (bien #'.$bienId.')' : ''),
                'changes' => ['pieces' => $soumission->fichiers->count(), 'bien' => $bienId],
                'ip' => request()->ip(),
            ]);
        }

        $nom = 'depot-'.Str::slug($soumission->promoteur).'-'.$soumission->id.'.zip';

        return response()->download($chemin, $nom)->deleteFileAfterSend();
    }

    private function ajouterAuZip(\ZipArchive $zip, FichierPropose $fichier, string $dossier, int $rang): void
    {
        if (! Storage::disk('local')->exists($fichier->chemin)) {
            return;
        }

        // Nom numéroté : deux photos peuvent porter le même nom d'origine, et
        // la seconde écraserait la première dans l'archive.
        $extension = pathinfo($fichier->nom_original, PATHINFO_EXTENSION) ?: 'jpg';
        $base = pathinfo($this->assainir($fichier->nom_original), PATHINFO_FILENAME);

        $zip->addFromString(
            sprintf('%s/%02d-%s.%s', $dossier, $rang, $base, $extension),
            Storage::disk('local')->get($fichier->chemin),
        );
    }

    /** Retire tout ce qui pourrait casser un chemin de fichier sous Windows. */
    private function assainir(string $valeur): string
    {
        $valeur = preg_replace('/[\/\\\\:*?"<>|]+/', '-', $valeur) ?? $valeur;

        return trim(mb_substr($valeur, 0, 60));
    }
}
