<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Lot;
use App\Models\Programme;
use App\Models\Souscripteur;
use App\Models\Souscription;
use App\Models\Versement;
use App\Services\ClientNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ======== DASHBOARD ========
    public function dashboard(): View
    {
        $programmes = Programme::withCount('lots', 'souscriptions')
            ->with(['ilots.lots.souscription.souscripteur', 'etapes.photos'])
            ->get();
        $souscripteurs = Souscripteur::with('souscriptions')->get();
        $souscriptions = Souscription::with(['souscripteur', 'programme', 'lot', 'versements'])->latest()->get();
        $versements = Versement::with('souscription.souscripteur', 'souscription.programme')->latest()->get();
        $lots = Lot::with('programme', 'ilot')->get();

        $totalEncaisse = (float) Versement::sum('amount');
        // Montant en cours = reste à payer cumulé des souscriptions non annulées
        $montantEnCours = $souscriptions->where('status', '!=', 'annule')
            ->sum(fn ($s) => $s->resteAPayer());

        $stats = [
            'programmes' => $programmes->count(),
            'souscripteurs' => $souscripteurs->count(),
            'lots_total' => $lots->count(),
            'lots_disponibles' => $lots->where('status', 'disponible')->count(),
            'lots_reserves' => $lots->where('status', 'reserve')->count(),
            'lots_vendus' => $lots->where('status', 'vendu')->count(),
            'total_encaisse' => $totalEncaisse,
            'montant_en_cours' => $montantEnCours,
            'total_prevision' => $souscriptions->sum(fn ($s) => (float) $s->total_price),
            'souscriptions_en_cours' => $souscriptions->where('status', 'en_cours')->count(),
            'souscriptions_soldes' => $souscriptions->where('status', 'solde')->count(),
            'frais_encaisses' => (float) $souscripteurs->where('frais_ouverture_payes', true)->sum('frais_ouverture'),
            'frais_dus' => (float) $souscripteurs->where('frais_ouverture_payes', false)->sum('frais_ouverture'),
        ];

        $bilanData = $versements->map(function ($v) {
            return [
                'id' => $v->id,
                'date' => $v->payment_date->format('Y-m-d'),
                'date_display' => $v->payment_date->format('d/m/Y'),
                'souscripteur' => $v->souscription->souscripteur->fullName(),
                'programme' => $v->souscription->programme->name,
                'programme_id' => $v->souscription->programme->id,
                'amount' => (float) $v->amount,
                'method' => $v->payment_method,
                'reference' => $v->reference ?? '\u2014',
                'facture_url' => route('pdf.facture', $v->id),
            ];
        })->values();

        $auditLogs = AuditLog::latest()->limit(120)->get();
        $demandes = Souscripteur::where('statut', 'en_attente')->latest()->get();
        $biens = \App\Models\Bien::orderBy('ordre')->latest()->get();

        return view('admin.dashboard', compact(
            'programmes', 'souscripteurs', 'souscriptions', 'versements', 'lots', 'stats', 'bilanData', 'auditLogs', 'demandes', 'biens'
        ));
    }

    // ======== PROGRAMMES ========
    public function storeProgramme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'location' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'surface_utile' => ['nullable', 'numeric', 'min:0'],
            'surface_totale' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:actif,termine,suspendu'],
        ]);

        Programme::create($validated);

        return redirect(route('admin.dashboard') . '#programmes')->with('success', 'Programme cree avec succes.');
    }

    public function updateProgramme(Request $request, Programme $programme): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'location' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'surface_utile' => ['nullable', 'numeric', 'min:0'],
            'surface_totale' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:actif,termine,suspendu'],
        ]);

        $programme->update($validated);

        return redirect(route('admin.dashboard') . '#programmes')->with('success', 'Programme mis a jour.');
    }

    public function destroyProgramme(Programme $programme): RedirectResponse
    {
        if ($programme->souscriptions()->exists()) {
            return redirect(route('admin.dashboard') . '#programmes')
                ->withErrors(['delete' => 'Impossible de supprimer : ce programme a des souscriptions. Supprimez-les d\'abord.']);
        }

        $programme->lots()->delete();
        $programme->delete();

        return redirect(route('admin.dashboard') . '#programmes')->with('success', 'Programme supprime.');
    }

    // ======== LOTS ========
    public function storeLot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'reference' => ['required', 'string', 'max:50'],
            'type_logement' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'surface' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Lot::create($validated);

        // Met à jour le total_lots du programme
        $programme = Programme::find($validated['programme_id']);
        $programme->update(['total_lots' => $programme->lots()->count()]);

        return redirect(route('admin.dashboard') . '#lots')->with('success', 'Lot cree avec succes.');
    }

    public function updateLot(Request $request, Lot $lot): RedirectResponse
    {
        $validated = $request->validate([
            'reference' => ['required', 'string', 'max:50'],
            'type_logement' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'surface' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:disponible,reserve,vendu'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $lot->update($validated);

        return redirect(route('admin.dashboard') . '#lots')->with('success', 'Lot mis a jour.');
    }

    public function destroyLot(Lot $lot): RedirectResponse
    {
        if ($lot->programme && $lot->programme->souscriptions()->where('lot_id', $lot->id)->exists()) {
            return redirect(route('admin.dashboard') . '#lots')
                ->withErrors(['delete' => 'Impossible de supprimer : ce lot est lie a une souscription.']);
        }

        $programme = $lot->programme;
        $lot->delete();
        $programme?->update(['total_lots' => $programme->lots()->count()]);

        return redirect(route('admin.dashboard') . '#lots')->with('success', 'Lot supprime.');
    }

    // ======== SOUSCRIPTEURS ========
    public function storeSouscripteur(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'date_naissance' => ['nullable', 'date'],
            'id_type' => ['nullable', 'string', 'max:50'],
            'id_number' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['uid'] = Souscripteur::generateUid();

        Souscripteur::create($validated);

        return redirect(route('admin.dashboard') . '#souscripteurs')->with('success', 'Adhérent enregistré. ID : ' . $validated['uid']);
    }

    public function updateSouscripteur(Request $request, Souscripteur $souscripteur, ClientNotifier $notifier): RedirectResponse
    {
        $fraisEtaitPayes = (bool) $souscripteur->frais_ouverture_payes;

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'date_naissance' => ['nullable', 'date'],
            'id_type' => ['nullable', 'string', 'max:50'],
            'id_number' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:500'],
            'app_access' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
            'frais_ouverture' => ['nullable', 'numeric', 'min:0'],
            'frais_ouverture_payes' => ['nullable', 'boolean'],
        ]);

        // Accès application mobile + frais d'ouverture (cases à cocher)
        $validated['app_access'] = $request->boolean('app_access');
        $validated['frais_ouverture_payes'] = $request->boolean('frais_ouverture_payes');
        $validated['frais_ouverture'] = $validated['frais_ouverture'] ?? Souscripteur::FRAIS_OUVERTURE_DEFAUT;

        // Date de paiement des frais : fixée au passage à « payés », effacée sinon
        if ($validated['frais_ouverture_payes']) {
            $validated['frais_ouverture_date'] = $souscripteur->frais_ouverture_date ?? now()->toDateString();
        } else {
            $validated['frais_ouverture_date'] = null;
        }

        // Mot de passe : ne mettre à jour que s'il est renseigné
        if (! empty($validated['password'])) {
            $souscripteur->password = Hash::make($validated['password']);
        }
        unset($validated['password']);

        // L'accès app nécessite un email
        if ($validated['app_access'] && empty($validated['email'])) {
            return redirect(route('admin.dashboard') . '#souscripteurs')
                ->withErrors(['app' => 'Un email est obligatoire pour activer l\'accès à l\'application mobile.']);
        }

        $souscripteur->fill($validated)->save();

        // Notification client : frais d'ouverture nouvellement réglés
        if (! $fraisEtaitPayes && $souscripteur->frais_ouverture_payes) {
            $montant = number_format((float) $souscripteur->frais_ouverture, 0, ',', ' ');
            $notifier->notify(
                $souscripteur,
                'frais',
                'Frais de dossier réglés',
                "Vos frais d'ouverture de dossier ({$montant} FCFA) ont été enregistrés comme payés. Votre reçu est disponible.",
                []
            );
        }

        return redirect(route('admin.dashboard') . '#souscripteurs')->with('success', 'Adhérent mis à jour.');
    }

    public function validateAdherent(Souscripteur $souscripteur, ClientNotifier $notifier): RedirectResponse
    {
        $souscripteur->update(['statut' => 'valide', 'app_access' => (bool) $souscripteur->email]);

        if ($souscripteur->email) {
            $notifier->notify(
                $souscripteur,
                'compte',
                'Compte validé',
                'Votre compte a été validé par le cabinet. Vous pouvez désormais accéder à votre espace membre.',
                []
            );
        }

        return redirect(route('admin.dashboard') . '#adhesions')->with('success', 'Adhésion validée.');
    }

    public function rejectAdherent(Souscripteur $souscripteur): RedirectResponse
    {
        $souscripteur->update(['statut' => 'refuse', 'app_access' => false]);

        return redirect(route('admin.dashboard') . '#adhesions')->with('success', 'Demande refusée.');
    }

    public function destroySouscripteur(Souscripteur $souscripteur): RedirectResponse
    {
        if ($souscripteur->souscriptions()->exists()) {
            return redirect(route('admin.dashboard') . '#souscripteurs')
                ->withErrors(['delete' => 'Impossible de supprimer : ce souscripteur a des souscriptions.']);
        }

        $souscripteur->delete();

        return redirect(route('admin.dashboard') . '#souscripteurs')->with('success', 'Adhérent supprimé.');
    }

    // ======== SOUSCRIPTIONS ========
    public function storeSouscription(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'souscripteur_id' => ['required', 'exists:souscripteurs,id'],
            'programme_id' => ['required', 'exists:programmes,id'],
            'lot_id' => ['required', 'exists:lots,id'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'apport_initial' => ['nullable', 'numeric', 'min:0'],
            'nb_mensualites' => ['required', 'integer', 'min:1', 'max:360'],
            'rythme' => ['required', 'in:mensuel,trimestriel'],
            'date_souscription' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $totalPrice = (float) $validated['total_price'];
        $apport = (float) ($validated['apport_initial'] ?? 0);
        $nbMens = (int) $validated['nb_mensualites'];
        $restant = $totalPrice - $apport;
        $validated['mensualite'] = $nbMens > 0 ? round($restant / $nbMens, 2) : 0;

        $souscription = Souscription::create($validated);

        // Marquer le lot comme réservé
        Lot::where('id', $validated['lot_id'])->update(['status' => 'reserve']);

        // Si apport initial > 0, créer un versement automatique
        if ($apport > 0) {
            Versement::create([
                'souscription_id' => $souscription->id,
                'amount' => $apport,
                'payment_date' => $validated['date_souscription'],
                'payment_method' => 'especes',
                'reference' => 'APPORT-' . $souscription->id,
                'note' => 'Apport initial',
            ]);
        }

        return redirect(route('admin.dashboard') . '#souscriptions')->with('success', 'Adhésion créée avec succès.');
    }

    public function updateSouscription(Request $request, Souscription $souscription): RedirectResponse
    {
        $validated = $request->validate([
            'total_price' => ['required', 'numeric', 'min:0'],
            'nb_mensualites' => ['required', 'integer', 'min:1', 'max:360'],
            'rythme' => ['required', 'in:mensuel,trimestriel'],
            'date_souscription' => ['required', 'date'],
            'status' => ['required', 'in:en_cours,solde,annule'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $souscription->update($validated);

        // Recalcule l'échéance : reste réparti sur les échéances restantes
        $souscription->update(['mensualite' => $souscription->echeanceActuelle()]);

        // Synchronise le statut du lot avec celui de la souscription
        if ($souscription->lot) {
            $lotStatus = match ($validated['status']) {
                'annule' => 'disponible',
                'solde' => 'vendu',
                default => 'reserve',
            };
            $souscription->lot->update(['status' => $lotStatus]);
        }

        return redirect(route('admin.dashboard') . '#souscriptions')->with('success', 'Adhésion mise à jour.');
    }

    public function destroySouscription(Souscription $souscription): RedirectResponse
    {
        // Libere le lot et supprime les versements rattaches (cascade FK)
        $souscription->lot?->update(['status' => 'disponible']);
        $souscription->delete();

        return redirect(route('admin.dashboard') . '#souscriptions')->with('success', 'Adhésion supprimée, lot libéré.');
    }

    // ======== VERSEMENTS ========
    public function storeVersement(Request $request, ClientNotifier $notifier): RedirectResponse
    {
        $validated = $request->validate([
            'souscription_id' => ['required', 'exists:souscriptions,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:especes,cheque,virement,mobile_money'],
            'reference' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $versement = Versement::create($validated);

        $souscription = Souscription::with('souscripteur')->find($validated['souscription_id']);

        // Redistribue le reste à payer sur les échéances restantes
        $souscription->mensualite = $souscription->echeanceActuelle();

        // Vérifier si la souscription est soldée
        if ($souscription->isSolde()) {
            $souscription->status = 'solde';
            Lot::where('id', $souscription->lot_id)->update(['status' => 'vendu']);
        }
        $souscription->save();

        // Notification client (centre de notifications + push FCM)
        if ($souscription->souscripteur) {
            $montant = number_format((float) $versement->amount, 0, ',', ' ');
            if (! $souscription->echeancesDebloquees()) {
                $resteApport = number_format($souscription->resteApport(), 0, ',', ' ');
                $pct = $souscription->apportRequisPct();
                $body = "Versement de {$montant} FCFA enregistré. Apport de {$pct}% non encore atteint : il reste {$resteApport} FCFA pour débloquer l'échéancier.";
            } else {
                $reste = number_format($souscription->resteAPayer(), 0, ',', ' ');
                $body = "Un versement de {$montant} FCFA a été enregistré. Reste à payer : {$reste} FCFA.";
            }
            $notifier->notify(
                $souscription->souscripteur,
                'versement',
                'Versement enregistré',
                $body,
                ['souscription_id' => $souscription->id]
            );
        }

        return redirect(route('admin.dashboard') . '#versements')->with('success', 'Versement enregistre avec succes.');
    }

    public function destroyVersement(Versement $versement): RedirectResponse
    {
        $souscription = $versement->souscription;
        $versement->delete();

        // Recalcule le statut apres suppression : si plus solde, repasse en cours
        if ($souscription) {
            if (! $souscription->isSolde() && $souscription->status === 'solde') {
                $souscription->status = 'en_cours';
                $souscription->lot?->update(['status' => 'reserve']);
            }
            // Redistribue le reste sur les échéances restantes
            $souscription->mensualite = $souscription->echeanceActuelle();
            $souscription->save();
        }

        return redirect(route('admin.dashboard') . '#versements')->with('success', 'Versement supprime.');
    }
}
