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
        ];

        foreach ($phones as $phone) {
            AvailableNumber::create(['phone' => $phone]);
        }
    }
}
