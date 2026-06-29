<?php

namespace Tests\Feature;

use App\Models\Ilot;
use App\Models\Lot;
use App\Models\Programme;
use App\Models\Souscripteur;
use App\Models\Souscription;
use App\Models\User;
use App\Models\Versement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialLogicTest extends TestCase
{
    use RefreshDatabase;

    private function makeSouscription(array $attrs = []): Souscription
    {
        $prog = Programme::create(['name' => 'Prog', 'location' => 'Abidjan', 'status' => 'actif']);
        $ilot = Ilot::create(['programme_id' => $prog->id, 'name' => 'A']);
        $lot = $ilot->lots()->create([
            'programme_id' => $prog->id, 'reference' => 'A-01',
            'type_logement' => 'terrain', 'price' => 5_000_000, 'status' => 'disponible',
        ]);
        $sc = Souscripteur::create([
            'uid' => Souscripteur::generateUid(),
            'first_name' => 'Test', 'last_name' => 'Client',
        ]);

        return Souscription::create(array_merge([
            'souscripteur_id' => $sc->id,
            'programme_id' => $prog->id,
            'lot_id' => $lot->id,
            'total_price' => 5_000_000,
            'apport_initial' => 0,
            'nb_mensualites' => 4,
            'rythme' => 'mensuel',
            'mensualite' => 1_250_000,
            'date_souscription' => '2026-01-01',
            'status' => 'en_cours',
        ], $attrs));
    }

    public function test_total_verse_et_reste_a_payer(): void
    {
        $s = $this->makeSouscription();
        $this->assertSame(0.0, $s->totalVerse());
        $this->assertSame(5_000_000.0, $s->resteAPayer());

        Versement::create(['souscription_id' => $s->id, 'amount' => 2_000_000, 'payment_date' => '2026-02-01', 'payment_method' => 'especes']);

        $this->assertSame(2_000_000.0, $s->fresh()->totalVerse());
        $this->assertSame(3_000_000.0, $s->fresh()->resteAPayer());
    }

    public function test_redistribution_echeance_sur_echeances_restantes(): void
    {
        $s = $this->makeSouscription(['apport_initial' => 1_000_000]);
        // apport : ne compte pas comme échéance
        Versement::create(['souscription_id' => $s->id, 'amount' => 1_000_000, 'payment_date' => '2026-01-01', 'payment_method' => 'especes', 'reference' => 'APPORT-' . $s->id]);

        $s = $s->fresh();
        $this->assertSame(0, $s->echeancesPayees());
        $this->assertSame(4, $s->echeancesRestantes());
        $this->assertSame(1_000_000.0, $s->echeanceActuelle()); // (5M-1M)/4

        // Un versement d'échéance
        Versement::create(['souscription_id' => $s->id, 'amount' => 1_000_000, 'payment_date' => '2026-02-01', 'payment_method' => 'especes']);

        $s = $s->fresh();
        $this->assertSame(1, $s->echeancesPayees());
        $this->assertSame(3, $s->echeancesRestantes());
        $this->assertSame(1_000_000.0, $s->echeanceActuelle()); // 3M/3
    }

    public function test_echeancier_genere_le_bon_nombre_de_lignes(): void
    {
        $s = $this->makeSouscription(['nb_mensualites' => 4, 'rythme' => 'trimestriel']);
        $echeancier = $s->echeancier();

        $this->assertCount(4, $echeancier);
        // intervalle trimestriel = 3 mois ; 1re échéance = date + 3 mois
        $this->assertSame('2026-04-01', $echeancier[0]['date']->toDateString());
        $this->assertSame(1_250_000.0, $echeancier[0]['montant']); // 5M/4
    }

    public function test_versement_complet_solde_la_souscription_via_route(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $s = $this->makeSouscription();

        $this->actingAs($admin)->post(route('versements.store'), [
            'souscription_id' => $s->id,
            'amount' => 5_000_000,
            'payment_date' => '2026-02-01',
            'payment_method' => 'virement',
        ])->assertRedirect();

        $s = $s->fresh();
        $this->assertSame('solde', $s->status);
        $this->assertTrue($s->isSolde());
        $this->assertSame('vendu', $s->lot->fresh()->status);
    }

    public function test_suppression_versement_repasse_en_cours_et_libere_le_lot(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $s = $this->makeSouscription();

        $this->actingAs($admin)->post(route('versements.store'), [
            'souscription_id' => $s->id, 'amount' => 5_000_000,
            'payment_date' => '2026-02-01', 'payment_method' => 'virement',
        ]);
        $versement = Versement::where('souscription_id', $s->id)->first();
        $this->assertSame('solde', $s->fresh()->status);

        $this->actingAs($admin)->post(route('versements.destroy', $versement))->assertRedirect();

        $s = $s->fresh();
        $this->assertSame('en_cours', $s->status);
        $this->assertSame('reserve', $s->lot->fresh()->status);
    }

    public function test_frais_ouverture_independants_du_prix_du_bien(): void
    {
        $s = $this->makeSouscription();
        $sc = $s->souscripteur;

        // Les frais par défaut (500 000) n'entrent pas dans le reste à payer du bien
        $this->assertSame(500_000.0, (float) $sc->frais_ouverture);
        Versement::create(['souscription_id' => $s->id, 'amount' => 5_000_000, 'payment_date' => '2026-02-01', 'payment_method' => 'especes']);
        $this->assertSame(0.0, $s->fresh()->resteAPayer()); // bien soldé indépendamment des frais
    }

    public function test_creation_versement_est_journalisee(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $s = $this->makeSouscription();

        $this->actingAs($admin)->post(route('versements.store'), [
            'souscription_id' => $s->id, 'amount' => 1_000_000,
            'payment_date' => '2026-02-01', 'payment_method' => 'especes',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'created',
            'model_type' => 'Versement',
            'user_id' => $admin->id,
        ]);
    }
}
