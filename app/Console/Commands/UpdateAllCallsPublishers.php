<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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
    protected $signature = 'allcalls:update-all-calls-publishers {--only-last-week}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the publisher info in the calls existing call records.';

    public function handle()
    {
        $query = Call::whereNull('publisher_name')->whereNull('publisher_id');

        // Check if the --only-last-week option is provided:
        if ($this->option('only-last-week')) {
            $weekAgo = Carbon::now()->subDays(7);
            $query->where('created_at', '>=', $weekAgo);
        }

        $calls = $query->get();

        $this->info('Found ' . $calls->count() . ' calls without publisher info.');

        // Loop through each call and update the publisher info:
        foreach ($calls as $call) {
            $response = $call->updatePublisherInfo();

            if ($response) {
                $this->info('Updated call ' . $call->id . ' with publisher info.');
                // log the response:
            } else {
                $this->info('Failed to update call ' . $call->id . ' with publisher info.');
            }
        }
    }
}
