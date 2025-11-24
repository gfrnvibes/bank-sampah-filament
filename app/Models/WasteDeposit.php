<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class WasteDeposit extends Model
{
    protected $fillable = ['user_id', 'waste_items', 'total_weight', 'total_amount', 'status', 'notes'];

    protected $casts = [
        'waste_items' => 'array',
        'total_weight' => 'float',
        'total_amount' => 'float',
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
