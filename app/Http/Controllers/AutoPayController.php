<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
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

        return Inertia::render('Billing/Autopay', compact('cards'));
    }
}
