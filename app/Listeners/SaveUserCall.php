<?php

namespace App\Listeners;

use App\Models\Call;
use App\Models\Client;
use App\Events\RingingCallEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveUserCall
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RingingCallEvent $event): void
    {
        Log::debug('Save the call now');
        Log::debug($event->user->id);

        $call = Call::create([
            'user_id' => $event->user->id,
            'call_type_id' => $event->callTypeId,
            'unique_call_id' => $event->uniqueCallId,
            'from' => $event->from,
        ]);

        Log::debug('FROM IS: ' . $event->from);

        Log::debug($call->toArray());

        $this->searchBrooksIM($event->from);

        // Query the external database
        $results = DB::connection('mysql2')->select("SELECT * FROM leads WHERE phone = ? LIMIT 1", [$event->from]);
        if (!sizeof($results)) {


            // First check if brooksIM returned something:
            if ($responseData = $this->searchBrooksIM($event->from)) {
                $this->saveBrooksIMClient($event->from, $event->user->id, $event->callTypeId, $call->id, $responseData);
                return;
            }

            $this->saveEmptyClient($event->from, $event->user->id, $event->callTypeId, $call->id);
            return;
        }
        $potentialLead = $results[0];

        Log::debug('Results from external database.');
        Log::debug($results);

        $client = Client::create([
            'first_name' => $potentialLead->firstName,
            'last_name' => $potentialLead->lastName,
            'phone' => $event->from,
            'zipCode' => $potentialLead->zipCode,
            'email' => $potentialLead->email,
            'address' => $potentialLead->address,
            'dob' => $potentialLead->dob,
            'call_id' => $call->id,
            'user_id' => $event->user->id,
            'call_type_id' => $event->callTypeId,
            'state' => $potentialLead->state,
        ]);

        Log::debug('Client saved.');
        Log::debug($client->toArray());
    }

    protected function searchBrooksIM($phone)
    {
        $response = Http::withHeaders(['x-api-key' => env('BROOKS_IM_API_KEY')])
            ->post(
                'https://api.imdatacenter.com/1.0/phone',
                [
                    'client_id' => uniqid(),
                    'phone' => (int) $phone,
                    'process' => ['fd', 'fe'],
                    'wireless_only' => false,
                    'immediate' => true,
                    'match_level' => 'individual'
                ]
            );

        if ( !$response->ok() )
        {
            return false;
        }


        Log::debug('BrooksIM returned a response.');
        Log::debug($response->body());
        return false;
    }
    protected function saveBrooksIMClient($from, $userId, $callTypeId, $callId, $responseData)
    {
        Log::debug('Calling saveBrooksIMClient');
    }

    protected function saveEmptyClient($from, $userId, $callTypeId, $callId)
    {

        $client = Client::create([
            'first_name' => 'N/A',
            'last_name' => 'N/A',
            'phone' => $from,
            'zipCode' => 'N/A',
            'email' => 'N/A',
            'address' => 'N/A',
            'dob' => 'N/A',
            'call_id' => $callId,
            'user_id' => $userId,
            'call_type_id' => $callTypeId,
            'state' => 'N/A',
        ]);

        Log::debug('Client saved.');
        Log::debug($client->toArray());

        return $client;
    }
}
