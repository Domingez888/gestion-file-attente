<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nom', 'email', 'motDePasse', 'telephone', 'role', 'secteur', 'adresse','etablissement_id','service_id',])]
#[Hidden(['motDePasse', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'motDePasse' => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->motDePasse;
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'client_id');
    }

    public function etablissement(): BelongsTo
    {
    return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }

    public function appelVideoClient(): HasOne
    {
        return $this->hasOne(AppelVideo::class, 'client_id');
    }

    public function appelVideoEmploye(): HasOne
    {
        return $this->hasOne(AppelVideo::class, 'employe_id');
    }

    public function notifications_recues(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_user');
    }
    public function service(): BelongsTo
{
    return $this->belongsTo(Service::class, 'service_id');
}
}