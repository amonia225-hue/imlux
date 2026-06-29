<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const CACHE_KEY = 'app_settings';

    /** Valeurs par défaut (en-têtes factures / attestations). */
    public const DEFAULTS = [
        'company_name' => 'Lorny Conseils Management',
        'company_tagline' => "Bureau d'études",
        'company_logo' => null,            // chemin sur le disque public ; sinon logo livré
        'company_address' => '',
        'company_phone' => '',
        'company_email' => '',
        'company_rccm' => '',              // Registre du Commerce
        'company_ncc' => '',               // Numéro Compte Contribuable
        'company_website' => '',
        'company_footer' => '',            // note de bas de page des documents
    ];

    /** Toutes les valeurs sous forme de tableau (mises en cache). */
    public static function bag(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => self::pluck('value', 'key')->toArray());
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = self::bag()[$key] ?? null;
        if ($value === null || $value === '') {
            return $default ?? (self::DEFAULTS[$key] ?? null);
        }
        return $value;
    }

    public static function put(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /** Incrémente un compteur (ex. téléchargements de l'app) et renvoie la nouvelle valeur. */
    public static function bump(string $key, int $by = 1): int
    {
        $row = self::firstOrCreate(['key' => $key], ['value' => '0']);
        $new = (int) $row->value + $by;
        $row->update(['value' => (string) $new]);
        Cache::forget(self::CACHE_KEY);

        return $new;
    }

    /**
     * Chemin du logo pour DomPDF (fichier local), avec repli sur le logo livré.
     */
    public static function logoPath(): string
    {
        $logo = self::bag()['company_logo'] ?? null;
        if ($logo && file_exists(public_path('storage/' . $logo))) {
            return public_path('storage/' . $logo);
        }
        return public_path('image/lorny.png');
    }
}
