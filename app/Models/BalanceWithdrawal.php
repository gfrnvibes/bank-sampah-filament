<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalanceWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function transactionHistory()
    {
        return $this->morphOne(TransactionHistory::class, 'reference');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($withdrawal) {
            if ($withdrawal->status === 'completed') {
                $user = User::find($withdrawal->user_id);
                if ($user->balance < $withdrawal->amount) {
                    throw new \Exception('Saldo tidak mencukupi untuk melakukan penarikan');
                }
            }
        });

        static::created(function ($withdrawal) {
            if ($withdrawal->status === 'completed') {
                $user = User::find($withdrawal->user_id);
                $balanceBefore = $user->balance;
                $balanceAfter = $balanceBefore - $withdrawal->amount;

                $transaction = TransactionHistory::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => TransactionHistory::TYPE_WITHDRAWAL,
                    'amount' => $withdrawal->amount,
                    'description' => 'Penarikan saldo',
                    'reference_id' => $withdrawal->id,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter
                ]);

                $user->balance = $balanceAfter;
                $user->save();
            }
        });

        static::updated(function ($withdrawal) {
            if ($withdrawal->isDirty('status') && $withdrawal->status === 'completed') {
                $user = User::find($withdrawal->user_id);
                if ($user->balance < $withdrawal->amount) {
                    throw new \Exception('Saldo tidak mencukupi untuk melakukan penarikan');
                }

                $balanceBefore = $user->balance;
                $balanceAfter = $balanceBefore - $withdrawal->amount;

                if (!$withdrawal->transactionHistory) {
                    TransactionHistory::create([
                        'user_id' => $withdrawal->user_id,
                        'type' => TransactionHistory::TYPE_WITHDRAWAL,
                        'amount' => $withdrawal->amount,
                        'description' => 'Penarikan saldo',
                        'reference_id' => $withdrawal->id,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter
                    ]);
                } else {
                    $withdrawal->transactionHistory->update([
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter
                    ]);
                }

                $user->balance = $balanceAfter;
                $user->save();
            }
        });
    }
}