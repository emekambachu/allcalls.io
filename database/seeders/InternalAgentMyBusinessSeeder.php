<?php

namespace Database\Seeders;

use App\Models\InternalAgentMyBusiness;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternalAgentMyBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InternalAgentMyBusiness::factory()->count(50)->create();
    }
}
