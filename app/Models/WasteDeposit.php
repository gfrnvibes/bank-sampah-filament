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


    protected static function booted()
    {
        // Ensure totals are computed from waste_items before saving
        static::saving(function (WasteDeposit $deposit) {
            $items = $deposit->waste_items ?? [];
            $totalWeight = 0;
            $totalAmount = 0;

            if (is_array($items)) {
                foreach ($items as $item) {
                    $w = isset($item['weight']) ? (float) $item['weight'] : 0;
                    $a = isset($item['amount']) ? (float) $item['amount'] : 0;
                    $totalWeight += $w;
                    $totalAmount += $a;
                }
            }

            $deposit->total_weight = number_format($totalWeight, 2, '.', '');
            $deposit->total_amount = number_format($totalAmount, 2, '.', '');
        });

        // After created: add total_amount to user's balance
        static::created(function (WasteDeposit $deposit) {
            if (empty($deposit->user_id)) {
                return;
            }

            $amount = (float) $deposit->total_amount;
            if ($amount == 0) {
                return;
            }

            DB::transaction(function () use ($deposit, $amount) {
                User::where('id', $deposit->user_id)->increment('balance', $amount);
            });
        });

        // After updated: adjust user's balance by difference
        static::updated(function (WasteDeposit $deposit) {
            $originalUserId = $deposit->getOriginal('user_id');
            $newUserId = $deposit->user_id;
            $originalAmount = (float) ($deposit->getOriginal('total_amount') ?? 0);
            $newAmount = (float) ($deposit->total_amount ?? 0);

            DB::transaction(function () use ($originalUserId, $newUserId, $originalAmount, $newAmount) {
                if ($originalUserId !== $newUserId) {
                    if ($originalAmount != 0) {
                        User::where('id', $originalUserId)->decrement('balance', $originalAmount);
                    }
                    if ($newAmount != 0) {
                        User::where('id', $newUserId)->increment('balance', $newAmount);
                    }
                } else {
                    $diff = $newAmount - $originalAmount;
                    if ($diff != 0) {
                        User::where('id', $newUserId)->increment('balance', $diff);
                    }
                }
            });
        });

        // After deleted: subtract the amount from user's balance
        static::deleted(function (WasteDeposit $deposit) {
            if (empty($deposit->user_id)) {
                return;
            }

            $amount = (float) ($deposit->total_amount ?? 0);
            if ($amount == 0) {
                return;
            }

            DB::transaction(function () use ($deposit, $amount) {
                User::where('id', $deposit->user_id)->decrement('balance', $amount);
            });
        });
    }
}
