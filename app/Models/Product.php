<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'short_description',
        'description',
        'base_price',
        'discount_price',
        'purchase_cost',
        'seo_title',
        'seo_description',
        'seo_tags',
        'active_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active_status' => 'boolean',
        'base_price' => 'decimal:2',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }
}
