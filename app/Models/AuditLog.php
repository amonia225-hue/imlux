<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'action', 'model_type', 'model_id', 'summary', 'changes', 'ip',
    ];

    protected function casts(): array
    {
        return ['changes' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enregistre une action sur un modèle, uniquement si un admin est connecté.
     */
    public static function record(string $action, Model $model): void
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return; // pas de log pour les seeds, commandes, ou actions souscripteur
        }

        // Champs sensibles à ne jamais journaliser
        $hidden = ['password', 'remember_token'];
        $changes = collect($action === 'updated' ? $model->getChanges() : $model->getAttributes())
            ->except(array_merge($hidden, ['updated_at', 'created_at']))
            ->toArray();

        // Ignore les mises à jour automatiques sans intérêt (ex: recompte total_lots, mensualité recalculée)
        if ($action === 'updated') {
            $bruit = ['total_lots', 'mensualite'];
            if (empty($changes) || empty(array_diff(array_keys($changes), $bruit))) {
                return;
            }
        }

        static::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $action,
            'model_type' => class_basename($model),
            'model_id' => $model->getKey(),
            'summary' => method_exists($model, 'auditLabel') ? $model->auditLabel() : null,
            'changes' => $changes ?: null,
            'ip' => request()->ip(),
        ]);
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'created' => 'Création',
            'updated' => 'Modification',
            'deleted' => 'Suppression',
            default => $this->action,
        };
    }
}
