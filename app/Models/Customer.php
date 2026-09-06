<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'area_id',
        'phone',
        'email',
        'city',
        'thana',
        'full_address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
