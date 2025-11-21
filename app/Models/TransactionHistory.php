<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'amount', 'description', 'reference_id', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function balanceWithdrawal()
    {
        return $this->belongsTo(\App\Models\BalanceWithdrawal::class, 'reference_id');
    }

    public function wasteDeposit()
    {
        return $this->belongsTo(\App\Models\WasteDeposit::class, 'reference_id');
    }
}
