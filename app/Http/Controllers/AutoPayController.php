<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\AutopaySetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AutoPayController extends Controller
{
    public function show()
    {
        $cards = Auth::user()->cards;

        $cards = $cards->map(function ($card) {
            $decryptedNumber = Crypt::decryptString($card->number);
            $card->last4 = substr($decryptedNumber, -4);
            $decryptedMonth = Crypt::decryptString($card->month);
            $decryptedYear = Crypt::decryptString($card->year);
            $card->expiryDate = sprintf('%02d/%02d', $decryptedMonth, substr($decryptedYear, -2));

            // Guess the card type based on the card number
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

            return $card;
        });

        $setting = AutopaySetting::whereUserId(Auth::user()->id)->first();

        return Inertia::render('Billing/Autopay', [
            'cards' => $cards,
            'setting' => $setting ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'enabled' => 'required',
            'threshold' => 'required|numeric|integer|min:20',
            'amount' => 'required|numeric|integer|min:50',
            'card_id' => 'required|numeric',
        ]);

        $card = Card::find($request->card_id);

        // Check if the card belongs to the user
        if (! $card || $card->user_id !== Auth::user()->id) {
            return redirect()->back();
        }

        AutopaySetting::updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'enabled' => $request->enabled,
                'threshold' => $request->threshold,
                'amount' => $request->amount,
                'card_id' => $request->card_id,
            ]
        );

        return redirect()->back()->with([
            'message' => 'Autopay settings saved.'
        ]);
    }
    public function storeWithStripe(Request $request){
        $request->validate([
            'enabled' => 'required',
            'threshold' => 'required|numeric|integer|min:20',
            'amount' => 'required|numeric|integer|min:50',
            'card_id' => 'required|numeric',
        ]);
        try{
        $card = Card::find($request->card_id);

        // Check if the card belongs to the user
        if (! $card || $card->user_id !== Auth::user()->id) {
            return redirect()->back();
        }
        $cardNumber = Crypt::decryptString($card->number);
        $cardMonth = Crypt::decryptString($card->month);
        $cardYear = Crypt::decryptString($card->year);
        $cvc = Crypt::decryptString($card->cvv);

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        // Create Token
        $token = $stripe->tokens->create([
            'card' => [
                'number' => $cardNumber,
                'exp_month' => $cardMonth,
                'exp_year' => $cardYear,
                'cvc' => $cvc,
            ],
        ]);
        // Create Charge
        $charge= $stripe->charges->create([
            'amount' => $request->amount,
            'currency' => 'usd',
            'source' => $token,
            'description' => 'Auto Play',
        ]);
        if ( $charge['status'] != 'succeeded') {
              // The payment is declined:
            dd('RESPONSE IS NOT SUCCESS');
        }
        AutopaySetting::updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'enabled' => $request->enabled,
                'threshold' => $request->threshold,
                'amount' => $request->amount,
                'card_id' => $request->card_id,
            ]
        );

        return redirect()->back()->with([
            'message' => 'Autopay settings saved.'
        ]);
    } catch (\Stripe\Exception\CardException $e) {
        // Handle card-related errors
        dd($e->getMessage());
    } catch (\Stripe\Exception\ApiErrorException $e) {
        dd($e->getMessage());
        // Handle other API errors
        //  echo 'API Error: ' . $e->getMessage();
    }catch(Exception $e){
        dd($e->getMessage());
    }
    }
}
