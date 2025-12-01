<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WasteDeposit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WasteDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user_id valid = 2 sampai 8
        $userIds = range(2, 8);

        foreach ($userIds as $userId) {

            $depositCount = rand(1, 5);

            for ($i = 0; $i < $depositCount; $i++) {

                $items = [];
                $itemCount = rand(1, 5);

                for ($j = 0; $j < $itemCount; $j++) {
                    $weight = rand(1, 10);
                    $typeId = rand(1, 5);

                    $items[] = [
                        'weight' => $weight,
                        'waste_type_id' => (string) $typeId,
                    ];
                }

                $totalWeight = collect($items)->sum('weight');
                $totalAmount = $totalWeight * 1000; // contoh rate

                // Simpan deposit
                WasteDeposit::create([
                    'user_id' => $userId,
                    'waste_items' => $items,
                    'total_weight' => $totalWeight,
                    'total_amount' => $totalAmount,
                    'notes' => fake()->sentence(),
                ]);

                // Tambahkan ke balance user
                User::where('id', $userId)->increment('balance', $totalAmount);
            }
        }
    }
}
