<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\TransactionHistory;

class WasteDeposit extends Model
{
    protected $fillable = ['user_id', 'waste_items', 'total_weight', 'total_amount', 'notes'];

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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($wasteDeposit) {
            // Buat transaksi history
            $user = User::find($wasteDeposit->user_id);
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $wasteDeposit->total_amount;

            $transaction = TransactionHistory::create([
                'user_id' => $wasteDeposit->user_id,
                'type' => TransactionHistory::TYPE_DEPOSIT,
                'amount' => $wasteDeposit->total_amount,
                'description' => 'Penyetoran sampah',
                'reference_id' => $wasteDeposit->id,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter
            ]);

            // Update balance user
            $user->balance = $balanceAfter;
            $user->save();
        });
    }
}