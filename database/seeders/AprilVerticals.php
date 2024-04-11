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
            ['type' => 'Final Expense - Fronter', 'phone' => '+19285506150', 'stagingPhone' => '+18474624275'],
            ['type' => 'NO BUFFER - Final Expense - Fronter', 'phone' => '+18135016768', 'stagingPhone' => '+12185228474'],
            ['type' => 'Simplified Issue Customer', 'phone' => '+14138315585', 'stagingPhone' => '+15672986384'],
            ['type' => 'Guaranteed Issue Customer', 'phone' => '+18173832431', 'stagingPhone' => '+14133845341'],
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
