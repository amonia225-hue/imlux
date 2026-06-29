<?php

namespace App\Http\Controllers;

use App\Models\Ilot;
use App\Models\Lot;
use App\Models\Programme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IlotController extends Controller
{
    /**
     * Crée un îlot et génère automatiquement ses lots.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'name' => ['required', 'string', 'max:100'],
            'nb_lots' => ['required', 'integer', 'min:1', 'max:500'],
            'type_logement' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'surface' => ['nullable', 'numeric', 'min:0'],
        ]);

        $ilot = Ilot::create([
            'programme_id' => $validated['programme_id'],
            'name' => $validated['name'],
            'ordre' => Ilot::where('programme_id', $validated['programme_id'])->count(),
        ]);

        // Génère les lots de l'îlot (références name-01, name-02, ...)
        $created = 0;
        for ($i = 1; $i <= (int) $validated['nb_lots']; $i++) {
            $reference = $validated['name'] . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);

            // Évite les collisions sur la contrainte unique (programme_id, reference)
            if (Lot::where('programme_id', $validated['programme_id'])->where('reference', $reference)->exists()) {
                continue;
            }

            Lot::create([
                'programme_id' => $validated['programme_id'],
                'ilot_id' => $ilot->id,
                'reference' => $reference,
                'type_logement' => $validated['type_logement'],
                'price' => $validated['price'],
                'surface' => $validated['surface'] ?? null,
                'status' => 'disponible',
            ]);
            $created++;
        }

        $programme = Programme::find($validated['programme_id']);
        $programme->update(['total_lots' => $programme->lots()->count()]);

        return redirect(route('admin.dashboard') . '#ilots')
            ->with('success', "Îlot « {$ilot->name} » créé avec {$created} lot(s).");
    }

    public function destroy(Ilot $ilot): RedirectResponse
    {
        // Refuse la suppression si un lot de l'îlot est réservé ou vendu
        if ($ilot->lots()->where('status', '!=', 'disponible')->exists()) {
            return redirect(route('admin.dashboard') . '#ilots')
                ->withErrors(['ilot' => 'Impossible de supprimer : cet îlot contient des lots réservés ou vendus.']);
        }

        $programme = $ilot->programme;
        $ilot->lots()->delete();
        $ilot->delete();
        $programme?->update(['total_lots' => $programme->lots()->count()]);

        return redirect(route('admin.dashboard') . '#ilots')->with('success', 'Îlot supprimé.');
    }
}
