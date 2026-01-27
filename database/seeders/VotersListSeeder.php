<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VotersList;

class VotersListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VotersList::factory()->count(100)->create();
    }
}
