<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('souscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscripteur_id')->constrained()->cascadeOnDelete();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_price', 14, 2);
            $table->decimal('apport_initial', 14, 2)->default(0);
            $table->integer('nb_mensualites')->default(12);
            $table->decimal('mensualite', 14, 2)->default(0);
            $table->date('date_souscription');
            $table->string('status', 30)->default('en_cours'); // en_cours, solde, annule
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('souscriptions');
    }
};
