<?php

namespace Database\Seeders;

use App\Models\ChantierEtape;
use App\Models\Ilot;
use App\Models\Lot;
use App\Models\Programme;
use App\Models\Souscripteur;
use App\Models\Souscription;
use App\Models\User;
use App\Models\Versement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== COMPTE ADMINISTRATEUR =====
        User::updateOrCreate(
            ['email' => 'admin@immo-gest.ci'],
            [
                'name' => 'Administrateur IM\'LUX',
                'password' => Hash::make('immogest2026'),
                'is_admin' => true,
            ]
        );

        // Jeu de démonstration (hors production, et seulement si la base est vide)
        if (! app()->environment('production') && Programme::count() === 0) {
            $this->seedDemo();
        }
    }

    private function seedDemo(): void
    {
        // ===== PROGRAMMES =====
        $palmiers = Programme::create([
            'name' => 'Résidence Les Palmiers',
            'location' => 'Abidjan, Cocody Angré',
            'description' => 'Programme résidentiel haut standing avec espaces verts et sécurité 24h/24.',
            'status' => 'actif',
        ]);

        $lagon = Programme::create([
            'name' => 'Villas du Lagon',
            'location' => 'Abidjan, Marcory Biétry',
            'description' => 'Villas duplex en bord de lagune, finitions premium.',
            'status' => 'actif',
        ]);

        // ===== ÎLOTS + LOTS =====
        $ilotA = $this->createIlot($palmiers, 'A', 6, 'F3', 28_000_000, 85);
        $ilotB = $this->createIlot($palmiers, 'B', 4, 'studio', 12_000_000, 35);
        $this->createIlot($lagon, 'V', 3, 'villa', 75_000_000, 220);

        $palmiers->update(['total_lots' => $palmiers->lots()->count()]);
        $lagon->update(['total_lots' => $lagon->lots()->count()]);

        // ===== SOUSCRIPTEURS =====
        $kouassi = Souscripteur::create([
            'uid' => Souscripteur::generateUid(),
            'first_name' => 'Yao',
            'last_name' => 'Kouassi',
            'email' => 'client@imlux.ci',
            'phone' => '+225 07 07 07 07 07',
            'date_naissance' => '1988-04-12',
            'id_type' => 'CNI',
            'id_number' => 'CI002345678',
            'address' => 'Cocody, Riviera 3, Abidjan',
            'password' => Hash::make('client2026'),
            'app_access' => true,
            'frais_ouverture' => 500000,
            'frais_ouverture_payes' => true,
            'frais_ouverture_date' => '2026-01-10',
        ]);

        $aminata = Souscripteur::create([
            'uid' => Souscripteur::generateUid(),
            'first_name' => 'Aminata',
            'last_name' => 'Diabaté',
            'email' => 'aminata.diabate@example.ci',
            'phone' => '+225 05 05 05 05 05',
            'date_naissance' => '1992-09-30',
            'id_type' => 'Passeport',
            'id_number' => 'CI19P98765',
            'address' => 'Marcory Zone 4, Abidjan',
            'frais_ouverture' => 500000,
            'frais_ouverture_payes' => false,
        ]);

        // ===== SOUSCRIPTION 1 (en cours, trimestriel) =====
        $lotA1 = $ilotA->lots()->first();
        $s1 = $this->createSouscription($kouassi, $palmiers, $lotA1, 28_000_000, 4_000_000, 8, 'trimestriel', '2026-01-15');
        $this->addVersement($s1, 3_000_000, '2026-04-15', 'virement', 'VIR-2026-0212');
        $this->addVersement($s1, 3_000_000, '2026-07-15', 'mobile_money', 'OM-784512');

        // ===== SOUSCRIPTION 2 (soldée) =====
        $lotB1 = $ilotB->lots()->first();
        $this->createSouscription($aminata, $palmiers, $lotB1, 12_000_000, 12_000_000, 1, 'mensuel', '2026-02-01');

        // ===== AVANCEMENT DES TRAVAUX (Les Palmiers) =====
        foreach ([
            ['Fondations', 'Terrassement et coulage des fondations.', 100, 'termine', '2026-01-30', 1],
            ['Gros œuvre', 'Élévation des murs et dalles des étages.', 100, 'termine', '2026-03-20', 2],
            ['Toiture', 'Charpente et couverture du bâtiment.', 60, 'en_cours', null, 3],
            ['Second œuvre', 'Plomberie, électricité, cloisons.', 15, 'en_cours', null, 4],
            ['Finitions', 'Peinture, carrelage, menuiseries.', 0, 'a_venir', null, 5],
        ] as [$title, $desc, $progress, $status, $dateReal, $ordre]) {
            ChantierEtape::create([
                'programme_id' => $palmiers->id,
                'title' => $title,
                'description' => $desc,
                'progress' => $progress,
                'status' => $status,
                'date_realisee' => $dateReal,
                'ordre' => $ordre,
            ]);
        }
    }

    private function createIlot(Programme $prog, string $name, int $nb, string $type, float $price, float $surface): Ilot
    {
        $ilot = Ilot::create(['programme_id' => $prog->id, 'name' => $name, 'ordre' => $prog->ilots()->count()]);
        for ($i = 1; $i <= $nb; $i++) {
            $ilot->lots()->create([
                'programme_id' => $prog->id,
                'reference' => $name . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'type_logement' => $type,
                'price' => $price,
                'surface' => $surface,
                'status' => 'disponible',
            ]);
        }
        return $ilot;
    }

    private function createSouscription(Souscripteur $sc, Programme $prog, Lot $lot, float $total, float $apport, int $nbEch, string $rythme, string $date): Souscription
    {
        $restant = $total - $apport;
        $souscription = Souscription::create([
            'souscripteur_id' => $sc->id,
            'programme_id' => $prog->id,
            'lot_id' => $lot->id,
            'total_price' => $total,
            'apport_initial' => $apport,
            'nb_mensualites' => $nbEch,
            'rythme' => $rythme,
            'mensualite' => $nbEch > 0 ? round($restant / $nbEch, 2) : 0,
            'date_souscription' => $date,
            'status' => 'en_cours',
        ]);

        $lot->update(['status' => 'reserve']);

        if ($apport > 0) {
            $this->addVersement($souscription, $apport, $date, 'especes', 'APPORT-' . $souscription->id, 'Apport initial');
        }

        return $souscription;
    }

    private function addVersement(Souscription $s, float $amount, string $date, string $method, ?string $ref = null, ?string $note = null): void
    {
        Versement::create([
            'souscription_id' => $s->id,
            'amount' => $amount,
            'payment_date' => $date,
            'payment_method' => $method,
            'reference' => $ref,
            'note' => $note,
        ]);

        $s = $s->fresh();
        $s->mensualite = $s->echeanceActuelle();
        if ($s->isSolde()) {
            $s->status = 'solde';
            $s->lot->update(['status' => 'vendu']);
        }
        $s->save();
    }
}
