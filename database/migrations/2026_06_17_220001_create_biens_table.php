<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('biens', function (Blueprint $t) {
            $t->id();
            $t->string('name');                         // Villa Basse QUARTZ
            $t->string('type')->nullable();             // 4 pièces · Fondation R+1
            $t->decimal('surface', 10, 2)->nullable();  // m²
            $t->decimal('price', 16, 2);                // montant
            $t->unsignedTinyInteger('apport_pct')->default(35);
            $t->boolean('cloture_incluse')->default(false);
            $t->decimal('cloture_prix', 14, 2)->default(5000000);
            $t->text('description')->nullable();
            $t->string('photo')->nullable();
            $t->string('status', 20)->default('disponible'); // disponible, reserve, vendu
            $t->unsignedInteger('ordre')->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('biens'); }
};
