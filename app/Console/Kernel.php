<?php

namespace App\Console;

use App\Models\Call;
use http\Env;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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
