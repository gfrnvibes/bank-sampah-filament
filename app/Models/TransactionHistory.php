<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';

    protected $fillable = ['user_id', 'type', 'amount', 'description', 'reference_id'];

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

    public function getReferenceDataAttribute()
    {
        if ($this->type === self::TYPE_DEPOSIT) {
            return $this->wasteDeposit;
        } elseif ($this->type === self::TYPE_WITHDRAWAL) {
            return $this->balanceWithdrawal;
        }

        return null;
    }

}
