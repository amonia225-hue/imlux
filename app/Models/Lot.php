<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lot extends Model
{
    use Auditable;

    protected $fillable = [
        'programme_id', 'ilot_id', 'reference', 'type_logement', 'price', 'surface', 'status', 'description',
    ];

    public function auditLabel(): string
    {
        return 'Lot ' . $this->reference;
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'surface' => 'decimal:2',
        ];
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function ilot(): BelongsTo
    {
        return $this->belongsTo(Ilot::class);
    }

    /** Souscription active liée à ce lot (la plus récente). */
    public function souscription(): HasOne
    {
        return $this->hasOne(Souscription::class)->latestOfMany();
    }

    /** Couleur de statut : disponible=rouge, réservé/en cours=orange, soldé/vendu=vert. */
    public function statusColor(): string
    {
        return match ($this->status) {
            'vendu' => 'green',
            'reserve' => 'orange',
            default => 'red',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'vendu' => 'Soldé',
            'reserve' => 'Réservé / en cours',
            default => 'Disponible',
        };
    }
}
