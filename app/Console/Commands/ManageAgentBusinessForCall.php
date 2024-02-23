<?php

namespace App\Console\Commands;

use App\Models\Call;
use App\Models\InternalAgentMyBusiness;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ManageAgentBusinessForCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:manage-agent-business-for-call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign agent business to a call.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $businesses = InternalAgentMyBusiness::whereNull('call_id')->get();
        $total = $businesses->count();

        $this->info("Total Businesses --> $total");

        try {
            foreach ($businesses as $business) {
                $dateTillEndOfDay = Carbon::parse($business->application_date)->endOfDay();
    
                //Assign Call To Business
                $matchedRecord = Call::whereFrom(trim($business->client_phone_no))
                    ->where('created_at', '<=', $dateTillEndOfDay)
                    ->orderBy('created_at', 'desc')->first();
                if ($matchedRecord) {
                    $business->call_id = $matchedRecord->id;
                    if (isset($matchedRecord->client)) {
                        $matchedRecord->client->status = 'Sale - Guaranteed Issue';
                        $matchedRecord->client->save();
                    }
                    $business->save();
                }
            }
    
            $this->info("Assigned call to business for the agent clients.");
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $this->info("Exception --> $error");
        }
    }
}
