<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'data_biometrik' => 'array',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya Admin dan Staff yang boleh mengakses Panel Admin
        return in_array($this->role, ['admin', 'staff']);
    }

    public function suratRequests(): HasMany
    {
        return $this->hasMany(SuratRequest::class);
    }
}