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
    Schema::create('inscriptions_en_attente', function (Blueprint $table) {
        $table->id();

        // Informations de l'établissement
        $table->string('nom_etablissement');
        $table->string('adresse');
        $table->string('telephone_etablissement')->nullable();
        $table->string('email_etablissement')->nullable();

        // Informations du futur administrateur
        $table->string('nom_administrateur');
        $table->string('email_administrateur');
        $table->string('telephone_administrateur')->nullable();
        $table->string('mot_de_passe_hash');

        // Informations du paiement
        $table->decimal('montant', 12, 2);
        $table->string('devise', 3)->default('XAF');
        $table->string('reference_paiement')->unique();
        $table->string('charge_id')->nullable()->unique();
        $table->string('reseau')->nullable();
        $table->string('telephone_paiement')->nullable();

        // Suivi de l'inscription
        $table->string('statut')->default('en_attente');
        $table->timestamp('expire_le');
        $table->timestamp('paye_le')->nullable();

        $table->timestamps();

        $table->index('email_administrateur');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions_en_attente');
    }
};
