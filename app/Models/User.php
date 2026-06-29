<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'is_super_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Protection du compte super administrateur (propriétaire) :
        // il ne peut être ni supprimé, ni rétrogradé / désactivé.
        static::deleting(function (User $user) {
            if ($user->is_super_admin) {
                throw new \RuntimeException('Compte super administrateur protégé : suppression interdite.');
            }
        });

        static::updating(function (User $user) {
            if ($user->getOriginal('is_super_admin')) {
                if (! $user->is_super_admin || ! $user->is_admin) {
                    throw new \RuntimeException('Compte super administrateur protégé : ses droits ne peuvent pas être retirés.');
                }
            }
        });
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }
}
