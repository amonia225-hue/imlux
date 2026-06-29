<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('reference'); // ex: A-01, B-12
            $table->string('type_logement'); // studio, F2, F3, F4, villa
            $table->decimal('price', 14, 2);
            $table->decimal('surface', 8, 2)->nullable(); // m²
            $table->string('status', 30)->default('disponible'); // disponible, reserve, vendu
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['programme_id', 'reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
