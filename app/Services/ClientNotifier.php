<?php

namespace App\Services;

use App\Models\ClientNotification;
use App\Models\Souscripteur;

/**
 * Centre de notifications client : enregistre la notification en base
 * (visible dans l'app et l'espace web) ET envoie un push FCM.
 */
class ClientNotifier
{
    public function __construct(private FcmService $fcm)
    {
    }

    public function notify(Souscripteur $souscripteur, string $type, string $title, string $body, array $data = []): ClientNotification
    {
        $notification = $souscripteur->appNotifications()->create([
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data ?: null,
        ]);

        // Push FCM (sans effet tant que Firebase n'est pas configuré)
        $this->fcm->notifySouscripteur($souscripteur, $title, $body, array_merge($data, [
            'type' => $type,
            'notification_id' => (string) $notification->id,
        ]));

        return $notification;
    }

    /**
     * Notifie TOUS les souscripteurs (ex. publication d'un nouveau bien).
     */
    public function notifyAll(string $type, string $title, string $body, array $data = []): void
    {
        Souscripteur::query()->chunkById(200, function ($souscripteurs) use ($type, $title, $body, $data) {
            foreach ($souscripteurs as $souscripteur) {
                try {
                    $this->notify($souscripteur, $type, $title, $body, $data);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('notifyAll échec souscripteur ' . $souscripteur->id . ' : ' . $e->getMessage());
                }
            }
        });
    }
}
