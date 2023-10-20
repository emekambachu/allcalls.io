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
                // If the user’s bid amount matches another user’s bid amount:
                if ($allBids->where('amount', $userBid->amount)->count() > 1) {
                    Log::debug('Charge amount is the same as the user bid amount');
                    $chargeAmount = $userBid->amount;
                } 
                // Else if user's bid is greater than 35 AND there’s no other bid amount greater than 35 in the list, apart from this user’s bid:
                elseif ($userBid->amount > 35 && $allBids->where('amount', '>', 35)->count() < 2) {
                    // if there are NO bids at all other than users bid then charge $35, otherwise charge $36
                    if ( $allBids->count() == 1 ) {
                        Log::debug('Charge amount is 35 as there are no other bidders at all');
                        $chargeAmount = 35;
                    } else {
                        Log::debug('Charge amount is 36 as there are other bids, just not greater than 35');
                        $chargeAmount = 36;
                    }
                }
                // Else, find the bidder after this user’s bid
                else {
                    $userBidIndex = $allBids->search(function ($bid) use ($userBid) {
                        return $bid->id == $userBid->id;
                    });
                    Log::debug('User Bid Index:', ['index' => $userBidIndex]);
                    
                    if (isset($allBids[$userBidIndex + 1])) {
                        Log::debug('Charge amount is the next highest bid amount');
                        $chargeAmount = $allBids[$userBidIndex + 1]->amount + 1;
                    } else {
                        Log::debug('Charge amount is 35 as there are no other bids greater than 35 in the final else block');
                        $chargeAmount = 35; // default as there's no bidder after this user's bid
                    }
                }
            } else {
                Log::debug('User bid not found');
                return;
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
