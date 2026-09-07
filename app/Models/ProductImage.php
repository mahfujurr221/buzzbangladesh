<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'product_color_id',
        'image_path',
        'is_main',
        'sort_order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }

    /**
     * Get the public URL for this product image.
     */
    public function getImageUrlAttribute(): string
    {
        return storage_asset($this->image_path, 'backend/images/products/placeholder.png');
    }
}
