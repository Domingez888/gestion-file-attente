<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Paiement;

class Abonnement extends Model

{
    const MONTANT_ANNUEL = 100000; // ton forfait fixe

    protected $table = 'abonnements';



    protected $fillable = [
        'etablissement_id',
        'montant',
        'devise',
        'reference_paiement',
        'statut',
        'date_debut',
        'date_fin',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date_debut' => 'date',
            'date_fin' => 'date',
        ];
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }
        public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}