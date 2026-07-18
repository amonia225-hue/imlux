<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Un bien proposé par un promoteur, tel que décrit dans le formulaire VLOG.
 */
class BienPropose extends Model
{
    protected $table = 'biens_proposes';

    /** Types repris du fichier client, plus « autre » pour ne rien perdre. */
    public const TYPES = [
        'villa_basse' => 'Villa basse',
        'duplex' => 'Villa duplex',
        'appartement' => 'Appartement',
        'terrain' => 'Lot / terrain',
        'autre' => 'Autre',
    ];

    public const MODES = [
        'credit_bancaire' => 'Crédit bancaire',
        // Orthographe corrigée : le fichier source écrit « temprement ».
        'temperament' => 'Tempérament (échelonné)',
        'cash' => 'Cash',
    ];

    /** Checklist « documents disponibles » du formulaire. */
    public const DOCUMENTS = [
        'agrement_promoteur' => 'Agrément promoteur',
        'agrement_programme' => 'Agrément programme',
        'agrement_amenageur' => 'Agrément aménageur foncier',
        'plan_de_masse' => 'Plan de masse',
        'acd_morcele' => 'ACD morcelé',
    ];

    protected $fillable = [
        'soumission_id', 'site', 'type_bien', 'libelle',
        'superficie_min', 'superficie_max', 'superficie_utile', 'nb_pieces',
        'disponibilite', 'modes_acquisition', 'prix_unitaire', 'prix_au_m2',
        'delai_reglement', 'documents', 'commentaire', 'ordre',
    ];

    protected function casts(): array
    {
        return [
            'modes_acquisition' => 'array',
            'documents' => 'array',
            'prix_au_m2' => 'boolean',
            'superficie_min' => 'integer',
            'superficie_max' => 'integer',
            'superficie_utile' => 'integer',
            'nb_pieces' => 'integer',
            'disponibilite' => 'integer',
            'prix_unitaire' => 'integer',
        ];
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type_bien] ?? $this->type_bien;
    }

    /** « 400 à 600 m² », ou « 600 m² » si aucune fourchette n'est donnée. */
    public function superficieLabel(): string
    {
        if ($this->superficie_min === null && $this->superficie_max === null) {
            return '—';
        }

        if ($this->superficie_max === null || $this->superficie_max === $this->superficie_min) {
            return number_format((int) $this->superficie_min, 0, ',', ' ').' m²';
        }

        return number_format((int) $this->superficie_min, 0, ',', ' ')
            .' à '.number_format((int) $this->superficie_max, 0, ',', ' ').' m²';
    }

    public function prixLabel(): string
    {
        if ($this->prix_unitaire === null) {
            return 'Non communiqué';
        }

        return number_format($this->prix_unitaire, 0, ',', ' ').' FCFA'
            .($this->prix_au_m2 ? ' / m²' : ' TTC');
    }

    public function soumission(): BelongsTo
    {
        return $this->belongsTo(SoumissionPromoteur::class, 'soumission_id');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(LotPropose::class, 'bien_propose_id');
    }

    public function fichiers(): HasMany
    {
        return $this->hasMany(FichierPropose::class, 'bien_propose_id');
    }
}
