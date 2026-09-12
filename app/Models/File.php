<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom', 'statut','service_id'])]
class File extends Model
{
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
    public function service()
{
    return $this->belongsTo(Service::class);
}
}