<?php

namespace App\Console\Commands;

use App\Models\Activity;
use Illuminate\Console\Command;

class LogoutUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logout:all-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Logout all login users at 12'o clock";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Activity::whereNull('logout_time')->update([
            'logout_time' => now()
        ]);
    }
}
