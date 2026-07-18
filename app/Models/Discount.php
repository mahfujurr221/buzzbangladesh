<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'level',
        'category_id',
        'product_id',
        'variation_ids',
        'discount_percentage',
        'start_date',
        'end_date',
        'active_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active_status'       => 'boolean',
        'start_date'          => 'date',
        'end_date'            => 'date',
        'discount_percentage' => 'decimal:2',
        'variation_ids'       => 'array',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Helper to get the actual ProductVariation models for this discount.
     * Since it's a JSON array of IDs, we use a simple query instead of a native relationship.
     */
    public function getVariationsAttribute()
    {
        if (empty($this->variation_ids)) {
            return collect();
        }
        return ProductVariation::whereIn('id', $this->variation_ids)->get();
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    /**
     * Only discounts that are currently active (within session window & active_status = true).
     */
    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();
        return $query->where('active_status', true)
                     ->where('start_date', '<=', $today)
                     ->where('end_date', '>=', $today);
    }

    /**
     * Discounts whose session hasn't started yet.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        $today = now()->toDateString();
        return $query->where('active_status', true)
                     ->where('start_date', '>', $today);
    }

    /**
     * Discounts whose session has ended.
     */
    public function scopeExpired(Builder $query): Builder
    {
        $today = now()->toDateString();
        return $query->where('end_date', '<', $today);
    }

    // ──────────────────────────────────────────────
    // Computed Attributes
    // ──────────────────────────────────────────────

    /**
     * Returns 'active', 'upcoming', 'expired', or 'disabled'.
     * Priority: most specific level wins (variation > product > category).
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

    /**
     * Human-readable target description (for display in lists).
     */
    public function getTargetNameAttribute(): string
    {
        return match ($this->level) {
            'category'  => optional($this->category)->name ?? '—',
            'product'   => optional($this->product)->name ?? '—',
            'variation' => count($this->variation_ids ?? []) . ' Variation(s)',
            default     => '—',
        };
    }
}
