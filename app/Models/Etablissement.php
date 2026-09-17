<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Etablissement extends Model
{
    protected $fillable = [
    'nom',
    'adresse',
    'telephone',
    'email',
];

public function employes(): HasMany
{
    return $this->hasMany(User::class, 'etablissement_id');
}
public function services(): HasMany
{
    return $this->hasMany(Service::class, 'etablissement_id');
}
}
