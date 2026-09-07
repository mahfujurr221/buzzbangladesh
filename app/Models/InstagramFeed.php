<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramFeed extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'link', 'status'];

    /**
     * Get the public URL for the feed image.
     */
    public function getImageUrlAttribute(): string
    {
        return storage_asset($this->image);
    }
}
