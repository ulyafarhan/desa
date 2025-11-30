<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PejabatDesa extends Model
{
    protected $table = 'pejabat_desa';

    protected $guarded = ['id'];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function suratRequests(): HasMany
    {
        return $this->hasMany(SuratRequest::class, 'pejabat_id');
    }
}