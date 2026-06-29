<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Souscription extends Model
{
    use Auditable;

    public function auditLabel(): string
    {
        return 'Souscription #' . $this->id . ' — ' . number_format((float) $this->total_price, 0, ',', ' ') . ' FCFA';
    }

    protected $fillable = [
        'souscripteur_id', 'programme_id', 'lot_id',
        'total_price', 'apport_initial', 'nb_mensualites', 'mensualite', 'rythme',
        'date_souscription', 'status', 'notes',
    ];

    public const RYTHMES = [
        'mensuel' => 'Mensuel',
        'trimestriel' => 'Trimestriel',
    ];

    public function rythmeLabel(): string
    {
        return self::RYTHMES[$this->rythme] ?? 'Mensuel';
    }

    /** Libellé du montant d'échéance selon le rythme. */
    public function echeanceLabel(): string
    {
        return match ($this->rythme) {
            'trimestriel' => 'Échéance trimestrielle',
            'semestriel' => 'Échéance semestrielle',
            'annuel' => 'Échéance annuelle',
            default => 'Mensualité',
        };
    }

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'apport_initial' => 'decimal:2',
            'mensualite' => 'decimal:2',
            'date_souscription' => 'date',
        ];
    }

    public function souscripteur(): BelongsTo
    {
        return $this->belongsTo(Souscripteur::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function versements(): HasMany
    {
        return $this->hasMany(Versement::class);
    }

    public function totalVerse(): float
    {
        return (float) $this->versements()->sum('amount');
    }

    public function resteAPayer(): float
    {
        return max(0, (float) $this->total_price - $this->totalVerse());
    }

    public function progressPercent(): float
    {
        if ((float) $this->total_price <= 0) return 0;
        return min(100, round(($this->totalVerse() / (float) $this->total_price) * 100, 1));
    }

    public function isSolde(): bool
    {
        return $this->resteAPayer() <= 0;
    }

    /** Pourcentage d'apport minimum requis avant de débloquer les échéances (configurable). */
    public function apportRequisPct(): int
    {
        return (int) \App\Models\Setting::get('apport_min_pct', '35');
    }

    public function apportRequis(): float
    {
        return round((float) $this->total_price * $this->apportRequisPct() / 100, 2);
    }

    /** Les échéances sont débloquées une fois l'apport minimum atteint. */
    public function echeancesDebloquees(): bool
    {
        return $this->totalVerse() >= $this->apportRequis();
    }

    /** Reste à verser pour atteindre l'apport minimum. */
    public function resteApport(): float
    {
        return max(0, $this->apportRequis() - $this->totalVerse());
    }

    /** Intervalle en mois selon le rythme. */
    public function intervalleMois(): int
    {
        return match ($this->rythme) {
            'trimestriel' => 3,
            'semestriel' => 6,
            'annuel' => 12,
            default => 1,
        };
    }

    /**
     * Nombre de versements déjà effectués au titre des échéances (hors apport initial).
     */
    public function echeancesPayees(): int
    {
        // Tant que l'apport minimum (35 %) n'est pas atteint, aucune échéance ne démarre.
        if (! $this->echeancesDebloquees()) {
            return 0;
        }
        $count = $this->versements()->count();
        if ((float) $this->apport_initial > 0) {
            $count -= 1; // l'apport initial ne compte pas comme une échéance
        }
        return max(0, $count);
    }

    public function echeancesRestantes(): int
    {
        return max(1, (int) $this->nb_mensualites - $this->echeancesPayees());
    }

    /**
     * Montant d'échéance recalculé : reste à payer réparti sur les échéances restantes.
     */
    public function echeanceActuelle(): float
    {
        if ($this->isSolde()) {
            return 0;
        }
        return round($this->resteAPayer() / $this->echeancesRestantes(), 2);
    }

    /**
     * Prochaine échéance non réglée (ou null si soldée / non en cours).
     * @return array{n: int, date: \Carbon\Carbon, montant: float}|null
     */
    public function prochaineEcheance(): ?array
    {
        if ($this->status !== 'en_cours' || $this->isSolde()) {
            return null;
        }
        // L'échéancier reste verrouillé tant que l'apport minimum (35 %) n'est pas atteint.
        if (! $this->echeancesDebloquees()) {
            return null;
        }
        $echeancier = $this->echeancier();
        return $echeancier[$this->echeancesPayees()] ?? null;
    }

    /**
     * Échéancier prévisionnel : une ligne par échéance (date + montant).
     */
    public function echeancier(): array
    {
        $nb = (int) $this->nb_mensualites;
        if ($nb < 1) {
            return [];
        }
        $montant = round(((float) $this->total_price - (float) $this->apport_initial) / $nb, 2);
        $interval = $this->intervalleMois();
        $lignes = [];
        for ($i = 1; $i <= $nb; $i++) {
            $lignes[] = [
                'n' => $i,
                'date' => $this->date_souscription->copy()->addMonths($interval * $i),
                'montant' => $montant,
            ];
        }
        return $lignes;
    }
}
