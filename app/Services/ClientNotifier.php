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
}
