<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chantier_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chantier_etape_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('legende')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_photos');
    }
};
