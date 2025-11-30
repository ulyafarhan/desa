<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratTemplate extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'form_schema' => 'array',
        'is_active' => 'boolean',
    ];

    public function suratRequests(): HasMany
    {
        return $this->hasMany(SuratRequest::class, 'template_id');
    }
}