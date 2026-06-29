<?php

namespace App\Http\Controllers;

use App\Models\Souscripteur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('public.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:souscripteurs,email'],
            'phone' => ['required', 'string', 'max:30'],
            'date_naissance' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:300'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [], [
            'first_name' => 'prénom', 'last_name' => 'nom', 'date_naissance' => 'date de naissance',
        ]);

        Souscripteur::create([
            'uid' => Souscripteur::generateUid(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_naissance' => $validated['date_naissance'],
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'app_access' => false,      // activé après validation du cabinet
            'statut' => 'en_attente',
        ]);

        return redirect()->route('register.create')->with('registered', true);
    }
}
