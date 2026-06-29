<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Souscripteur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Connexion souscripteur (email + mot de passe) → jeton Sanctum.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:120'],
            'fcm_token' => ['nullable', 'string', 'max:512'],
            'platform' => ['nullable', 'string', 'max:20'],
        ]);

        $souscripteur = Souscripteur::where('email', $credentials['email'])->first();

        if (! $souscripteur || ! $souscripteur->password || ! Hash::check($credentials['password'], $souscripteur->password)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        if (! $souscripteur->app_access) {
            throw ValidationException::withMessages([
                'email' => ['Votre accès à l\'application n\'est pas activé. Contactez votre conseiller.'],
            ]);
        }

        $souscripteur->forceFill(['last_login_at' => now()])->save();

        // Enregistre le token FCM si fourni
        if (! empty($credentials['fcm_token'])) {
            $souscripteur->deviceTokens()->updateOrCreate(
                ['token' => $credentials['fcm_token']],
                ['platform' => $credentials['platform'] ?? null]
            );
        }

        $token = $souscripteur->createToken($credentials['device_name'] ?? 'mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'souscripteur' => $this->profile($souscripteur),
        ]);
    }

    /**
     * Profil du souscripteur connecté.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($this->profile($request->user()));
    }

    /**
     * Déconnexion : révoque le jeton courant.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.']);
    }

    private function profile(Souscripteur $s): array
    {
        return [
            'id' => $s->id,
            'uid' => $s->uid,
            'first_name' => $s->first_name,
            'last_name' => $s->last_name,
            'full_name' => $s->fullName(),
            'email' => $s->email,
            'phone' => $s->phone,
            'address' => $s->address,
            'photo_url' => $s->photo ? asset('storage/' . $s->photo) : null,
            'frais_ouverture' => (float) $s->frais_ouverture,
            'frais_ouverture_payes' => (bool) $s->frais_ouverture_payes,
            'frais_recu_url' => $s->frais_ouverture_payes
                ? URL::temporarySignedRoute('pdf.frais', now()->addDays(7), $s)
                : null,
            'notifications_non_lues' => $s->appNotifications()->whereNull('read_at')->count(),
        ];
    }
}
