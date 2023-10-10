<?php

namespace Database\Seeders;

use App\Models\CallTypeNumber;
use App\Models\AvailableNumber;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StagingNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['phone' => '6787232049', 'call_type_id' => 1], // Auto Insurance
            ['phone' => '2065576246', 'call_type_id' => 2], // Final Expense
            ['phone' => '8156765371', 'call_type_id' => 3], // U65 Health
            ['phone' => '4094032898', 'call_type_id' => 4], // ACA
            ['phone' => '4248357649', 'call_type_id' => 5], // Medicare/Medicaid
        ];

        foreach ($data as $record) {
            CallTypeNumber::create($record);
        }



        $availableNumbers = [
            '2315257345',
            '8183815880',
            '6788536559',
            '4057572165',
            '2052893545',
            '5034064286',
            '4435192551',
            '6783480938',
            '6785639789',
            '4248886053',
        ];

        foreach ($availableNumbers as $phone) {
            AvailableNumber::firstOrCreate(['phone' => $phone]);
        }
    }
}
