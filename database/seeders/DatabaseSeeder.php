<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@boheco1.com',
            'password' => bcrypt('password123'),
        ]);
        User::factory()->create([
            'name' => 'ecom',
            'email' => 'ecom@boheco1.com',
            'password' => bcrypt('password123'),
        ]);
    }
}
