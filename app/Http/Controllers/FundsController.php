<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Services\NMIGateway;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Stripe\Stripe;
use Stripe\Token;

class FundsController extends Controller
{

    /**
     * Display the form for adding funds to a user's account.
     * This includes the list of existing payment cards that a user can use.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Retrieve the user's stored payment cards
        $cards = $request->user()->cards;

        // Transform the card details for use in the view
        $cards = $cards->map(function ($card) {

            // Decrypt the card number and extract the last 4 digits
            $decryptedNumber = Crypt::decryptString($card->number);
            $card->last4 = substr($decryptedNumber, -4);

            // Decrypt the card's expiry month and year
            $decryptedMonth = Crypt::decryptString($card->month);
            $decryptedYear = Crypt::decryptString($card->year);

            // Format and set the card's expiry date
            $card->expiryDate = sprintf('%02d/%02d', $decryptedMonth, substr($decryptedYear, -2));

            // Determine the type of the card based on its number
            if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $decryptedNumber)) {
                $card->type = 'Visa';
            } elseif (preg_match('/^5[1-5][0-9]{14}$/', $decryptedNumber)) {
                $card->type = 'Mastercard';
            } elseif (preg_match('/^3[47][0-9]{13}$/', $decryptedNumber)) {
                $card->type = 'American Express';
            } elseif (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $decryptedNumber)) {
                $card->type = 'Discover';
            } else {
                $card->type = 'Unknown';
            }

            // Return the card with the added details
            return $card;
        });

        // Render the 'Billing/Funds' view and pass the transformed cards to it
        return Inertia::render('Billing/Funds', compact('cards'));
    }



    public function store(Request $request)
    {
        // 1. Validate the request
        $request->validate([
            'number' => 'required|numeric|digits_between:15,16',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvv' => 'required|numeric|digits_between:3,4',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',

            'zip' => ['required', 'regex:/^[0-9]{5}(?:-[0-9]{4})?$/'],

            'amount' => 'required|numeric|integer|min:1'
        ]);

        // 2. Set up and process payment
        $gw = new NMIGateway;
        $gw->setLogin(env('NMI_KEY'));

        $gw->setBilling($request->user()->first_name, $request->user()->last_name, $request->address, $request->city, $request->state, $request->zip, 'US', $request->user()->phone, $request->user()->email);

        $subtotal = (float) $request->amount;

        // Adding 3% processing fee to the subtotal
        $totalWithFee = $subtotal * 1.03;

        // Format to two decimal places
        $finalAmount = number_format($totalWithFee, 2, '.', '');

        Log::debug('Final Amount: ' . $finalAmount);

        $r = $gw->doSale($finalAmount, $request->number, $request->month . substr($request->year, -2));
        $response = $gw->responses['responsetext'];

        // If the payment is not successful, redirect back with a failure message
        if ($response !== 'SUCCESS') {
            return redirect()->back()->with([
                'message' => 'Payment failed.'
            ]);
        }

        // 3. If payment is successful, save the card
        $encryptedNumber = Crypt::encryptString($request->number);
        $encryptedMonth = Crypt::encryptString($request->month);
        $encryptedYear = Crypt::encryptString($request->year);
        $encryptedCvv = Crypt::encryptString($request->cvv);

        // Check if the user already has a card
        $isFirstCard = !Card::where('user_id', $request->user()->id)->exists();

        $card = Card::create([
            'number' => $encryptedNumber,
            'month' => $encryptedMonth,
            'year' => $encryptedYear,
            'cvv' => $encryptedCvv,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'user_id' => $request->user()->id,
            'default' => $isFirstCard,
        ]);

        // The payment is approved:
        $request->user()->update([
            'balance' => $request->user()->balance + (float) $request->amount,
        ]);

        // Add the transactions.
        Transaction::create([
            'amount' => (float) $request->amount,
            'user_id' => $request->user()->id,
            'sign' => true,
            'card_id' => $card->id,
        ]);

        return redirect()->back()->with([
            'message' => 'Payment successful and card added.'
        ]);
    }
}
