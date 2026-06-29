<?php

namespace Database\Seeders;

use App\Models\Bien;
use Illuminate\Database\Seeder;

/**
 * Publie sur le site public quelques biens de départ (repris du dev).
 * Idempotent : ne recrée pas un bien déjà présent (clé = nom).
 * Photo laissée nulle → le site affiche le visuel par défaut ; remplacer
 * ensuite par les vraies photos depuis le dashboard (« Biens (site) »).
 *
 *   php artisan db:seed --class=BienSeeder --force
 */
class BienSeeder extends Seeder
{
    public function run(): void
    {
        $biens = [
            [
                'name' => 'Villa Basse QUARTZ',
                'type' => '4 pièces · Fondation R+1',
                'surface' => 200,
                'price' => 52000000,
                'apport_pct' => 35,
                'cloture_incluse' => false,
                'cloture_prix' => 5000000,
                'status' => 'disponible',
                'ordre' => 1,
            ],
            [
                'name' => 'Duplex AMÉTHYSTE',
                'type' => 'Duplex · 4 pièces',
                'surface' => 200,
                'price' => 80000000,
                'apport_pct' => 35,
                'cloture_incluse' => true,
                'cloture_prix' => 5000000,
                'status' => 'disponible',
                'ordre' => 2,
            ],
        ];

        foreach ($biens as $b) {
            Bien::firstOrCreate(['name' => $b['name']], $b);
        }
    }
}
