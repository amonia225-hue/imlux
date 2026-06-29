<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_racine_affiche_le_site_public(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Lorny Conseils Management');
    }

    public function test_la_page_inscription_repond(): void
    {
        $this->get('/inscription')->assertOk()->assertSee('Créer mon compte');
    }

    public function test_une_inscription_cree_un_adherent_en_attente(): void
    {
        $this->post('/inscription', [
            'first_name' => 'Koffi', 'last_name' => 'Yao',
            'email' => 'koffi@example.ci', 'phone' => '+225 0102030405',
            'date_naissance' => '1990-01-01', 'address' => 'Abidjan',
            'password' => 'secret123', 'password_confirmation' => 'secret123',
        ])->assertRedirect();

        $this->assertDatabaseHas('souscripteurs', [
            'email' => 'koffi@example.ci', 'statut' => 'en_attente', 'app_access' => false,
        ]);
    }

    public function test_la_page_de_connexion_repond(): void
    {
        $this->get('/login')->assertOk();
    }
}
