<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SouscriptionController extends Controller
{
    /**
     * Liste des souscriptions du souscripteur connecté (résumé).
     */
    public function index(Request $request): JsonResponse
    {
        $souscriptions = $request->user()->souscriptions()
            ->with(['programme', 'lot', 'versements'])
            ->latest('date_souscription')
            ->get()
            ->map(fn (Souscription $s) => $this->summary($s));

        return response()->json(['data' => $souscriptions]);
    }

    /**
     * Détail d'une souscription : versements + avancement du chantier.
     */
    public function show(Request $request, Souscription $souscription): JsonResponse
    {
        abort_unless($souscription->souscripteur_id === $request->user()->id, 403);

        $souscription->load(['programme.etapes.photos', 'lot', 'versements' => fn ($q) => $q->orderByDesc('payment_date')]);

        return response()->json([
            ...$this->summary($souscription),
            'versements' => $souscription->versements->map(fn ($v) => [
                'id' => $v->id,
                'amount' => (float) $v->amount,
                'payment_date' => $v->payment_date->toDateString(),
                'payment_method' => $v->payment_method,
                'payment_method_label' => ucfirst(str_replace('_', ' ', $v->payment_method)),
                'reference' => $v->reference,
                'note' => $v->note,
                'facture_url' => URL::temporarySignedRoute('pdf.facture', now()->addDays(7), $v),
                'recu_url' => $v->recu
                    ? URL::temporarySignedRoute('pdf.versement.recu', now()->addDays(7), $v)
                    : null,
            ]),
            'attestation_url' => URL::temporarySignedRoute('pdf.attestation', now()->addDays(7), $souscription),
            'travaux' => $this->travaux($souscription),
        ]);
    }

    /**
     * Avancement des travaux du programme rattaché à la souscription.
     */
    public function travaux(Souscription $souscription): array
    {
        $programme = $souscription->programme;
        $etapes = $programme->relationLoaded('etapes') ? $programme->etapes : $programme->etapes()->get();

        return [
            'avancement_global' => $programme->avancementGlobal(),
            'etapes' => $etapes->map(fn ($e) => [
                'id' => $e->id,
                'title' => $e->title,
                'description' => $e->description,
                'progress' => (int) $e->progress,
                'status' => $e->status,
                'status_label' => $e->statusLabel(),
                'date_prevue' => $e->date_prevue?->toDateString(),
                'date_realisee' => $e->date_realisee?->toDateString(),
                'photo_url' => $e->photo ? url('media/' . $e->photo) : null,
                'images' => array_map(fn ($img) => $img['url'], $e->images()),
            ])->values(),
        ];
    }

    private function summary(Souscription $s): array
    {
        return [
            'id' => $s->id,
            'programme' => [
                'id' => $s->programme->id,
                'name' => $s->programme->name,
                'location' => $s->programme->location,
                'avancement_global' => $s->programme->avancementGlobal(),
            ],
            'lot' => [
                'reference' => $s->lot->reference,
                'type_logement' => $s->lot->type_logement,
                'surface' => $s->lot->surface ? (float) $s->lot->surface : null,
            ],
            'total_price' => (float) $s->total_price,
            'total_verse' => $s->totalVerse(),
            'reste_a_payer' => $s->resteAPayer(),
            'mensualite' => (float) $s->mensualite,
            'nb_mensualites' => (int) $s->nb_mensualites,
            'rythme' => $s->rythme,
            'rythme_label' => $s->rythmeLabel(),
            'echeance_label' => $s->echeanceLabel(),
            'echeance_actuelle' => $s->echeanceActuelle(),
            'echeances_restantes' => $s->echeancesRestantes(),
            'progress_percent' => $s->progressPercent(),
            'status' => $s->status,
            'date_souscription' => $s->date_souscription->toDateString(),
        ];
    }
}
