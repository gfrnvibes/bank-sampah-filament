<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_kg',
        'description',
        'image'
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
    ];
}
