<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('souscripteurs', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->boolean('app_access')->default(false)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('app_access');
        });
    }

    public function down(): void
    {
        Schema::table('souscripteurs', function (Blueprint $table) {
            $table->dropColumn(['password', 'app_access', 'last_login_at']);
        });
    }
};
