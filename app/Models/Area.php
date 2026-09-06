<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'delivery_charge',
        'default',
        'status',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'area_id');
    }
    
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'area_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($area) {
            if ($area->orders()->exists() || $area->customers()->exists()) {
                return false;
            }
        });
    }
}
