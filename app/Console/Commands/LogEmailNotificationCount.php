<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LogEmailNotificationCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:email-notification-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Total email notification count being sent out per minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Log::info("Checking email notification count...");

        $key = 'email_notifications:count:' . now()->subMinute()->format('Y-m-d:H:i');
        $count = Redis::get($key);
        Log::info("Email notifications sent in the last minute: {$count}");
    
        Redis::del($key);
    }
}
