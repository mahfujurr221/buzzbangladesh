<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'area_id',
        'order_status_id',
        'total_amount',
        'shipping_cost',
        'total_purchase_cost',
        'net_profit',
        'city',
        'thana',
        'shipping_address',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_purchase_cost' => 'decimal:2',
        'net_profit' => 'decimal:2',
    ];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function consignment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DeliveryConsignment::class, 'order_id');
    }

    /**
     * Generate an 8-digit unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $lastOrder = self::orderBy('id', 'desc')->first();
        if ($lastOrder && is_numeric($lastOrder->order_number) && strlen($lastOrder->order_number) === 8) {
            $nextNumber = strval(intval($lastOrder->order_number) + 1);
        } else {
            $nextNumber = strval(10000000 + ($lastOrder ? $lastOrder->id + 1 : 1));
        }

        while (self::where('order_number', $nextNumber)->exists()) {
            $nextNumber = strval(intval($nextNumber) + 1);
        }

        return $nextNumber;
    }
}

