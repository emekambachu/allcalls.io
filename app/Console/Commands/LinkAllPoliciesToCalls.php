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

        $policies = InternalAgentMyBusiness::all();

        try {
            // Assuming $businesses is a collection of Business models
            // foreach ($policies as $policy) {
            //     if ($policy->client_id) {
            //         $client = Client::find($policy->client_id);
            //         if ($client) {
            //             $policy->call_id = $client->call_id;
            //             $policy->save();
            //             $policy = $policy->refresh();

            //             $call = Call::find($client->call_id);
            //             if ($call) {
            //                 $call->policy_id = $policy->id;
            //                 $call->save();
            //             }
            //         }
            //     }
            // }


            foreach($policies as $policy) {
                // Check if the call_id already exists:
                if ($policy->call_id) {
                    // If it does, find the call record and update the policy_id
                    $call = Call::find($policy->call_id);
                    if ($call) {
                        $call->policy_id = $policy->id;
                        $call->save();
                    }
                } else {
                    // If it doesn't, find the client record and update the call_id
                    $client = Client::find($policy->client_id);
                    // Then find the call record and update the policy_id
                    if ($client) {
                        $policy->call_id = $client->call_id;
                        $policy->save();
                        $policy = $policy->refresh();

                        $call = Call::find($client->call_id);
                        if ($call) {
                            $call->policy_id = $policy->id;
                            $call->save();
                        }
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
