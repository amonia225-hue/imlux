<?php

namespace App\Services;

use App\Models\Souscripteur;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envoi de notifications push via Firebase Cloud Messaging (API HTTP v1).
 *
 * Configuration (.env) :
 *   FCM_PROJECT_ID=mon-projet
 *   FCM_CREDENTIALS=storage/app/firebase/service-account.json
 *
 * Tant que ces variables ne sont pas définies, le service ne fait rien
 * (l'application fonctionne normalement, les push sont simplement ignorés).
 */
class FcmService
{
    private ?string $projectId;
    private ?string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.fcm.project_id');
        $path = config('services.fcm.credentials');
        $this->credentialsPath = $path ? base_path($path) : null;
    }

    public function isConfigured(): bool
    {
        return $this->projectId && $this->credentialsPath && is_file($this->credentialsPath);
    }

    /**
     * Envoie une notification à tous les appareils d'un souscripteur.
     */
    public function notifySouscripteur(Souscripteur $souscripteur, string $title, string $body, array $data = []): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $tokens = $souscripteur->deviceTokens()->pluck('token')->unique()->all();
        foreach ($tokens as $token) {
            $this->sendToToken($token, $title, $body, $data);
        }
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $accessToken = $this->accessToken();
        if (! $accessToken) {
            return;
        }

        try {
            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", [
                    'message' => [
                        'token' => $token,
                        'notification' => ['title' => $title, 'body' => $body],
                        'data' => array_map('strval', $data),
                        'android' => ['priority' => 'high'],
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('FCM envoi échoué', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Throwable $e) {
            Log::warning('FCM exception : ' . $e->getMessage());
        }
    }

    /**
     * Récupère un jeton d'accès OAuth2 à partir du compte de service (mis en cache ~55 min).
     */
    private function accessToken(): ?string
    {
        return Cache::remember('fcm_access_token', now()->addMinutes(55), function () {
            try {
                $sa = json_decode(file_get_contents($this->credentialsPath), true);
                $now = time();

                $jwtHeader = $this->b64(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
                $jwtClaim = $this->b64(json_encode([
                    'iss' => $sa['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud' => $sa['token_uri'],
                    'iat' => $now,
                    'exp' => $now + 3600,
                ]));

                $signatureInput = $jwtHeader . '.' . $jwtClaim;
                openssl_sign($signatureInput, $signature, $sa['private_key'], 'sha256WithRSAEncryption');
                $jwt = $signatureInput . '.' . $this->b64($signature);

                $response = Http::asForm()->post($sa['token_uri'], [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

                return $response->json('access_token');
            } catch (\Throwable $e) {
                Log::warning('FCM token OAuth échoué : ' . $e->getMessage());
                return null;
            }
        });
    }

    private function b64(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
