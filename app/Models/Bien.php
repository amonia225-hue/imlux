<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    use Auditable;

    protected $fillable = [
        'name', 'type', 'surface', 'price', 'apport_pct',
        'cloture_incluse', 'cloture_prix', 'description', 'photo', 'status', 'ordre',
    ];

    protected function casts(): array
    {
        return [
            'surface' => 'decimal:2',
            'price' => 'decimal:2',
            'cloture_prix' => 'decimal:2',
            'apport_pct' => 'integer',
            'cloture_incluse' => 'boolean',
        ];
    }

    public function auditLabel(): string
    {
        return 'Bien ' . $this->name;
    }

    /** Apport initial requis (par défaut 35 % du prix). */
    public function apportInitial(): float
    {
        return round((float) $this->price * $this->apport_pct / 100, 2);
    }

    /** Coût de la clôture en option (0 si déjà incluse). */
    public function prixClotureOption(): float
    {
        return $this->cloture_incluse ? 0 : (float) $this->cloture_prix;
    }

    /** Montant total si le client ajoute la clôture (sinon = prix). */
    public function montantAvecCloture(): float
    {
        return (float) $this->price + $this->prixClotureOption();
    }

    public function clotureLabel(): string
    {
        return $this->cloture_incluse ? 'Livré avec clôture' : 'Livré sans clôture';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'vendu' => 'Vendu',
            'reserve' => 'Réservé',
            default => 'Disponible',
        };
    }
}
