<?php

namespace App\Listeners;

use App\Models\Call;
use App\Models\Client;
use App\Events\RingingCallEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        Log::debug($call->toArray());

        // Query the external database
        $results = DB::connection('mysql2')->select("SELECT * FROM leads WHERE phone = ? LIMIT 1", [$event->from]);
        if (!sizeof($results)) return;
        $potentialLead = $results[0];

        Log::debug('Results from external database.');
        Log::debug($results);

        $client = Client::create([
            'first_name' => $potentialLead->first_name,
            'last_name' => $potentialLead->last_name,
            'phone' => $event->from,
            'zipCode' => $potentialLead->zipCode,
            'email' => $potentialLead->email,
            'address' => $potentialLead->address,
            'dob' => $potentialLead->dob,
            'call_id' => $call->id,
        ]);

        Log::debug('Client saved.');
        Log::debug($client->all());
    }
}
