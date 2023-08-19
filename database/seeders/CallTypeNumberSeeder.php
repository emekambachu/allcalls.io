<?php

namespace Database\Seeders;

use App\Models\CallTypeNumber;
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
        $data = [
            ['phone' => '3186978047', 'call_type_id' => 1],
            ['phone' => '5736523170', 'call_type_id' => 2],
            ['phone' => '3186978131', 'call_type_id' => 3],
            ['phone' => '6817716513', 'call_type_id' => 4],
            ['phone' => '4327772239', 'call_type_id' => 5],
        ];

        foreach ($data as $record) {
            CallTypeNumber::create($record);
        }
    }
}
