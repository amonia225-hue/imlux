<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('souscripteurs', function (Blueprint $t) {
            $t->string('statut', 20)->default('valide')->after('app_access'); // en_attente, valide, refuse
        });
    }
    public function down(): void {
        Schema::table('souscripteurs', fn (Blueprint $t) => $t->dropColumn('statut'));
    }
};
