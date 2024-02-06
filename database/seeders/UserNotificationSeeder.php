<?php

namespace Database\Seeders;

use App\Models\User\UserNotification;
use Illuminate\Database\Seeder;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserNotification::factory()->count(5)->create();
    }
}
