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
        Log::debug($event->uniqueCallId);

        $call = Call::create([
            'user_id' => $event->user->id,
            'call_type_id' => $event->callTypeId,
            'unique_call_id' => $event->uniqueCallId,
        ]);

        // Query the external database
        $results = DB::connection('mysql2')->select("SELECT * FROM users WHERE phone = ? LIMIT 1", [$event->phone]);

        Log::debug($results);

        // Client::create([
        //     'first_name' => 'John',
        //     'last_name' => 'Doe',
        //     'phone' => '+1234567890',
        //     'zipCode' => '10001',
        //     'email' => 'john@example.com',
        //     'address' => '123 Main Street',
        //     'dob' => '99-12-01',
        //     'call_id' => $call->id,
        // ]);
    }
}
