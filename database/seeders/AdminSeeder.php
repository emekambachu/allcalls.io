<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
            'first_name' => 'system',
            'last_name' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '000000000',
        ]);

        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role_id' => 1,
        ]);
    }
}
