<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Districts;
use App\Models\MasterList;
use App\Models\VotersList;
use App\Models\OnlineVotersReceipts;

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

        $this->call(DistrictsSeeder::class);
        $this->call(MasterListSeeder::class);
        $this->call(VotersListSeeder::class);
        $this->call(OnlineVotersReceiptsSeeder::class);
    }
}
