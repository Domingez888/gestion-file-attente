<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nom');
            $table->renameColumn('password', 'motDePasse');
            $table->string('telephone')->nullable()->after('motDePasse');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nom', 'name');
            $table->renameColumn('motDePasse', 'password');
            $table->dropColumn('telephone');
        });
    }
};