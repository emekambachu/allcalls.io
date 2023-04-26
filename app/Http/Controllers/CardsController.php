<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CardsController extends Controller
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

        return Inertia::render('Billing/Cards', compact('cards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|digits_between:13,19',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvv' => 'required|numeric|digits:3',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
        ]);

        $encryptedNumber = Crypt::encryptString($request->number);
        $encryptedMonth = Crypt::encryptString($request->month);
        $encryptedYear = Crypt::encryptString($request->year);
        $encryptedCvv = Crypt::encryptString($request->cvv);
    
        Card::create([
            'number' => $encryptedNumber,
            'month' => $encryptedMonth,
            'year' => $encryptedYear,
            'cvv' => $encryptedCvv,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->back();
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return redirect()->back();
    }
}
