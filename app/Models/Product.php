<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'brand_id',
        'season_id',
        'name',
        'slug',
        'short_description',
        'description',
        'purchase_price',
        'sale_price',
        'seo_title',
        'seo_description',
        'seo_tags',
        'active_status',
        'is_new_arrival',
        'is_featured',
        'is_best_seller',
        'is_on_sale',
        'is_trending',
        'entry_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active_status'  => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_featured'    => 'boolean',
        'is_best_seller' => 'boolean',
        'is_on_sale'     => 'boolean',
        'is_trending'    => 'boolean',
        'purchase_price' => 'decimal:2',
        'sale_price'     => 'decimal:2',
        'entry_date'     => 'date',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function season(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function variations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function discounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Discount::class);
    }
}
