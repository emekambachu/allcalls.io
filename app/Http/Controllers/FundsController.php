<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
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

            return $card;
        });

        return Inertia::render('Billing/Funds', compact('cards'));
    }
}
