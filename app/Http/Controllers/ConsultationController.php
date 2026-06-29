<?php

namespace App\Http\Controllers;

use App\Models\Souscripteur;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function index(): View
    {
        return view('consultation.index');
    }

    public function show(Request $request): View
    {
        $uid = $request->input('uid');
        $souscripteur = Souscripteur::where('uid', $uid)->first();

        if (! $souscripteur) {
            return view('consultation.index', ['error' => 'Identifiant introuvable. Vérifiez votre ID unique.']);
        }

        return $this->renderSouscripteur($souscripteur);
    }

    /**
     * Accès client par téléphone + date de naissance.
     */
    public function showByPhone(Request $request): View
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'date_naissance' => ['required', 'date'],
        ]);

        // Normalise le téléphone (supprime espaces) pour une comparaison souple
        $phone = preg_replace('/\s+/', '', $validated['phone']);

        $souscripteur = Souscripteur::whereNotNull('phone')
            ->whereDate('date_naissance', $validated['date_naissance'])
            ->get()
            ->first(fn ($s) => preg_replace('/\s+/', '', $s->phone) === $phone);

        if (! $souscripteur) {
            return view('consultation.index', [
                'errorPhone' => 'Aucun dossier ne correspond à ce téléphone et cette date de naissance.',
            ]);
        }

        return $this->renderSouscripteur($souscripteur);
    }

    public function showDirect(string $uid): View
    {
        $souscripteur = Souscripteur::where('uid', $uid)->firstOrFail();

        return $this->renderSouscripteur($souscripteur);
    }

    private function renderSouscripteur(Souscripteur $souscripteur): View
    {
        $souscriptions = $souscripteur->souscriptions()
            ->with(['programme.etapes.photos', 'lot', 'versements'])
            ->get();

        return view('consultation.show', compact('souscripteur', 'souscriptions'));
    }
}
