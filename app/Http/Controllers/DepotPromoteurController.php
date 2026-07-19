<?php

namespace App\Http\Controllers;

use App\Models\BienPropose;
use App\Models\FichierPropose;
use App\Models\InvitationPromoteur;
use App\Models\SoumissionPromoteur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Formulaire public de dépôt de biens, ouvert par jeton.
 *
 * Aucune authentification : le promoteur n'a pas de compte. C'est le jeton de
 * l'invitation qui fait office d'identification, et c'est aussi ce qui impose
 * la prudence sur les fichiers reçus — tout ce qui arrive ici vient d'Internet.
 */
class DepotPromoteurController extends Controller
{
    /** 8 Mo par fichier : au-delà, un plan scanné devient un vecteur de saturation. */
    private const TAILLE_MAX_KO = 8192;

    private const PHOTOS_MAX = 12;

    public function show(string $token): View
    {
        $invitation = $this->invitation($token);

        if (! $invitation->estUtilisable()) {
            return view('promoteur.depot-indisponible', [
                'motif' => $invitation->motifIndisponibilite(),
            ]);
        }

        $soumission = $this->brouillon($invitation);

        // Une soumission déjà transmise n'est plus modifiable : c'est la pièce
        // que le bureau d'études examine, elle ne doit pas bouger sous ses yeux.
        if (! $soumission->estBrouillon()) {
            return view('promoteur.depot-recu', ['soumission' => $soumission]);
        }

        return view('promoteur.depot', [
            'invitation' => $invitation,
            'soumission' => $soumission->load('biens.lots', 'biens.fichiers', 'fichiers'),
            'types' => BienPropose::TYPES,
            'modes' => BienPropose::MODES,
            'documents' => BienPropose::DOCUMENTS,
        ]);
    }

    public function enregistrer(Request $request, string $token): RedirectResponse
    {
        $invitation = $this->invitation($token);
        abort_unless($invitation->estUtilisable(), 410);

        $soumission = $this->brouillon($invitation);
        abort_unless($soumission->estBrouillon(), 409);

        $transmettre = $request->boolean('transmettre');

        $data = $request->validate([
            'promoteur' => ['required', 'string', 'max:160'],
            'contact' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'telephone' => ['nullable', 'string', 'max:40'],

            'biens' => [$transmettre ? 'required' : 'nullable', 'array', 'max:30'],
            'biens.*.id' => ['nullable', 'integer'],
            'biens.*.site' => ['required_with:biens', 'string', 'max:160'],
            'biens.*.type_bien' => ['required_with:biens', Rule::in(array_keys(BienPropose::TYPES))],
            'biens.*.libelle' => ['required_with:biens', 'string', 'max:255'],
            'biens.*.superficie_min' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'biens.*.superficie_max' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'biens.*.superficie_utile' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'biens.*.nb_pieces' => ['nullable', 'integer', 'min:1', 'max:100'],
            'biens.*.disponibilite' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'biens.*.modes_acquisition' => ['nullable', 'array'],
            'biens.*.modes_acquisition.*' => [Rule::in(array_keys(BienPropose::MODES))],
            'biens.*.prix_unitaire' => ['nullable', 'integer', 'min:0', 'max:100000000000'],
            'biens.*.prix_au_m2' => ['nullable', 'boolean'],
            'biens.*.delai_reglement' => ['nullable', 'string', 'max:160'],
            'biens.*.documents' => ['nullable', 'array'],
            'biens.*.documents.*' => [Rule::in(array_keys(BienPropose::DOCUMENTS))],
            'biens.*.commentaire' => ['nullable', 'string', 'max:2000'],
            'biens.*.lots' => ['nullable', 'string', 'max:2000'],
            'biens.*.ilot' => ['nullable', 'string', 'max:60'],
        ], [
            'biens.required' => 'Ajoutez au moins un bien avant de transmettre.',
            'biens.*.site.required_with' => 'Le site est obligatoire pour chaque bien.',
            'biens.*.libelle.required_with' => 'Décrivez le bien (ex. « Villa basse 4 pièces »).',
        ]);

        DB::transaction(function () use ($soumission, $data, $transmettre) {
            $soumission->update([
                'promoteur' => $data['promoteur'],
                'contact' => $data['contact'] ?? null,
                'email' => $data['email'] ?? null,
                'telephone' => $data['telephone'] ?? null,
            ]);

            $this->synchroniserBiens($soumission, $data['biens'] ?? []);

            if ($transmettre) {
                $soumission->update([
                    'statut' => SoumissionPromoteur::STATUT_SOUMISE,
                    'soumise_le' => now(),
                ]);
            }
        });

        if ($transmettre) {
            return redirect()->route('promoteur.depot', $token);
        }

        return back()->with('ok', 'Brouillon enregistré. Vous pouvez revenir plus tard avec le même lien.');
    }

