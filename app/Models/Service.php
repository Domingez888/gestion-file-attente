<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom', 'secteur', 'adresse', 'employe_id', 'prix','etablissement_id'])]
class Service extends Model
{
    public function employe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
    public function etablissement(): BelongsTo
{
    return $this->belongsTo(Etablissement::class, 'etablissement_id');
}

public function employes(): HasMany
{
    return $this->hasMany(User::class, 'service_id');
}
}