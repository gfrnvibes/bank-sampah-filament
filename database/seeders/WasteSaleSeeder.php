<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteSale;
use App\Models\WasteType;

class WasteSaleSeeder extends Seeder
{
    public function run(): void
    {
        $wasteTypes = WasteType::all()->pluck('price_per_kg', 'id');

        $users = User::whereBetween('id', [2, 11])->get();

        foreach ($users as $user) {

            // Total deposit weight user
            $totalDepositWeight = WasteDeposit::where('user_id', $user->id)->sum('total_weight');

            if ($totalDepositWeight <= 0) {
                continue;
            }

            // Maksimal berat yang boleh dijual
            $remainingWeight = $totalDepositWeight;

            // Bikin beberapa penjualan
            $saleCount = rand(1, 3);

            for ($i = 0; $i < $saleCount; $i++) {

                if ($remainingWeight <= 0)
                    break;

                // Tentukan berat yang mau dijual (maksimal sisa deposit)
                $saleWeight = rand(1, min(10, $remainingWeight));

                $itemCount = rand(1, 3);
                $wasteItems = [];
                $totalWeight = 0;

                foreach (range(1, $itemCount) as $x) {
                    $wasteTypeId = $wasteTypes->keys()->random();
                    $weight = rand(1, min(5, $remainingWeight - $totalWeight));

                    if ($weight <= 0)
                        break;

                    $wasteItems[] = [
                        'weight' => $weight,
                        'waste_type_id' => $wasteTypeId,
                    ];

                    $totalWeight += $weight;

                    if ($totalWeight >= $saleWeight)
                        break;
                }

                if ($totalWeight <= 0)
                    continue;

                // Income dari sale tetap random biar matching deposit range
                $totalIncome = rand(10000, 25000);

                WasteSale::create([
                    'waste_items' => json_encode($wasteItems),
                    'total_weight' => $totalWeight,
                    'total_income' => $totalIncome,
                    'buyer' => fake()->company(),
                ]);

                // Kurangi remaining
                $remainingWeight -= $totalWeight;
            }
        }
    }
}
