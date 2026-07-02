<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'product_size_id',
        'product_color_id',
        'sku',
        'sale_price',
        'purchase_price',
        'stock_quantity',
        'active_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'active_status' => 'boolean',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    public function color(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
}
