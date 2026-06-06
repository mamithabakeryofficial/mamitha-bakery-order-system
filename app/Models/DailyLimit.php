<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLimit extends Model
{
    protected $fillable = [
        'max_orders_per_day', 'max_products_per_day', 
        'opening_time', 'closing_time', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
