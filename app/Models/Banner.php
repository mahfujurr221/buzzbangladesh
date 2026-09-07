<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_link',
        'status',
    ];

    /**
     * Get the public URL for the banner image.
     */
    public function getImageUrlAttribute(): string
    {
        return storage_asset($this->image);
    }
}
