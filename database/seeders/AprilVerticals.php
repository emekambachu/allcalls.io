<?php

namespace Database\Seeders;

use App\Models\CallType;
use App\Models\CallTypeNumber;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AprilVerticals extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['type' => 'Final Expense - Fronter', 'phone' => '9285506150', 'stagingPhone' => '8474624275'],
            ['type' => 'NO BUFFER - Final Expense - Fronter', 'phone' => '8135016768', 'stagingPhone' => '2185228474'],
            ['type' => 'Simplified Issue Customer', 'phone' => '4138315585', 'stagingPhone' => '5672986384'],
            ['type' => 'Guaranteed Issue Customer', 'phone' => '8173832431', 'stagingPhone' => '4133845341'],
        ];


        if (env('APP_ENV') === 'staging') {
            foreach ($types as $type) {
                $callType = CallType::create(['type' => $type['type']]);
                CallTypeNumber::create(['phone' => $type['stagingPhone'], 'call_type_id' => $callType->id]);
            }
        } else if (env('APP_ENV') === 'production') {
            foreach ($types as $type) {
                $callType = CallType::create(['type' => $type['type']]);
                CallTypeNumber::create(['phone' => $type['phone'], 'call_type_id' => $callType->id]);
            }
        }
    }
}