    /**
     * Les biens sont mis à jour par identifiant plutôt que supprimés puis
     * recréés : les photos déjà déposées sont rattachées à un bien, les
     * recréer romprait le lien.
     *
     * @param  array<int, array<string, mixed>>  $lignes
     */
    private function synchroniserBiens(SoumissionPromoteur $soumission, array $lignes): void
    {
        $conserves = [];

        foreach (array_values($lignes) as $ordre => $ligne) {
            $attributs = [
                'site' => $ligne['site'],
                'type_bien' => $ligne['type_bien'],
                'libelle' => $ligne['libelle'],
                'superficie_min' => $ligne['superficie_min'] ?? null,
                'superficie_max' => $ligne['superficie_max'] ?? null,
                'superficie_utile' => $ligne['superficie_utile'] ?? null,
                'nb_pieces' => $ligne['nb_pieces'] ?? null,
                'disponibilite' => $ligne['disponibilite'] ?? null,
                'modes_acquisition' => array_values($ligne['modes_acquisition'] ?? []),
                'prix_unitaire' => $ligne['prix_unitaire'] ?? null,
                'prix_au_m2' => (bool) ($ligne['prix_au_m2'] ?? false),
                'delai_reglement' => $ligne['delai_reglement'] ?? null,
                'documents' => array_values($ligne['documents'] ?? []),
                'commentaire' => $ligne['commentaire'] ?? null,
                'ordre' => $ordre,
            ];

            $bien = ! empty($ligne['id'])
                ? $soumission->biens()->find($ligne['id'])
                : null;

            if ($bien) {
                $bien->update($attributs);
            } else {
                $bien = $soumission->biens()->create($attributs);
            }

            $conserves[] = $bien->id;

            $this->synchroniserLots($bien, $ligne['lots'] ?? '', $ligne['ilot'] ?? null);
        }

        // Les biens retirés du formulaire disparaissent, avec leurs lots et
        // leurs fichiers (cascade sur la clé étrangère).
        $soumission->biens()->whereNotIn('id', $conserves ?: [0])->delete();
    }

    /**
     * Le promoteur saisit ses lots en une ligne : « 13;14, 15;16 ». On éclate
     * sur les séparateurs usuels plutôt que de lui imposer un champ par lot.
     */
    private function synchroniserLots(BienPropose $bien, string $saisie, ?string $ilot): void
    {
        $numeros = collect(preg_split('/[;,\n]+/', $saisie) ?: [])
            ->map(fn (string $n) => trim($n))
            ->filter()
            ->unique()
            ->take(200);

        $bien->lots()->delete();

        foreach ($numeros as $numero) {
            $bien->lots()->create(['numero' => $numero, 'ilot' => $ilot ?: null]);
        }
    }

