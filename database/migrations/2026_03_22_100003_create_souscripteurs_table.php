<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('souscripteurs', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 20)->unique(); // IMM-2026-X89Z
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('id_type', 50)->nullable(); // CNI, Passeport, Attestation
            $table->string('id_number', 80)->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('souscripteurs');
    }
};
