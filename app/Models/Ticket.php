<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['numero', 'statut', 'heureCreation', 'heureAppel', 'heureRendezVous', 'file_id', 'client_id'])]
class Ticket extends Model
{
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}