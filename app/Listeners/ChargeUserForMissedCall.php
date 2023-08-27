<?php

namespace App\Listeners;

use Exception;
use App\Models\User;
use App\Events\MissedCallEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChargeUserForMissedCall
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
    public function handle(MissedCallEvent $event): void
    {
        // Log the handling of the event
        Log::debug('Handling ChargeUserForMissedCall');

        Log::debug('Tempoarily charging for missed calls are paused until are bugs are worked out.');
        return;
        
        try {
            // Retrieve user_id from the event data
            $userId = $event->user->id;

            // Retrieve the user from the database
            $user = User::find($userId);

            if ($user) {
                Log::debug("Found user with ID $userId");
                
                // Check if the user has sufficient balance
                if ($user->balance >= 5) {
                    // Deduct $5 from the user's balance
                    DB::transaction(function () use ($user) {
                        $user->decrement('balance', 5);
                    });

                    Log::debug('Deducted $5 from user balance');
                } else {
                    Log::warning('Insufficient balance to charge for missed call');
                    return;
                }
            } else {
                Log::warning("No user found with ID $userId");
                return;
            }
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('An error occurred while attempting to charge the user for a missed call: ' . $e->getMessage());
        }


    }
}
