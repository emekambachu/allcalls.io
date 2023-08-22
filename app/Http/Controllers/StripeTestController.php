<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StripeTestController extends Controller
{
    public function show()
    {
        return view('stripe-test');
    }

    public function store(Request $request)
    {
        $request->validate([
            'paymentMethodId' => 'required',
            'amount' => 'required',
        ]);

        // If not authenticated, use user 2 for testing.
        $user = $request->user() ?? User::findOrFail(2);

        $stripeCharge = $user->charge(
            (int) $request->amount, $request->paymentMethodId
        );

        return $stripeCharge;
        // dd($stripeCharge);
    }
}
