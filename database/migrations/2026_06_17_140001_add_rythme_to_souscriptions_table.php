<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            // mensuel, trimestriel, semestriel, annuel
            $table->string('rythme', 20)->default('mensuel')->after('nb_mensualites');
        });
    }

    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->dropColumn('rythme');
        });
    }
};
