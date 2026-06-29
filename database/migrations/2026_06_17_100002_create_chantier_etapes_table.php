<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chantier_etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('title');                 // ex: Fondations, Gros œuvre, Toiture, Finitions
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('progress')->default(0); // 0-100 %
            $table->string('status', 20)->default('a_venir'); // a_venir, en_cours, termine
            $table->date('date_prevue')->nullable();
            $table->date('date_realisee')->nullable();
            $table->string('photo')->nullable();     // image principale de l'étape
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_etapes');
    }
};
