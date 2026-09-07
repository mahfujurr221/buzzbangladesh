<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getLogoUrlAttribute(): string
    {
        return storage_asset($this->logo, 'backend/images/logo.png');
    }

    public function getFaviconUrlAttribute(): string
    {
        return storage_asset($this->favicon, 'backend/images/favicon.png');
    }
}
