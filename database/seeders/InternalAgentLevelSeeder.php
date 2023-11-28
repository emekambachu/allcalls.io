<?php

namespace Database\Seeders;

use App\Models\InternalAgentLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InternalAgentLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Internal Level 1',
            'Internal Level 2',
            'Internal Level 3',
            'Internal Level 4',
            'AC Level 1',
            'AC Level 2',
            'AC Level 3',
            'AC Level 4',
            'AC Level 5',
            'AC Level 6',
            'AC Level 7',
            'AC Level 8',
            'AC Level 9',
            'AC Level 10',
            'AC Level 11',
        ];
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Truncate the table
        InternalAgentLevel::truncate();
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
 
        foreach ($roles as $role) {
            InternalAgentLevel::updateOrCreate(
                ['name' => $role]
            );
        }
    }
}