    public function televerser(Request $request, string $token): RedirectResponse
    {
        $invitation = $this->invitation($token);
        abort_unless($invitation->estUtilisable(), 410);

        $soumission = $this->brouillon($invitation);
        abort_unless($soumission->estBrouillon(), 409);

        $request->validate([
            'bien_propose_id' => ['nullable', 'integer'],
            'categorie' => ['required', Rule::in(['photo', 'document'])],
            // Types fermés : on n'accepte ni archive, ni fichier exécutable, ni
            // document bureautique susceptible de porter des macros.
            'fichiers' => ['required', 'array', 'max:12'],
            'fichiers.*' => ['file', 'max:'.self::TAILLE_MAX_KO, 'mimes:jpg,jpeg,png,webp,pdf'],
        ], [
            'fichiers.*.mimes' => 'Formats acceptés : JPEG, PNG, WebP et PDF.',
            'fichiers.*.max' => 'Chaque fichier doit peser moins de 8 Mo.',
        ]);

        $bien = $request->filled('bien_propose_id')
            ? $soumission->biens()->find($request->integer('bien_propose_id'))
            : null;

        $categorie = $request->string('categorie')->toString();

        if ($categorie === FichierPropose::CATEGORIE_PHOTO
            && $soumission->fichiers()->where('categorie', FichierPropose::CATEGORIE_PHOTO)->count() >= self::PHOTOS_MAX) {
            return back()->withErrors(['fichiers' => 'Maximum '.self::PHOTOS_MAX.' photos par dépôt.']);
        }

        foreach ($request->file('fichiers') as $fichier) {
            // Disque privé, nom généré : le nom d'origine n'est jamais réutilisé
            // dans le chemin, il pourrait contenir n'importe quoi.
            $chemin = $fichier->store("depots/{$soumission->id}", 'local');

            $soumission->fichiers()->create([
                'bien_propose_id' => $bien?->id,
                'categorie' => $categorie,
                'chemin' => $chemin,
                'nom_original' => mb_substr($fichier->getClientOriginalName(), 0, 255),
                'mime' => $fichier->getMimeType() ?: 'application/octet-stream',
                'taille' => $fichier->getSize() ?: 0,
            ]);
        }

        return back()->with('ok', 'Fichier(s) ajouté(s).');
    }

    public function supprimerFichier(string $token, FichierPropose $fichier): RedirectResponse
    {
        $invitation = $this->invitation($token);
        abort_unless($invitation->estUtilisable(), 410);

        $soumission = $this->brouillon($invitation);

        // Un jeton ne donne accès qu'à ses propres fichiers.
        abort_unless($fichier->soumission_id === $soumission->id, 404);
        abort_unless($soumission->estBrouillon(), 409);

        Storage::disk('local')->delete($fichier->chemin);
        $fichier->delete();

        return back()->with('ok', 'Fichier supprimé.');
    }

    /**
     * Affiche une pièce déposée, pour que le promoteur revoie ce qu'il a envoyé.
     *
     * Le jeton ne donne accès qu'aux fichiers de son propre dépôt : sans cette
     * vérification, un promoteur pourrait lire les plans d'un concurrent en
     * changeant l'identifiant dans l'URL.
     */
    public function fichier(string $token, FichierPropose $fichier): Response
    {
        $invitation = $this->invitation($token);
        $soumission = $this->brouillon($invitation);

        abort_unless($fichier->soumission_id === $soumission->id, 404);
        abort_unless(Storage::disk('local')->exists($fichier->chemin), 404);

        return response(Storage::disk('local')->get($fichier->chemin), 200, [
            'Content-Type' => $fichier->mime,
            'Content-Disposition' => 'inline; filename="'.addslashes($fichier->nom_original).'"',
        ]);
    }

    private function invitation(string $token): InvitationPromoteur
    {
        return InvitationPromoteur::where('token', $token)->firstOrFail();
    }

    /**
     * Le brouillon en cours, ou la dernière soumission si elle a été transmise.
     * Un même lien ne rouvre pas un nouveau formulaire à chaque visite.
     */
    private function brouillon(InvitationPromoteur $invitation): SoumissionPromoteur
    {
        $existante = $invitation->soumissions()->latest('id')->first();

        if ($existante) {
            return $existante;
        }

        return $invitation->soumissions()->create([
            'promoteur' => $invitation->promoteur,
            'contact' => $invitation->contact,
            'email' => $invitation->email,
            'telephone' => $invitation->telephone,
            'statut' => SoumissionPromoteur::STATUT_BROUILLON,
        ]);
    }
}
