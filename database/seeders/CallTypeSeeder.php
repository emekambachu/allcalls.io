<?php

namespace Database\Seeders;

use App\Models\CallType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CallTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Auto Insurance',
            'Final Expense',
            'U65 Health',
            'ACA',
            'Medicare/Medicaid',
        ];

        foreach ($types as $type) {
            CallType::create(['type' => $type]);
        }
    }
}
