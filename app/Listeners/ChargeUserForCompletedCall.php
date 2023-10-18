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
            // Fetch all the bids for the call type in descending order
            $allBids = Bid::where('call_type_id', $event->callType->id)
                ->orderBy('amount', 'desc')
                ->get();

            Log::debug('All Bids:', ['bids' => $allBids->toArray()]);

            $userBid = $allBids->firstWhere('user_id', $event->user->id);
            Log::debug('User Bid:', ['userBid' => $userBid]);

            if ($userBid) {
                $userBidIndex = $allBids->search(function ($bid) use ($userBid) {
                    return $bid->id == $userBid->id;
                });
                Log::debug('User Bid Index:', ['index' => $userBidIndex]);

                if ($userBidIndex == 0) { // if the user has the highest bid
                    Log::debug('User has the highest bid');
                    if ($allBids->where('amount', $userBid->amount)->count() > 1) {
                        // Multiple users have bid the same highest amount
                        $chargeAmount = $userBid->amount; // charge the exact bid amount
                    } else if (count($allBids) == 1) {
                        $chargeAmount = 35;
                    } else {
                        $chargeAmount = $allBids[1]->amount + 1; // second highest bid + 1
                    }
                
                } else {
                    Log::debug('User does not have the highest bid');
                    if (isset($allBids[$userBidIndex + 1])) { // if there's a lower bid
                        $chargeAmount = $allBids[$userBidIndex + 1]->amount + 1; // next lower bid + 1
                        Log::debug('User is charged next lower bid + 1', ['chargeAmount' => $chargeAmount]);
                    } else {
                        $chargeAmount = $userBid->amount; // lowest bid pays their amount
                        Log::debug('User is charged their bid amount as it\'s the lowest', ['chargeAmount' => $chargeAmount]);
                    }
                }
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
