<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Collecte de biens auprès des promoteurs.
 *
 * Reprend le formulaire Excel « Formulaire VLOG / ASA » du client, en le
 * normalisant : le fichier est un formulaire papier à en-tête répété, où un
 * site porte plusieurs biens, un bien plusieurs documents, plusieurs modes
 * d'acquisition et plusieurs lots. Une table à plat ne pouvait pas le
 * représenter.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Invitation nominative : le lien transmis au promoteur porte un jeton
        // unique. Un lien public anonyme serait invérifiable — on ne saurait
        // jamais qui a déposé quoi, et il serait ouvert au dépôt sauvage.
        Schema::create('invitations_promoteur', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('promoteur', 160);
            $table->string('contact', 120)->nullable();
            $table->string('email', 160)->nullable();
            $table->string('telephone', 40)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('expire_le')->nullable();
            $table->timestamp('revoquee_le')->nullable();
            $table->foreignId('cree_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('expire_le');
        });

        Schema::create('soumissions_promoteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained('invitations_promoteur')->cascadeOnDelete();

            // Recopiés depuis l'invitation puis modifiables par le promoteur :
            // c'est lui qui connaît ses coordonnées à jour.
            $table->string('promoteur', 160);
            $table->string('contact', 120)->nullable();
            $table->string('email', 160)->nullable();
            $table->string('telephone', 40)->nullable();

            // brouillon : le promoteur peut revenir plus tard.
            $table->string('statut', 20)->default('brouillon'); // brouillon|soumise|validee|rejetee
            $table->timestamp('soumise_le')->nullable();
            $table->text('note_admin')->nullable();
            $table->foreignId('traitee_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('traitee_le')->nullable();
            $table->timestamps();

            $table->index('statut');
        });

        Schema::create('biens_proposes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soumission_id')->constrained('soumissions_promoteur')->cascadeOnDelete();

            $table->string('site', 160);
            $table->string('type_bien', 40);   // villa_basse|duplex|appartement|terrain|autre
            $table->string('libelle', 255);

            // Le fichier source mêlait dans les mêmes colonnes une valeur
            // unique et une fourchette (Bingerville : 400 et 600). On rend la
            // fourchette explicite plutôt que de reconduire l'ambiguïté.
            $table->unsignedInteger('superficie_min')->nullable();
            $table->unsignedInteger('superficie_max')->nullable();
            $table->unsignedInteger('superficie_utile')->nullable();
            $table->unsignedSmallInteger('nb_pieces')->nullable();

            $table->unsignedInteger('disponibilite')->nullable();

            // Multi-choix : crédit bancaire, tempérament, cash.
            $table->json('modes_acquisition')->nullable();

            // Montants en entiers de francs CFA : un flottant introduirait des
            // erreurs d'arrondi sur des prix immobiliers.
            $table->unsignedBigInteger('prix_unitaire')->nullable();
            // Le fichier distingue « prix unitaire TTC » et « prix unitaire/m²
            // TTC » selon qu'il s'agit d'une maison ou d'un lot. On stocke
            // l'unité plutôt que de la deviner.
            $table->boolean('prix_au_m2')->default(false);

            $table->string('delai_reglement', 160)->nullable();

            // Checklist du fichier : agrément promoteur, agrément programme,
            // plan de masse, ACD morcelé, agrément aménageur foncier.
            $table->json('documents')->nullable();

            $table->text('commentaire')->nullable();
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->timestamps();
        });

        Schema::create('lots_proposes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bien_propose_id')->constrained('biens_proposes')->cascadeOnDelete();
            $table->string('numero', 60);
            $table->string('ilot', 60)->nullable();
            $table->timestamps();
        });

        // Photos et pièces jointes. Stockage PRIVÉ : un plan de masse ou un ACD
        // n'a pas à être accessible par URL devinable.
        Schema::create('fichiers_proposes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soumission_id')->constrained('soumissions_promoteur')->cascadeOnDelete();
            $table->foreignId('bien_propose_id')->nullable()->constrained('biens_proposes')->cascadeOnDelete();

            $table->string('categorie', 20);     // photo|document
            $table->string('chemin', 255);
            $table->string('nom_original', 255);
            $table->string('mime', 100);
            $table->unsignedInteger('taille');
            $table->timestamps();

            $table->index(['soumission_id', 'categorie']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichiers_proposes');
        Schema::dropIfExists('lots_proposes');
        Schema::dropIfExists('biens_proposes');
        Schema::dropIfExists('soumissions_promoteur');
        Schema::dropIfExists('invitations_promoteur');
    }
};
