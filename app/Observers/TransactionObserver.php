<?php

namespace App\Observers;

use App\Models\Card;
use App\Models\User;
use App\Models\Transaction;
use App\Services\NMIGateway;
use App\Models\AutopaySetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        Log::debug('TransactionObserver triggered.');

        // Check if there is an AutopaySetting with the user_id same as $transaction->user_id
        $autopaySetting = AutopaySetting::where('user_id', $transaction->user_id)->first();

        if (!$autopaySetting) {
            Log::debug('Autopay setting not found.');
            return;
        }

        // The user in question:
        $user = User::whereId($transaction->user_id)->first();

        if (!$user) {
            Log::debug('User not found.');
            return;
        }

        // Card model to use for the payment:
        $card = Card::whereId($autopaySetting->card_id)->first();

        if (!$card) {
            Log::debug('Card not found.');
            return;
        }

        // if the $user->balance is less than or equal to $autopaySetting->threshold;
        if ($user->balance <= $autopaySetting->threshold) {
            // then add $autopaySetting->amount to $user->balance
            if (!$this->attemptPayment($user, $card, $autopaySetting->amount)) {
                // there was a problem adding that amount to $user->balance.
                Log::debug('There was a problem with the payment.');
                return;
            }

            $user->update([
                'balance' => $user->balance + $autopaySetting->threshold,
            ]);
        }
    }

    protected function attemptPayment($user, $card, $amount)
    {
        $gw = new NMIGateway;
        $gw->setLogin(env('NMI_KEY'));

        $gw->setBilling($user->first_name, $user->last_name, $card->address, $card->city, $card->state, $card->zip, 'US', $user->phone, $user->email);

        $cardNumber = Crypt::decryptString($card->number);
        $cardMonth = Crypt::decryptString($card->month);
        $cardYear = Crypt::decryptString($card->year);

        $r = $gw->doSale($amount, $cardNumber, $cardMonth . substr($cardYear, -2));
        $response = $gw->responses['responsetext'];

        if ($response !== 'SUCCESS') {
            // The payment is declined:
            Log::debug('The payment was declined.');
            return false;
        }

        return true;
    }
}
