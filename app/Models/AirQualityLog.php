<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirQualityLog extends Model
{
    protected $fillable = [
        'ppm',
        'kategori',
        'arah',
        'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'ppm' => 'decimal:2'
    ];
}