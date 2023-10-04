<?php

namespace App\Listeners;

use App\Models\Call;
use App\Models\Bid;
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

        // Determine if the user is an internal agent
        $isInternalAgent = $event->user->roles->contains('name', 'internal-agent');

        $chargeAmount = 35; // default value for internal agents

        // If the user is not an internal agent, find their bid for the call type
        if (!$isInternalAgent) {
            // Fetch the two highest bids for the call type
            $topTwoBids = Bid::where('call_type_id', $event->callType->id)
                ->orderBy('amount', 'desc')
                ->take(2)
                ->get();
                
            if ($topTwoBids->count() == 2) {
                // If our user has the highest bid
                if ($topTwoBids->first()->user_id == $event->user->id) {
                    $chargeAmount = $topTwoBids->last()->amount + 1; // Charge one more than the second highest bid
                } else {
                    // If our user does not have the highest bid, then their charge is their own bid amount
                    $chargeAmount = $topTwoBids->first()->amount;
                }
            } else {
                Log::warning("Not enough bids found for CallType ID {$event->callType->id}. Defaulting to user's bid amount.");
                $chargeAmount = $topTwoBids->first()->amount; // Defaulting to the only bid amount available
            }
        }

        Log::debug("Charge amount: $$chargeAmount");

        // Check if the user has sufficient balance
        if ($event->user->balance >= $chargeAmount) {
            // Deduct the charge amount from the user's balance
            DB::transaction(function () use ($event, $chargeAmount) {
                $event->user->decrement('balance', $chargeAmount);

                Transaction::create([
                    'amount' => $chargeAmount,
                    'sign' => 0,
                    'user_id' => $event->user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'label' => 'Purchased call'
                ]);

                $call = Call::where('unique_call_id', $event->uniqueCallId)->first();
                if ($call) {
                    $call->amount_spent = $chargeAmount;
                    $call->save();
                }
            });
            Log::debug("Deducted $$chargeAmount from user balance after completed call");
        } else {
            FundsTooLow::dispatch($event->user);
            Log::warning('Insufficient balance to charge for completed call');
            return;
        }
    }
}
