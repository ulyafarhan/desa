<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panduan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'langkah_langkah' => 'array',
        'is_active' => 'boolean',
    ];
}