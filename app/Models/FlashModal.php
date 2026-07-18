<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class FlashModal extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'start_date',
        'end_date',
        'delay_seconds',
        'active_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date'    => 'datetime',
        'end_date'      => 'datetime',
        'active_status' => 'boolean',
        'delay_seconds' => 'integer',
    ];

    /**
     * Scope to get the currently active flash modal.
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = now();
        return $query->where('active_status', true)
                     ->where('start_date', '<=', $now)
                     ->where('end_date', '>=', $now);
    }

    /**
     * Get computed status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->active_status) {
            return 'disabled';
        }
        $now = now();
        if ($this->start_date && $this->start_date->gt($now)) {
            return 'upcoming';
        }
        if ($this->end_date && $this->end_date->lt($now)) {
            return 'expired';
        }
        return 'active';
    }
}
