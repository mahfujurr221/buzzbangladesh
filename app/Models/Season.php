<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Season extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'active_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active_status' => 'boolean',
        'start_date'    => 'date',
        'end_date'      => 'date',
    ];

    /**
     * Auto-generate slug from name before saving.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($season) {
            if (empty($season->slug)) {
                $season->slug = Str::slug($season->name);
            }
        });
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Computed status: active, upcoming, or expired.
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->active_status) {
            return 'disabled';
        }
        $today = now()->startOfDay();
        if ($this->start_date && $this->start_date->gt($today)) {
            return 'upcoming';
        }
        if ($this->end_date && $this->end_date->lt($today)) {
            return 'expired';
        }
        return 'active';
    }
}
