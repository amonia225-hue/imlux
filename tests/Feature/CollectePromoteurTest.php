<?php

namespace Tests\Feature;

use App\Models\BienPropose;
use App\Models\InvitationPromoteur;
use App\Models\SoumissionPromoteur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CollectePromoteurTest extends TestCase
{
    use RefreshDatabase;

    private function invitation(array $surcharges = []): InvitationPromoteur
    {
        return InvitationPromoteur::create(array_merge([
            'token' => InvitationPromoteur::nouveauToken(),
            'promoteur' => 'SCI Les Palmiers',
            'contact' => 'M. KOUASSI',
            'expire_le' => now()->addDays(60),
        ], $surcharges));
    }

    /**
     * @return array<string, mixed>
     */
    private function formulaire(array $surcharges = []): array
    {
        return array_merge([
            'promoteur' => 'SCI Les Palmiers',
            'telephone' => '+225 07 44 55 66 77',
            'biens' => [[
                'site' => 'Bingerville',
                'type_bien' => 'duplex',
                'libelle' => 'Villa duplex 4 pièces haut standing',
                'superficie_min' => 400,
                'superficie_max' => 600,
                'nb_pieces' => 4,
                'disponibilite' => 5,
                'modes_acquisition' => ['credit_bancaire', 'cash'],
                'prix_unitaire' => 85000000,
                'delai_reglement' => '12 mois après signature',
                'documents' => ['agrement_promoteur', 'plan_de_masse'],
                'lots' => '13 ; 14 ; 15',
                'ilot' => 'B',
            ]],
        ], $surcharges);
    }

    public function test_the_promoter_form_opens_with_a_valid_token(): void
    {
        $invitation = $this->invitation();

        $this->get("/promoteurs/depot/{$invitation->token}")
            ->assertOk()
            ->assertSee('SCI Les Palmiers');
    }

    public function test_an_unknown_token_is_a_404(): void
    {
        $this->get('/promoteurs/depot/jeton-invente')->assertNotFound();
    }

    public function test_an_expired_link_no_longer_accepts_a_deposit(): void
    {
        $invitation = $this->invitation(['expire_le' => now()->subDay()]);

        $this->get("/promoteurs/depot/{$invitation->token}")->assertOk()->assertSee('expiré');
        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire())->assertStatus(410);

        $this->assertSame(0, BienPropose::count());
    }

    public function test_a_revoked_link_no_longer_accepts_a_deposit(): void
    {
        $invitation = $this->invitation(['revoquee_le' => now()]);

        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire())->assertStatus(410);
    }

    public function test_a_draft_is_saved_without_being_transmitted(): void
    {
        $invitation = $this->invitation();

        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire())->assertRedirect();

        $soumission = SoumissionPromoteur::firstOrFail();

        $this->assertSame(SoumissionPromoteur::STATUT_BROUILLON, $soumission->statut);
        $this->assertNull($soumission->soumise_le);
        $this->assertSame(1, $soumission->biens()->count());
    }

    /**
     * Le fichier client note « 13 ;14 » dans une seule cellule : on éclate en
     * lots distincts plutôt que de conserver la chaîne brute.
     */
    public function test_the_lot_numbers_are_split_into_rows(): void
    {
        $invitation = $this->invitation();

        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire());

        $bien = BienPropose::firstOrFail();

        $this->assertSame(3, $bien->lots()->count());
        $this->assertSame(['13', '14', '15'], $bien->lots()->pluck('numero')->all());
        $this->assertSame('B', $bien->lots()->first()->ilot);
    }

    public function test_the_range_and_the_acquisition_modes_are_stored(): void
    {
        $invitation = $this->invitation();

        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire());

        $bien = BienPropose::firstOrFail();

        $this->assertSame(400, $bien->superficie_min);
        $this->assertSame(600, $bien->superficie_max);
        $this->assertSame('400 à 600 m²', $bien->superficieLabel());
        $this->assertSame(['credit_bancaire', 'cash'], $bien->modes_acquisition);
    }

    public function test_transmitting_locks_the_form(): void
    {
        $invitation = $this->invitation();

        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire(['transmettre' => 1]));

        $soumission = SoumissionPromoteur::firstOrFail();
        $this->assertSame(SoumissionPromoteur::STATUT_SOUMISE, $soumission->statut);
        $this->assertNotNull($soumission->soumise_le);

        // Le formulaire n'est plus éditable : c'est la pièce examinée.
        $this->get("/promoteurs/depot/{$invitation->token}")->assertOk()->assertSee('votre dépôt nous est parvenu');
        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire())->assertStatus(409);
    }

    public function test_transmitting_without_a_property_is_refused(): void
    {
        $invitation = $this->invitation();

        $this->post("/promoteurs/depot/{$invitation->token}", [
            'promoteur' => 'SCI Les Palmiers',
            'transmettre' => 1,
        ])->assertSessionHasErrors('biens');

        $this->assertSame(SoumissionPromoteur::STATUT_BROUILLON, SoumissionPromoteur::firstOrFail()->statut);
    }

    /**
     * Le formulaire est ouvert sur Internet : tout ce qui n'est pas une image
     * ou un PDF doit être refusé.
     */
    public function test_a_dangerous_file_is_refused(): void
    {
        Storage::fake('local');
        $invitation = $this->invitation();
        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire());

        $this->post("/promoteurs/depot/{$invitation->token}/fichiers", [
            'categorie' => 'document',
            'fichiers' => [UploadedFile::fake()->create('charge.php', 40, 'application/x-php')],
        ])->assertSessionHasErrors('fichiers.0');

        $this->assertSame(0, SoumissionPromoteur::firstOrFail()->fichiers()->count());
    }

    public function test_a_photo_is_stored_on_the_private_disk(): void
    {
        Storage::fake('local');
        $invitation = $this->invitation();
        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire());

        $this->post("/promoteurs/depot/{$invitation->token}/fichiers", [
            'categorie' => 'photo',
            'fichiers' => [UploadedFile::fake()->image('villa.jpg')],
        ])->assertRedirect();

        $fichier = SoumissionPromoteur::firstOrFail()->fichiers()->firstOrFail();

        $this->assertSame('photo', $fichier->categorie);
        Storage::disk('local')->assertExists($fichier->chemin);
    }

    /**
     * Un jeton ne donne accès qu'à ses propres fichiers.
     */
    public function test_a_token_cannot_delete_another_deposits_file(): void
    {
        Storage::fake('local');

        $a = $this->invitation();
        $this->post("/promoteurs/depot/{$a->token}", $this->formulaire());
        $this->post("/promoteurs/depot/{$a->token}/fichiers", [
            'categorie' => 'photo',
            'fichiers' => [UploadedFile::fake()->image('a.jpg')],
        ]);
        $fichierDeA = SoumissionPromoteur::firstOrFail()->fichiers()->firstOrFail();

        $b = $this->invitation(['promoteur' => 'Autre promoteur']);
        $this->post("/promoteurs/depot/{$b->token}", $this->formulaire(['promoteur' => 'Autre promoteur']));

        $this->post("/promoteurs/depot/{$b->token}/fichiers/{$fichierDeA->id}/supprimer")->assertNotFound();

        $this->assertDatabaseHas('fichiers_proposes', ['id' => $fichierDeA->id]);
    }

    public function test_the_manager_sees_and_validates_a_deposit(): void
    {
        $admin = User::factory()->create(["is_admin" => true]);
        $invitation = $this->invitation();
        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire(['transmettre' => 1]));

        $soumission = SoumissionPromoteur::firstOrFail();

        $this->actingAs($admin)->get('/admin/collecte-promoteurs')->assertOk()->assertSee('SCI Les Palmiers');
        $this->actingAs($admin)->get("/admin/collecte-promoteurs/depots/{$soumission->id}")->assertOk();

        $this->actingAs($admin)
            ->post("/admin/collecte-promoteurs/depots/{$soumission->id}/traiter", [
                'decision' => 'validee',
                'note_admin' => 'Dossier complet.',
            ])->assertRedirect();

        $this->assertSame(SoumissionPromoteur::STATUT_VALIDEE, $soumission->fresh()->statut);
        $this->assertSame($admin->id, $soumission->fresh()->traitee_par_id);
    }

    /**
     * Deux gestionnaires ouvrant le même dossier ne doivent pas pouvoir
     * écraser mutuellement leur décision.
     */
    public function test_a_deposit_cannot_be_judged_twice(): void
    {
        $admin = User::factory()->create(["is_admin" => true]);
        $invitation = $this->invitation();
        $this->post("/promoteurs/depot/{$invitation->token}", $this->formulaire(['transmettre' => 1]));
        $soumission = SoumissionPromoteur::firstOrFail();

        $this->actingAs($admin)->post("/admin/collecte-promoteurs/depots/{$soumission->id}/traiter", ['decision' => 'validee']);
        $this->actingAs($admin)
            ->post("/admin/collecte-promoteurs/depots/{$soumission->id}/traiter", ['decision' => 'rejetee'])
            ->assertSessionHasErrors('decision');

        $this->assertSame(SoumissionPromoteur::STATUT_VALIDEE, $soumission->fresh()->statut);
    }

    public function test_the_collection_screens_require_authentication(): void
    {
        $this->get('/admin/collecte-promoteurs')->assertRedirect();
    }
}
