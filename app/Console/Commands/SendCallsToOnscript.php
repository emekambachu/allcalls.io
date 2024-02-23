<?php

namespace App\Console\Commands;

use App\Models\Call;
use Illuminate\Console\Command;

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
        $calls = Call::whereSentToOnscript(false)->get();
        $total = $calls->count();
        $this->info("Total Calls --> $total");
        $this->info('Your command executed successfully!');
    }
}
