<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    use Auditable;

    protected $fillable = [
        'name', 'location', 'description', 'surface_utile', 'surface_totale', 'total_lots', 'status', 'image',
    ];

    protected function casts(): array
    {
        return ['surface_utile' => 'decimal:2', 'surface_totale' => 'decimal:2'];
    }

    public function auditLabel(): string
    {
        return $this->name;
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class);
    }

    public function ilots(): HasMany
    {
        return $this->hasMany(Ilot::class)->orderBy('ordre');
    }

    public function etapes(): HasMany
    {
        return $this->hasMany(ChantierEtape::class)->orderBy('ordre');
    }

    /**
     * Avancement global du chantier = moyenne des % des étapes.
     */
    public function avancementGlobal(): int
    {
        $etapes = $this->relationLoaded('etapes') ? $this->etapes : $this->etapes()->get();
        if ($etapes->isEmpty()) {
            return 0;
        }
        return (int) round($etapes->avg('progress'));
    }

    public function lotsDisponibles(): int
    {
        return $this->lots()->where('status', 'disponible')->count();
    }

    public function lotsVendus(): int
    {
        return $this->lots()->where('status', 'vendu')->count();
    }

    public function tauxRemplissage(): float
    {
        $total = $this->lots()->count();
        if ($total === 0) return 0;
        return round(($this->lotsVendus() / $total) * 100, 1);
    }

    public function totalEncaisse(): float
    {
        return (float) $this->souscriptions()
            ->join('versements', 'souscriptions.id', '=', 'versements.souscription_id')
            ->sum('versements.amount');
    }

    public function totalPrevision(): float
    {
        return (float) $this->souscriptions()->sum('total_price');
    }
}
