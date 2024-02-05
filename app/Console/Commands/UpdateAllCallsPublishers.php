<?php

namespace App\Console\Commands;

use App\Models\Call;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateAllCallsPublishers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:update-all-calls-publishers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the publisher info in the calls existing call records.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all calls where publisher_name and publisher_id is null:
        $calls = Call::whereNull('publisher_name')->whereNull('publisher_id')->get();

        $this->info('Found ' . $calls->count() . ' calls without publisher info.');

        // Loop through each call and update the publisher info:
        foreach ($calls as $call) {
            $response = $call->updatePublisherInfo();

            if ($response) {
                $this->info('Updated call ' . $call->id . ' with publisher info.');
                // log the response:
                dd($response);
            } else {
                $this->info('Failed to update call ' . $call->id . ' with publisher info.');
            }
        }
    }
}
