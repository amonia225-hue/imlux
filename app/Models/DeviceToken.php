<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    protected $fillable = [
        'souscripteur_id', 'token', 'platform',
    ];

    public function souscripteur(): BelongsTo
    {
        return $this->belongsTo(Souscripteur::class);
    }
}
