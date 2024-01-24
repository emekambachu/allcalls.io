<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([ 'email' => 'user@user.com'],[
            'first_name' => 'system',
            'last_name' => 'user',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '111111111',
        ]);

        $userAlreadySet = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', 2)
            ->first();

        if(!$userAlreadySet) {
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $agent = User::updateOrCreate([ 'email' => 'agent@agent.com'],[
            'first_name' => 'system',
            'last_name' => 'agent',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '111111111',
        ]);

        $agentAlreadySet = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', 3)
            ->first();

        if(!$agentAlreadySet) {
            DB::table('role_user')->insert([
                'user_id' => $agent->id,
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        User::factory()->count(1300)->create();

    }
}
