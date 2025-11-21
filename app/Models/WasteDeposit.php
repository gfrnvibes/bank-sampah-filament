<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteDeposit extends Model
{
    protected $fillable = ['user_id', 'waste_items', 'total_weight', 'total_amount', 'status', 'notes'];

    protected $casts = [
        'waste_items' => 'array',
        'total_weight' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionHistory()
    {
        return $this->morphOne(TransactionHistory::class, 'reference');
    }
}
