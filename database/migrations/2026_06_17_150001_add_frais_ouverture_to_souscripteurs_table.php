<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('souscripteurs', function (Blueprint $table) {
            // Frais d'ouverture de dossier (séparés du prix du bien)
            $table->decimal('frais_ouverture', 14, 2)->default(500000)->after('app_access');
            $table->boolean('frais_ouverture_payes')->default(false)->after('frais_ouverture');
        });
    }

    public function down(): void
    {
        Schema::table('souscripteurs', function (Blueprint $table) {
            $table->dropColumn(['frais_ouverture', 'frais_ouverture_payes']);
        });
    }
};
