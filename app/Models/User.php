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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'role',
        'status_akun',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'data_biometrik',
        'file_ktp_path',
        'file_kk_path',
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