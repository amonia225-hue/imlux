<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Souscripteur extends Authenticatable
{
    use Auditable, HasApiTokens, Notifiable;

    public function auditLabel(): string
    {
        return trim($this->first_name . ' ' . $this->last_name) . ' (' . $this->uid . ')';
    }

    public const FRAIS_OUVERTURE_DEFAUT = 500000;

    protected $fillable = [
        'uid', 'first_name', 'last_name', 'email', 'phone', 'date_naissance',
        'id_type', 'id_number', 'address', 'photo',
        'password', 'app_access', 'last_login_at', 'statut',
        'frais_ouverture', 'frais_ouverture_payes', 'frais_ouverture_date',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'app_access' => 'boolean',
            'last_login_at' => 'datetime',
            'date_naissance' => 'date',
            'frais_ouverture' => 'decimal:2',
            'frais_ouverture_payes' => 'boolean',
            'frais_ouverture_date' => 'date',
        ];
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class);
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(ClientNotification::class)->latest();
    }

    public function fullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public static function generateUid(): string
    {
        do {
            $uid = 'IMM-' . now()->year . '-' . strtoupper(Str::random(4));
        } while (self::where('uid', $uid)->exists());

        return $uid;
    }
}
