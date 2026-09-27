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
    // Les champs d'abonnement existent déjà
    // dans la table users.
}

public function down(): void
{
    // Aucune modification à annuler.
}
};
