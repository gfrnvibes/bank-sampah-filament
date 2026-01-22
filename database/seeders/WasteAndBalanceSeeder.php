<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteDepositItem;
use App\Models\BalanceWithdrawal;
use App\Models\TransactionHistory;
use App\Models\WasteType;

class WasteAndBalanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil waste type dari DB (id => price_per_kg)
        $wasteTypes = WasteType::all()->pluck('price_per_kg', 'id');

        // Ambil user id 2–11
        $users = User::whereBetween('id', [2, 11])->get();

        foreach ($users as $user) {

            $balance = $user->balance ?? 0;

            /* ============================================================
            | 1. GENERATE WASTE DEPOSITS
            ============================================================*/
            $depositCount = rand(1, 5);

            for ($i = 0; $i < $depositCount; $i++) {

                $itemCount = rand(1, 3);
                $wasteItems = [];
                $totalWeight = 0;

                foreach (range(1, $itemCount) as $x) {
                    $wasteTypeId = $wasteTypes->keys()->random();
                    $weight = rand(1, 10); // kg

                    $wasteItems[] = [
                        'weight' => $weight,
                        'waste_type_id' => $wasteTypeId,
                    ];

                    $totalWeight += $weight;
                }

                // Total pendapatan deposit: random 10k–25k
                $totalAmount = rand(10000, 25000);

                // Simpan data deposit
                $deposit = WasteDeposit::create([
                    'user_id' => $user->id,
                    'total_weight' => $totalWeight,
                    'total_amount' => $totalAmount,
                    'notes' => 'Auto seeded deposit',
                    'receipt' => null,
                ]);

                // Simpan waste deposit items
                foreach ($wasteItems as $item) {
                    $pricePerKg = $wasteTypes[$item['waste_type_id']];
                    WasteDepositItem::create([
                        'waste_deposit_id' => $deposit->id,
                        'waste_type_id' => $item['waste_type_id'],
                        'weight_kg' => $item['weight'],
                        'price_per_kg' => $pricePerKg,
                        'subtotal' => $item['weight'] * $pricePerKg,
                    ]);
                }

                // Update balance user
                $balanceBefore = $balance;
                $balance += $totalAmount;
                $user->update(['balance' => $balance]);

                // Simpan ke transaction history
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'type' => TransactionHistory::TYPE_DEPOSIT,
                    'amount' => $totalAmount,
                    'description' => 'Waste deposit',
                    'reference_id' => $deposit->id,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balance,
                ]);
            }


            /* ============================================================
            | 2. GENERATE WITHDRAWALS
            ============================================================*/
            $withdrawCount = rand(1, 3);

            for ($j = 0; $j < $withdrawCount; $j++) {

                // Jumlah penarikan random 10k–25k
                $amount = rand(10000, 25000);

                if ($balance < $amount) {
                    // Skip kalau saldo kurang
                    continue;
                }

                $status = collect(['pending', 'accepted', 'rejected', 'completed'])->random();
                // Simpan data penarikan
                $withdrawal = BalanceWithdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'status' => $status,
                    'receipt' => "Auto seeded withdrawal",
                ]);

                // Update balance user
                $balanceBefore = $balance;
                $balance -= $amount;
                $user->update(['balance' => $balance]);

                // Simpan ke riwayat transaksi
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'type' => TransactionHistory::TYPE_WITHDRAWAL,
                    'amount' => $amount,
                    'description' => 'Balance withdrawal',
                    'reference_id' => $withdrawal->id,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balance,
                ]);
            }
        }
    }
}
