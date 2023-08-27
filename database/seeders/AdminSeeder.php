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

        $adminAlreadySet = DB::table('role_user')
            ->where('user_id', $admin->id)
            ->where('role_id', 1)
            ->first();

        if(!$adminAlreadySet) {
            DB::table('role_user')->insert([
                'user_id' => $admin->id,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
