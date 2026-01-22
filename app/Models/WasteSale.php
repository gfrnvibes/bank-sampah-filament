<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteSale extends Model
{
    protected $fillable = [
        'waste_items',
        'total_weight',
        'total_income',
        'buyer',
        'notes',
        'receipt',
    ];

    protected $casts = [
        'total_weight' => 'float',
        'total_income' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(WasteSaleItem::class);
    }
}
