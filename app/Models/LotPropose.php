<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Numéro de lot rattaché à un bien proposé.
 *
 * Le fichier source note « 13 ;14 » dans une seule cellule sans jamais remplir
 * la colonne Îlot. On sépare les deux : un numéro par ligne, l'îlot facultatif.
 */
class LotPropose extends Model
{
    protected $table = 'lots_proposes';

    protected $fillable = ['bien_propose_id', 'numero', 'ilot'];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(BienPropose::class, 'bien_propose_id');
    }
}
