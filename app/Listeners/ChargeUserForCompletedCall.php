<?php

namespace App\Listeners;

use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChargeUserForCompletedCall
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
    public function handle(CompletedCallEvent $event): void
    {
        Log::debug('ChargeUserForCompletedCall');
        Log::debug('User:');
        Log::debug($event->user);

        // Check if the user has sufficient balance
        if ($event->user->balance >= 35) {
            // Deduct $5 from the user's balance
            DB::transaction(function () use ($event) {
                $event->user->decrement('balance', 35);
            });

            Log::debug('Deducted $25 from user balance after completed call');
        } else {
            Log::warning('Insufficient balance to charge for completed call');
            return;
        }
    }
}
