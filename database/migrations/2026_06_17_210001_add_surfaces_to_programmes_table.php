<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('programmes', function (Blueprint $t) {
            $t->decimal('surface_utile', 12, 2)->nullable()->after('description');
            $t->decimal('surface_totale', 12, 2)->nullable()->after('surface_utile');
        });
    }
    public function down(): void {
        Schema::table('programmes', fn (Blueprint $t) => $t->dropColumn(['surface_utile','surface_totale']));
    }
};
