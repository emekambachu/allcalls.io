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

        // $schedule->command('log:email-notification-count')->everyMinute();

        $schedule->call(function () {
            // Check the condition before executing the command
            if (config('app.url') == AllCALLS_LIVE) {
                $calls = Call::whereSentToOnscript(false)->count();
                if ($calls > 0) {
                    Artisan::call('allcalls:send-calls-to-on-script');
                }
            }
        })->dailyAt('23:59');


        // TODO: Replace environment to production after testing
        if (app()->environment('production')) {
            // DNC Delta Merge @ 7, 8, 9, 10, 11, 12, 13, 14, 15 EST
            $times = ['07', '08', '09', '10', '11', '12', '13', '14', '15'];
            foreach ($times as $time) {
                $schedule->command('allcalls:merge-dnc')
                    ->timezone('America/New_York')
                    ->dailyAt("{$time}:00");
            }
        }

        $schedule->command('allcalls:check-dnc-health')
            ->timezone('America/New_York') // EST timezone
            ->dailyAt('15:00'); // 3 PM
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
