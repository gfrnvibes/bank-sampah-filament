<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat jenis sampah Besi = 3000, Kardus = 2000, Kaleng = 1000, dari Waste Type
        \App\Models\WasteType::create([
            'name' => 'Besi',
            'price_per_kg' => 3000,
        ]);

        \App\Models\WasteType::create([
            'name' => 'Kardus',
            'price_per_kg' => 2000,
        ]);

        \App\Models\WasteType::create([
            'name' => 'Kaleng',
            'price_per_kg' => 1000,
        ]);
    }
}
