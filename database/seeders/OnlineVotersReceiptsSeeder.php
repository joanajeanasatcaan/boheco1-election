<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OnlineVotersReceipts;

class OnlineVotersReceiptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OnlineVotersReceipts::factory()->count(50)->create();
    }
}
