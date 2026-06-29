<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChantierEtape extends Model
{
    use Auditable;

    protected $fillable = [
        'programme_id', 'title', 'description', 'progress',
        'status', 'date_prevue', 'date_realisee', 'photo', 'ordre',
    ];

    public function auditLabel(): string
    {
        return 'Étape ' . $this->title;
    }

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'ordre' => 'integer',
            'date_prevue' => 'date',
            'date_realisee' => 'date',
        ];
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ChantierPhoto::class)->orderBy('ordre')->orderBy('id');
    }

    /**
     * Toutes les images de l'étape (galerie + ancienne photo unique éventuelle).
     * @return array<int, array{id: int|null, url: string}>
     */
    public function images(): array
    {
        $images = [];
        if ($this->photo) {
            $images[] = ['id' => null, 'url' => asset('storage/' . $this->photo)];
        }
        $gallery = $this->relationLoaded('photos') ? $this->photos : $this->photos()->get();
        foreach ($gallery as $p) {
            $images[] = ['id' => $p->id, 'url' => asset('storage/' . $p->path)];
        }
        return $images;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'termine' => 'Terminé',
            'en_cours' => 'En cours',
            default => 'À venir',
        };
    }
}
