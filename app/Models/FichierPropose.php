<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Photo ou pièce jointe déposée par un promoteur.
 *
 * Le chemin pointe vers le disque privé : un plan de masse ou un ACD ne doit
 * pas être servi depuis `public/`, où une URL devinable suffirait à le lire.
 */
class FichierPropose extends Model
{
    protected $table = 'fichiers_proposes';

    public const CATEGORIE_PHOTO = 'photo';

    public const CATEGORIE_DOCUMENT = 'document';

    protected $fillable = [
        'soumission_id', 'bien_propose_id', 'categorie',
        'chemin', 'nom_original', 'mime', 'taille',
    ];

    protected function casts(): array
    {
        return ['taille' => 'integer'];
    }

    public function estImage(): bool
    {
        return str_starts_with($this->mime, 'image/');
    }

    public function tailleLisible(): string
    {
        return $this->taille >= 1048576
            ? round($this->taille / 1048576, 1).' Mo'
            : round($this->taille / 1024).' Ko';
    }

    public function soumission(): BelongsTo
    {
        return $this->belongsTo(SoumissionPromoteur::class, 'soumission_id');
    }
}
