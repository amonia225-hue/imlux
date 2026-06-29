<?php

namespace Tests\Feature;

use App\Models\Ilot;
use App\Models\Programme;
use App\Models\Souscripteur;
use App\Models\Souscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeClient(bool $appAccess = true): Souscripteur
    {
        return Souscripteur::create([
            'uid' => Souscripteur::generateUid(),
            'first_name' => 'Yao', 'last_name' => 'Kouassi',
            'email' => 'client@imlux.ci',
            'password' => Hash::make('secret123'),
            'app_access' => $appAccess,
        ]);
    }

    public function test_login_avec_identifiants_valides_renvoie_un_jeton(): void
    {
        $this->makeClient();

        $this->postJson('/api/login', ['email' => 'client@imlux.ci', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonStructure(['token', 'souscripteur' => ['id', 'uid', 'full_name']]);
    }

    public function test_login_refuse_mauvais_mot_de_passe(): void
    {
        $this->makeClient();

        $this->postJson('/api/login', ['email' => 'client@imlux.ci', 'password' => 'wrong'])
            ->assertStatus(422);
    }

    public function test_login_refuse_si_acces_app_desactive(): void
    {
        $this->makeClient(appAccess: false);

        $this->postJson('/api/login', ['email' => 'client@imlux.ci', 'password' => 'secret123'])
            ->assertStatus(422);
    }

    public function test_endpoints_proteges_sans_jeton_renvoient_401(): void
    {
        $this->getJson('/api/souscriptions')->assertUnauthorized();
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_un_client_ne_peut_pas_voir_la_souscription_d_un_autre(): void
    {
        $client = $this->makeClient();
        $autre = Souscripteur::create(['uid' => Souscripteur::generateUid(), 'first_name' => 'A', 'last_name' => 'B']);

        $prog = Programme::create(['name' => 'P', 'location' => 'Abidjan', 'status' => 'actif']);
        $ilot = Ilot::create(['programme_id' => $prog->id, 'name' => 'A']);
        $lot = $ilot->lots()->create(['programme_id' => $prog->id, 'reference' => 'A-01', 'type_logement' => 'terrain', 'price' => 1_000_000, 'status' => 'disponible']);
        $souscriptionAutre = Souscription::create([
            'souscripteur_id' => $autre->id, 'programme_id' => $prog->id, 'lot_id' => $lot->id,
            'total_price' => 1_000_000, 'nb_mensualites' => 1, 'mensualite' => 1_000_000,
            'date_souscription' => '2026-01-01', 'status' => 'en_cours',
        ]);

        $token = $client->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/souscriptions/' . $souscriptionAutre->id)
            ->assertForbidden();
    }
}
