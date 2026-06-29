<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;

/**
 * Journalise automatiquement les créations / modifications / suppressions
 * effectuées par un administrateur connecté.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn ($model) => AuditLog::record('created', $model));
        static::updated(fn ($model) => AuditLog::record('updated', $model));
        static::deleted(fn ($model) => AuditLog::record('deleted', $model));
    }
}
