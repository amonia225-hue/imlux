<?php

namespace Database\Seeders;

use App\Models\Bien;
use Illuminate\Database\Seeder;

/**
 * Publie sur le site public les biens de départ (repris du dev), avec photos.
 * Les images sont versionnées dans storage/app/public/biens (servies via le
 * lien storage). Idempotent : met à jour le bien existant (clé = nom).
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
                'photo' => 'biens/sNU810C2OFsyz4ESHBYkDCDnmA8mOgB708o2AvAQ.jpg',
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
                'photo' => 'biens/XdKzRBJocN6iiprGJJPozP5ubjoqjWHjc4SDEJVT.jpg',
                'status' => 'disponible',
                'ordre' => 2,
            ],
        ];

        foreach ($biens as $b) {
            Bien::updateOrCreate(['name' => $b['name']], $b);
        }
    }
}
