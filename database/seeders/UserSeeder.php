<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'name' => 'Gufron',
            'email' => 'gufron@gmail.com',
            'nik' => '3205201403010002',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }
}
