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
    if (!Schema::hasColumn('users', 'abonnement_expire_le')) {
        Schema::table('users', function (Blueprint $table) {
            $table->date('abonnement_expire_le')->nullable();
        });
    }
}

public function down(): void
{
    // Ne pas supprimer une colonne préexistante.
}
};
