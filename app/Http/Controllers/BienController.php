<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BienController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBien($request);
        $data['cloture_incluse'] = $request->boolean('cloture_incluse');
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('biens', 'public');
        }

        Bien::create($data);

        return redirect(route('admin.dashboard') . '#biens')->with('success', 'Bien publié sur le site.');
    }

    public function update(Request $request, Bien $bien): RedirectResponse
    {
        $data = $this->validateBien($request);
        $data['cloture_incluse'] = $request->boolean('cloture_incluse');
        if ($request->hasFile('photo')) {
            if ($bien->photo) {
                Storage::disk('public')->delete($bien->photo);
            }
            $data['photo'] = $request->file('photo')->store('biens', 'public');
        }

        $bien->update($data);

        return redirect(route('admin.dashboard') . '#biens')->with('success', 'Bien mis à jour.');
    }

    public function destroy(Bien $bien): RedirectResponse
    {
        if ($bien->photo) {
            Storage::disk('public')->delete($bien->photo);
        }
        $bien->delete();

        return redirect(route('admin.dashboard') . '#biens')->with('success', 'Bien supprimé.');
    }

    private function validateBien(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => ['nullable', 'string', 'max:150'],
            'surface' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'apport_pct' => ['required', 'integer', 'min:0', 'max:100'],
            'cloture_prix' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:disponible,reserve,vendu'],
            'description' => ['nullable', 'string', 'max:2000'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
