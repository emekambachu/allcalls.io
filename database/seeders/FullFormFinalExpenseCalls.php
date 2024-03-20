<?php

namespace Database\Seeders;

use App\Models\CallType;
use App\Models\CallTypeNumber;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FullFormFinalExpenseCalls extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $callType = CallType::create([
            'type' => 'Full Form Final Expense Calls',
        ]);

        $stagingNumber = '7868013930';
        $productionNumber = '8317408019';

        if (app()->environment('staging')) {
            CallTypeNumber::create([
                'phone' => $stagingNumber,
                'call_type_id' => $callType->id,
            ]);
        } else if (app()->environment('production')) {
            CallTypeNumber::create([
                'phone' => $productionNumber,
                'call_type_id' => $callType->id,
            ]);
        }
    }
}
