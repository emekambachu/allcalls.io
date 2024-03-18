<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Models\Call;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\InternalAgentMyBusiness;

class LinkAllPoliciesToCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:link-all-policies-to-calls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Links all policies to calls.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Start Transaction
        DB::beginTransaction();

        $businesses = InternalAgentMyBusiness::whereNull('call_id')->get();

        try {
            // Assuming $businesses is a collection of Business models
            foreach ($businesses as $business) {
                $dateTillEndOfDay = Carbon::parse($business->application_date)->endOfDay();

                $matchedRecord = Call::whereFrom(trim($business->client_phone_no))
                    ->where('created_at', '<=', $dateTillEndOfDay)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($matchedRecord) {
                    $matchedRecord->policy_id = $business->id;
                    $business->call_id = $matchedRecord->id;

                    // Save in transaction
                    $business->save();
                    $matchedRecord->save();

                    Log::debug("Assign Call To Business --> {$business} AND Call Record ---> {$matchedRecord}");
                } else {
                    Log::debug("Date --> $dateTillEndOfDay Call Record Not found For Business --> {$business}");
                }
            }

            // Commit Transaction
            DB::commit();
        } catch (Exception $e) {
            // Rollback Transaction on Error
            DB::rollBack();
            Log::error("Error Processing Call Assignment: {$e->getMessage()}");
        }
    }
}
