<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Invitation nominative envoyée à un promoteur pour qu'il dépose ses biens.
 *
 * Le jeton porte l'identité du déposant : un lien public anonyme ne permettrait
 * pas de savoir qui a envoyé quoi, ni de couper l'accès à un promoteur en
 * particulier.
 */
class InvitationPromoteur extends Model
{
    use Auditable;

    protected $table = 'invitations_promoteur';

    protected $fillable = [
        'token', 'promoteur', 'contact', 'email', 'telephone',
        'note', 'expire_le', 'revoquee_le', 'cree_par_id',
    ];

    protected function casts(): array
    {
        return [
            'expire_le' => 'datetime',
            'revoquee_le' => 'datetime',
        ];
    }

    /**
     * 48 caractères aléatoires : le lien circule par WhatsApp et par e-mail,
     * il doit être indevinable même en connaissant le nom du promoteur.
     */
    public static function nouveauToken(): string
    {
        return Str::random(48);
    }

    public function estUtilisable(): bool
    {
        if ($this->revoquee_le !== null) {
            return false;
        }

        return $this->expire_le === null || $this->expire_le->isFuture();
    }

    public function motifIndisponibilite(): ?string
    {
        if ($this->revoquee_le !== null) {
            return 'Ce lien a été révoqué par le bureau d\'études.';
        }

        if ($this->expire_le !== null && $this->expire_le->isPast()) {
            return 'Ce lien a expiré le '.$this->expire_le->format('d/m/Y').'.';
        }

        return null;
    }

    public function soumissions(): HasMany
    {
        return $this->hasMany(SoumissionPromoteur::class, 'invitation_id')->latest();
    }

    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par_id');
    }

    public function auditLabel(): string
    {
        return 'Invitation promoteur '.$this->promoteur;
    }
}
