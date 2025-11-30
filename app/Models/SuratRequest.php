<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'data_input' => 'array',
    ];

    public function user(): BelongsTo
    {
        // Eager Loading NFR: user() akan selalu dimuat saat request
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SuratTemplate::class, 'template_id');
    }

    public function pejabat(): BelongsTo
    {
        return $this->belongsTo(PejabatDesa::class, 'pejabat_id');
    }
}