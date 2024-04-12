<?php

namespace App\Listeners;

use DateTime;
use Exception;
use DateInterval;
use DateTimeZone;
use App\Models\Call;
use App\Models\Client;
use DateTimeInterface;
use App\Models\CallType;
use App\Models\UserActivity;
use App\Events\InitiatedCallEvent;
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
    public function handle(InitiatedCallEvent $event): void
    {
        Log::debug('Save the call now');
        Log::debug($event->user->id);
        
        Log::debug('Save the call now: callSid: ' . $event->firstCallSid);
        Log::debug('Save the call now: parentCallSid: ' . $event->secondCallSid);
        Log::debug('Save the call now: callToken: ' . $event->twilioCallToken);

        $call = Call::create([
            'user_id' => $event->user->id,
            'call_type_id' => $event->callTypeId,
            'unique_call_id' => $event->uniqueCallId,
            'from' => $event->from,
            'call_duration_in_seconds' => 0,
            'call_sid' => $event->firstCallSid,         // Saving firstCallSid to call_sid column
            'parent_call_sid' => $event->secondCallSid, // Saving secondCallSid to parent_call_sid column
            'twilio_call_token' => $event->twilioCallToken
        ]);

        UserActivity::create([
            'action' => 'Call taken.',
            'data' => json_encode([]),
            'platform' => 'N/A',
            'user_id' => $event->user->id,
            'ip_address' => 'N/A',
            'user_agent' => 'N/A',
        ]);

        // Save the last_called_at timestamp on the user.
        $event->user->last_called_at = now();
        $event->user->save();

        Log::debug('SaveUserCallForPhone:', ['phone' => $event->from]);

        // Query the external database
        $results = DB::connection('mysql2')->select("SELECT * FROM leads WHERE phone = ? LIMIT 1", [$event->from]);
        if (!sizeof($results)) {
            // First check if brooksIM returned something:
            if ($responseData = $this->searchBrooksIM($event->from)) {
                try {
                    $this->saveBrooksIMClient($event->from, $event->user->id, $event->callTypeId, $call->id, $responseData);
                    return;
                } catch (Exception $e) {
                    Log::debug('Exception thrown while saving brooksim client: ' . $e->getMessage());
                }

                $this->saveEmptyClient($event->from, $event->user->id, $event->callTypeId, $call->id);
                return;
            }

            $this->saveEmptyClient($event->from, $event->user->id, $event->callTypeId, $call->id);
            return;
        }
        $potentialLead = $results[0];

        Log::debug('Results from external database.');
        Log::debug($results);

        $client = Client::create([
            'first_name' => $potentialLead->firstName ?? 'N/A',
            'last_name' => $potentialLead->lastName ?? 'N/A',
            'phone' => $event->from,
            'zipCode' => $potentialLead->zipCode ?? 'N/A',
            'email' => $potentialLead->email ?? 'N/A',
            'address' => $potentialLead->address ?? 'N/A',
            'dob' => $potentialLead->dob ?? 'N/A',
            'call_id' => $call->id,
            'user_id' => $event->user->id,
            'call_type_id' => $event->callTypeId,
            'state' => $potentialLead->state ?? 'N/A',
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

        if (!$response->ok()) {
            return false;
        }


        Log::debug('BrooksIM returned a response.');
        Log::debug($response->body());
        return $response->body();
    }

    protected function saveBrooksIMClient($from, $userId, $callTypeId, $callId, $responseData)
    {
        Log::debug('Starting saveBrooksIMClient method.');

        // Decode the BrooksIM response data.
        $data = json_decode($responseData, true);
        Log::debug('Decoded BrooksIM response:');
        Log::debug($data);

        // Extract result data.
        $result = $data['result'];
        Log::debug('Extracted result from BrooksIM response:');
        Log::debug($result);

        // Define the client data to be saved.
        $clientData = [
            'first_name' => $result['name']['first_name'] ?? 'N/A',
            'last_name' => $result['name']['last_name'] ?? 'N/A',
            'phone' => $from,
            'zipCode' => $result['address']['zip'] ?? 'N/A',
            'email' => 'N/A',  // As it's not in the provided BrooksIM sample.
            'address' => $result['address']['address1'] . ' ' . $result['address']['address2'] ?? 'N/A',
            'dob' => 'N/A',    // As it's not in the provided BrooksIM sample.
            'call_id' => $callId,
            'user_id' => $userId,
            'call_type_id' => $callTypeId,
            'state' => $result['address']['state'] ?? 'N/A',
        ];
        Log::debug('Defined client data to be saved:');
        Log::debug($clientData);

        // Save the client.
        $client = Client::create($clientData);
        Log::debug('Client saved to database.');

        // Log the saved client data.
        Log::debug('Client saved from BrooksIM data:');
        Log::debug($client->toArray());

        return $client;
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
