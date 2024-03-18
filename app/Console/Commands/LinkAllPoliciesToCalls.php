<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Models\Call;
use App\Models\Client;
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

        $policies = InternalAgentMyBusiness::whereNull('call_id')->get();

        try {
            // Assuming $businesses is a collection of Business models
            foreach ($policies as $policy) {
                if ($policy->client_id) {
                    $client = Client::find($policy->client_id);
                    if ($client) {
                        $policy->call_id = $client->call_id;
                        $policy->save();
                        $policy = $policy->refresh();
                    }
                }

                if ($policy->call_id) {
                    $call = Call::find($policy->call_id);
                    if ($call) {
                        $call->policy_id = $policy->id;
                        $call->save();
                    }
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
