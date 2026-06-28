<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = [
        'name',
        'color_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
