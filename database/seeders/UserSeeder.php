<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buatlah seeder user di sini
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'nik' => '1234567890123456',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Dian Aldera Herdiansyah',
            'email' => 'herdiansyah@gmail.com',
            'nik' => str_pad((string) random_int(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT),
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 10; $i++) {
            \App\Models\User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'nik' => str_pad((string) random_int(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);
        }
    }
}
