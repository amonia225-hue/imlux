<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscripteur_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30);            // versement, frais, travaux, echeance
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['souscripteur_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_notifications');
    }
};
