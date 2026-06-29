<?php

namespace App\Http\Controllers;

use App\Models\ChantierEtape;
use App\Models\ChantierPhoto;
use App\Models\Programme;
use App\Services\ClientNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChantierController extends Controller
{
    public function index(): View
    {
        $programmes = Programme::with('etapes')->orderBy('name')->get();

        return view('admin.chantiers', compact('programmes'));
    }

    public function storeEtape(Request $request, ClientNotifier $notifier): RedirectResponse
    {
        $validated = $this->validateEtape($request);
        unset($validated['photos']);
        $validated['status'] = $this->statusFromProgress($validated['progress'], $request->input('status'));

        $etape = ChantierEtape::create($validated);
        $this->storePhotos($request, $etape);

        // Notifie les souscripteurs du programme d'une nouvelle étape de chantier
        $this->notifyProgramme(
            $etape,
            $notifier,
            'Avancement du chantier',
            "Nouvelle étape « {$etape->title} » ({$etape->progress}%). Avancement global : {$etape->programme->avancementGlobal()}%."
        );

        return redirect(route('admin.dashboard').'#chantiers')->with('success', 'Étape de chantier ajoutée.');
    }

    public function updateEtape(Request $request, ChantierEtape $etape, ClientNotifier $notifier): RedirectResponse
    {
        $validated = $this->validateEtape($request);
        unset($validated['photos']);
        $validated['status'] = $this->statusFromProgress($validated['progress'], $request->input('status'));

        $oldProgress = (int) $etape->progress;
        $etape->update($validated);

        // Ajoute les nouvelles photos (sans supprimer les existantes)
        $this->storePhotos($request, $etape);

        // Notifie les souscripteurs si l'avancement de l'étape a évolué
        if ((int) $etape->progress !== $oldProgress) {
            $message = $etape->status === 'termine'
                ? "Étape « {$etape->title} » terminée. Avancement global : {$etape->programme->avancementGlobal()}%."
                : "Étape « {$etape->title} » : {$etape->progress}%. Avancement global : {$etape->programme->avancementGlobal()}%.";
            $this->notifyProgramme($etape, $notifier, 'Avancement du chantier', $message);
        }

        return redirect(route('admin.dashboard').'#chantiers')->with('success', 'Étape mise à jour.');
    }

    public function destroyEtape(ChantierEtape $etape): RedirectResponse
    {
        if ($etape->photo) {
            Storage::disk('public')->delete($etape->photo);
        }
        foreach ($etape->photos as $p) {
            Storage::disk('public')->delete($p->path);
        }
        $etape->delete();

        return redirect(route('admin.dashboard').'#chantiers')->with('success', 'Étape supprimée.');
    }

    public function destroyPhoto(ChantierPhoto $photo): RedirectResponse
    {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return redirect(route('admin.dashboard').'#chantiers')->with('success', 'Photo supprimée.');
    }

    /**
     * Enregistre les photos de la galerie (champ "photos[]") d'une étape.
     */
    private function storePhotos(Request $request, ChantierEtape $etape): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }
        $ordre = (int) $etape->photos()->max('ordre');
        foreach ($request->file('photos') as $file) {
            $etape->photos()->create([
                'path' => $file->store('chantiers', 'public'),
                'ordre' => ++$ordre,
            ]);
        }
    }

    private function validateEtape(Request $request): array
    {
        return $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'date_prevue' => ['nullable', 'date'],
            'date_realisee' => ['nullable', 'date'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:4096'],
        ]);
    }

    private function statusFromProgress(int $progress, ?string $manual): string
    {
        if ($manual && in_array($manual, ['a_venir', 'en_cours', 'termine'], true)) {
            return $manual;
        }
        return match (true) {
            $progress >= 100 => 'termine',
            $progress > 0 => 'en_cours',
            default => 'a_venir',
        };
    }

    private function notifyProgramme(ChantierEtape $etape, ClientNotifier $notifier, string $title, string $body): void
    {
        $souscripteurs = Programme::find($etape->programme_id)
            ?->souscriptions()->with('souscripteur')->get()
            ->pluck('souscripteur')->filter()->unique('id');

        foreach ($souscripteurs ?? [] as $souscripteur) {
            $notifier->notify($souscripteur, 'travaux', $title, $body, ['programme_id' => $etape->programme_id]);
        }
    }
}
