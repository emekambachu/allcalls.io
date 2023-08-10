<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Services\NMIGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FundsWithCardController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the request
        $request->validate([
            'number' => 'required|numeric|digits_between:13,19',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvv' => 'required|numeric|digits:3',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'amount' => 'required|numeric|integer|min:1'
        ]);

        // 2. Set up and process payment
        $gw = new NMIGateway;
        $gw->setLogin(env('NMI_KEY'));

        $gw->setBilling($request->user()->first_name, $request->user()->last_name, $request->address, $request->city, $request->state, $request->zip, 'US', $request->user()->phone, $request->user()->email);

        $r = $gw->doSale($request->amount, $request->number, $request->month . substr($request->year, -2));
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

        Card::create([
            'number' => $encryptedNumber,
            'month' => $encryptedMonth,
            'year' => $encryptedYear,
            'cvv' => $encryptedCvv,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'user_id' => $request->user()->id,
        ]);

        // 4. Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Payment successful and card added.'
        ]);

    }
}