<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WasteType::create([
            'name' => 'Plastik',
            'price_per_kg' => 3500,
            'description' => 'Botol, gelas plastik, HDPE, PP.'
        ]);

        WasteType::create([
            'name' => 'Kertas',
            'price_per_kg' => 7000,
            'description' => 'Koran, kardus, buku, dan berbagai jenis kertas kering.'
        ]);

        WasteType::create([
            'name' => 'Logam',
            'price_per_kg' => 1000,
            'description' => 'Kaleng, besi, aluminium, dan logam layak daur ulang lainnya.'
        ]);

        WasteType::create([
            'name' => 'Kaca',
            'price_per_kg' => 1500,
            'description' => 'Botol kaca, pecahan kaca.'
        ]);

        WasteType::create([
            'name' => 'Elektronik',
            'price_per_kg' => 12000,
            'description' => 'HP rusak, kabel, motherboar, dan lain-lain.'
        ]);
    }
}
