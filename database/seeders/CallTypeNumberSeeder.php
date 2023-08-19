<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CallTypeNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('call_type_numbers')->insert([
            ['phone' => '3186978047', 'call_type_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['phone' => '5736523170', 'call_type_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['phone' => '3186978131', 'call_type_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['phone' => '6817716513', 'call_type_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['phone' => '4327772239', 'call_type_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
