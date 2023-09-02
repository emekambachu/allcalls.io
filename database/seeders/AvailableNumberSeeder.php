<?php

namespace Database\Seeders;

use App\Models\AvailableNumber;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AvailableNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phones = [
            '3256670373',
            '2178292289',
            '4327555268',
            '7542270877',

            // new numbers:
            '8644777761',
            '6612634970',
            '5054314193',
            '6364863245',
            '4159443015',
            '6502906860',
        ];

        foreach ($phones as $phone) {
            AvailableNumber::firstOrCreate(['phone' => $phone]);
        }
    }
}
