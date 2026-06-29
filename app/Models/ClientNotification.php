<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientNotification extends Model
{
    protected $fillable = [
        'souscripteur_id', 'type', 'title', 'body', 'data', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function souscripteur(): BelongsTo
    {
        return $this->belongsTo(Souscripteur::class);
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }
}
