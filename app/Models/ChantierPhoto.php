<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChantierPhoto extends Model
{
    protected $fillable = [
        'chantier_etape_id', 'path', 'legende', 'ordre',
    ];

    public function etape(): BelongsTo
    {
        return $this->belongsTo(ChantierEtape::class, 'chantier_etape_id');
    }
}
