<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettingWebsite extends Model
{
    use HasFactory;

    protected $table = 'setting_website';
    protected $guarded = [];

    /**
     * Modern Type Casting
     */
    protected function casts(): array
    {
        return [
            'meta_keywords' => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
