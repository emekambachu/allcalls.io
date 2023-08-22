<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Services\NMIGateway;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class FundsController extends Controller
{
    public function index()
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

        return Inertia::render('Billing/Funds', compact('cards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|integer|min:1',
            'cardId' => [
                'required',

                // Ensures the cardId sent actually belongs to the currently authenticated user.
                Rule::exists('cards', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
        ]);

        $card = Card::findOrFail($request->cardId);
        $user = Auth::user();

        $gw = new NMIGateway;
        $gw->setLogin(env('NMI_KEY'));

        $gw->setBilling($user->first_name, $user->last_name, $card->address, $card->city, $card->state, $card->zip, 'US', $user->phone, $user->email);
        
        $cardNumber = Crypt::decryptString($card->number);
        $cardMonth = Crypt::decryptString($card->month);
        $cardYear = Crypt::decryptString($card->year);

        $subtotal = (float) $request->amount;

        // Adding 3% processing fee to the subtotal
        $totalWithFee = $subtotal * 1.03;
        
        // Format to two decimal places
        $finalAmount = number_format($totalWithFee, 2, '.', '');

        Log::debug('Final Amount: ' . $finalAmount);
        
        $r = $gw->doSale($finalAmount, $cardNumber, $cardMonth . substr($cardYear, -2));
        $response = $gw->responses['responsetext'];
        
        if ($response !== 'SUCCESS') {
            // The payment is declined:
            dd('RESPONSE IS NOT SUCCESS');
        }

        // The payment is approved:
        $user->update([
            'balance' => $user->balance + (float) $request->amount,
        ]);

        // Add the transactions.
        Transaction::create([
            'amount' => (float) $request->amount,
            'user_id' => $user->id,
            'sign' => true,
            'card_id' => $card->id,
        ]);

        return redirect()->back()->with([
            'message' => '$' . $request->amount . ' added to your funds.'
        ]);
    }
}
