<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscription_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->date('payment_date');
            $table->string('payment_method', 30); // especes, cheque, virement, mobile_money
            $table->string('reference', 120)->nullable();
            $table->text('note')->nullable();
            $table->string('invoice_path')->nullable(); // chemin PDF facture
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
