<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteSale extends Model
{
    protected $fillable = [
        'user_id',
        'waste_items',
        'total_weight',
        'total_income',
        'buyer',
    ];

    protected $casts = [
        'waste_items' => 'json',
        'total_weight' => 'float',
        'total_income' => 'float',
    ];
}
