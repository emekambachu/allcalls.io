<?php

namespace App\Console;

use http\Env;
use App\Models\Call;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('allcalls:release-available-numbers')
            ->timezone('America/New_York')
            ->dailyAt('00:00');

        $schedule->command('allcalls:allcalls:send-equis-duplicate-emails')
            ->timezone('America/New_York')
            ->dailyAt('05:00');


        $schedule->call(function () {
            // Check the condition before executing the command
            if(config('app.url')== AllCALLS_LIVE){
                $calls = Call::whereSentToOnscript(false)->count();
                if ($calls > 0) {
                    Artisan::call('allcalls:send-calls-to-on-script');
                }
            }
        })->dailyAt('23:59');

        $schedule->call(function () {
            Log::info("Checking email notification count...");

            $key = 'email_notifications:count:' . now()->subMinute()->format('Y-m-d:H:i');
            $count = Redis::get($key);
            Log::info("Email notifications sent in the last minute: {$count}");
    
            // Optionally, you can delete the key if you don't need to keep history in Redis
            Redis::del($key);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
