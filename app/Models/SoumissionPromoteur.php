<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Dépôt d'un promoteur : ses coordonnées et la liste des biens qu'il propose.
 *
 * Reste au statut « brouillon » tant qu'il n'a pas cliqué « Transmettre ».
 * Une fois soumise, elle n'est plus modifiable par lui — c'est la pièce que le
 * bureau d'études examinera.
 */
class SoumissionPromoteur extends Model
{
    use Auditable;

    protected $table = 'soumissions_promoteur';

    public const STATUT_BROUILLON = 'brouillon';

    public const STATUT_SOUMISE = 'soumise';

    public const STATUT_VALIDEE = 'validee';

    public const STATUT_REJETEE = 'rejetee';

    protected $fillable = [
        'invitation_id', 'promoteur', 'contact', 'email', 'telephone',
        'statut', 'soumise_le', 'note_admin', 'traitee_par_id', 'traitee_le',
    ];

    protected function casts(): array
    {
        return [
            'soumise_le' => 'datetime',
            'traitee_le' => 'datetime',
        ];
    }

    public function estBrouillon(): bool
    {
        return $this->statut === self::STATUT_BROUILLON;
    }

    public function estTraitee(): bool
    {
        return in_array($this->statut, [self::STATUT_VALIDEE, self::STATUT_REJETEE], true);
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            self::STATUT_BROUILLON => 'Brouillon',
            self::STATUT_SOUMISE => 'À examiner',
            self::STATUT_VALIDEE => 'Validée',
            self::STATUT_REJETEE => 'Rejetée',
            default => $this->statut,
        };
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(InvitationPromoteur::class, 'invitation_id');
    }

    public function biens(): HasMany
    {
        return $this->hasMany(BienPropose::class, 'soumission_id')->orderBy('ordre');
    }

    public function fichiers(): HasMany
    {
        return $this->hasMany(FichierPropose::class, 'soumission_id');
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traitee_par_id');
    }

    public function auditLabel(): string
    {
        return 'Dépôt de '.$this->promoteur;
    }
}
