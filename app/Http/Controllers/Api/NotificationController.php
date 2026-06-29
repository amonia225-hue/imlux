<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->appNotifications()
            ->limit(100)
            ->get()
            ->map(fn (ClientNotification $n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'data' => $n->data,
                'read' => ! $n->isUnread(),
                'created_at' => $n->created_at->toIso8601String(),
                'date_display' => $n->created_at->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'data' => $notifications,
            'unread' => $request->user()->appNotifications()->whereNull('read_at')->count(),
        ]);
    }

    /**
     * Marque une notification (ou toutes) comme lue(s).
     */
    public function markRead(Request $request): JsonResponse
    {
        $query = $request->user()->appNotifications()->whereNull('read_at');

        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        $query->update(['read_at' => now()]);

        return response()->json(['message' => 'Marqué comme lu.']);
    }
}
