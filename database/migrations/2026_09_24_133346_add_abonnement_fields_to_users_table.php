<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'abonnement_actif')) {
            $table->boolean('abonnement_actif')->default(false);
        }

        if (!Schema::hasColumn('users', 'abonnement_debut')) {
            $table->date('abonnement_debut')->nullable();
        }

        if (!Schema::hasColumn('users', 'abonnement_fin')) {
            $table->date('abonnement_fin')->nullable();
        }
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    // Ne pas supprimer les colonnes préexistantes.
}
};
