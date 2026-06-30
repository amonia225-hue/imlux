<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /** La gestion des comptes administrateurs est réservée au super administrateur. */
    private function ensureSuper(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403, 'Action réservée au super administrateur.');
    }

    public function index(): View
    {
        $this->ensureSuper();

        $users = User::orderByDesc('is_super_admin')->orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuper();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'name' => 'nom',
            'email' => 'email',
            'password' => 'mot de passe',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // hashé via le cast 'hashed'
            'is_admin' => true,
            'is_super_admin' => false,
        ]);

        return back()->with('success', 'Compte administrateur créé : '.$data['email']);
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureSuper();

        if ($user->is_super_admin) {
            return back()->with('error', 'Compte super administrateur protégé : suppression interdite.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $email = $user->email;
        $user->delete();

        return back()->with('success', 'Compte supprimé : '.$email);
    }
}
