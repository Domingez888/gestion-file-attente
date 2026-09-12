<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    public function utilisateurs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_user');
    }
}