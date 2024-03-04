<?php

namespace App\Console\Commands;

use App\Events\SendToOnScriptUpdate;
use App\Listeners\SendCallInfoToOnScriptAI;
use App\Models\Call;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendCallsToOnscript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:send-calls-to-on-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send calls to sccript';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $calls = Call::whereSentToOnscript(false)->get();
            foreach ($calls as $call){
                SendToOnScriptUpdate::dispatch($call,$call->user);
            }
            Log::debug('AUTOMATE DAILY SCRIPT RUNS SUCCESSFULLY');
            $this->info('Your command executed successfully!');
        } catch (\Exception $e){

            Log::debug('AUTOMATE DAILY SCRIPT RUNS FAILED:', ['ERROR' => $e]);
        }
    }
}
