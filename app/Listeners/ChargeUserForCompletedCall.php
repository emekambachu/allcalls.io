<?php

namespace App\Listeners;

use App\Models\Call;
use App\Events\FundsTooLow;
use App\Models\Transaction;
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

                Transaction::create([
                    'amount' => 35,
                    'sign' => 0,
                    'user_id' => $event->user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'label' => 'Purchased call'
                ]);

                $call = Call::where('unique_call_id', $event->uniqueCallId)->first();
                if ($call) {
                    $call->amount_spent = 35;
                    $call->save();
                }
            });
            Log::debug('Deducted $35 from user balance after completed call');
        } else {
            FundsTooLow::dispatch($event->user);
            Log::warning('Insufficient balance to charge for completed call');
            return;
        }
    }
}
