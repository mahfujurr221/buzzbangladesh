<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryConsignment extends Model
{
    protected $fillable = [
        'order_id',
        'provider',
        'provider_status',
        'consignment_id',
        'tracking_code',
        'delivery_type',
        'item_type',
        'item_weight',
        'item_quantity',
        'item_description',
        'special_instruction',
        'is_cod',
        'amount_to_collect',
        'provider_city_id',
        'provider_zone_id',
        'provider_area_id',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'api_response',
        'webhook_payload',
        'dispatched_at',
        'picked_at',
        'delivered_at',
    ];

    protected $casts = [
        'is_cod' => 'boolean',
        'amount_to_collect' => 'decimal:2',
        'item_weight' => 'decimal:2',
        'api_response' => 'array',
        'webhook_payload' => 'array',
        'dispatched_at' => 'datetime',
        'picked_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
