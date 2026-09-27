<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscriptionEnAttente extends Model
{
    protected $table = 'inscriptions_en_attente';

    protected $fillable = [
        'nom_etablissement',
        'adresse',
        'telephone_etablissement',
        'email_etablissement',
        'nom_administrateur',
        'email_administrateur',
        'telephone_administrateur',
        'mot_de_passe_hash',
        'montant',
        'devise',
        'reference_paiement',
        'charge_id',
        'reseau',
        'telephone_paiement',
        'statut',
        'expire_le',
        'paye_le',
    ];

    protected $hidden = [
        'mot_de_passe_hash',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'expire_le' => 'datetime',
            'paye_le' => 'datetime',
        ];
    }
}