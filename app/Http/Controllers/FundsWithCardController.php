<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Card;
use App\Models\Transaction;
use App\Services\NMIGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Crypt;
use Stripe\Exception\ApiErrorException;

class FundsWithCardController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the request
        $request->validate([
            'number' => 'required|numeric|digits_between:15,16',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvv' => 'required|numeric|digits:4',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',

            'zip' => 'required|numeric|digits:6',
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

    public function storeWithStripe(Request $request)
    {
        // dd($request);
        // Step 1: Validate incoming request data
        $request->validate([
            'number' => 'required|numeric|digits_between:13,16',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvv' => 'required|numeric|between:100,9999',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|numeric|digits:5',
            'amount' => 'required|numeric|integer|min:1'
        ]);
        Log::debug('Validated request data.', $request->all());

        // Step 2: Calculate final payment amount
        $subtotal = (float) $request->amount;
        $processingFee = $subtotal * 0.03;
        $totalWithFee = $subtotal + $processingFee;
        $finalAmount = number_format($totalWithFee, 2, '.', '');

        Log::debug('Calculated final amount with processing fee: ' . $finalAmount);

        try {
            // Step 3: Encrypt credit card details
            $encryptedNumber = Crypt::encryptString($request->number);
            $encryptedMonth = Crypt::encryptString($request->month);
            $encryptedYear = Crypt::encryptString($request->year);
            $encryptedCvv = Crypt::encryptString($request->cvv);
            Log::debug('Encrypted card details.');

            // Step 4: Initialize Stripe client
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            Log::debug('Initialized Stripe client.');

            // Step 5: Generate token using credit card details
            // $token = $stripe->tokens->create([
            //     'card' => [
            //         'number' => $request->number,
            //         'exp_month' => $request->month,
            //         'exp_year' => $request->year,
            //         'cvc' => $request->cvv,
            //     ],
            // ]);
            Log::debug('Generated Stripe token.');

            // Step 6: Create charge using generated token
            $charge = $stripe->charges->create([
                'amount' => $finalAmount * 100, // converting to cents
                'currency' => 'usd',
                // 'source' => $token,
                'source' => 'tok_visa',
                'description' => 'Add Fund',
            ]);
            Log::debug('Created charge in Stripe.', ['charge_id' => $charge['id']]);

            if (empty($charge) || $charge['status'] != 'succeeded') {
                // Payment declined
                Log::error('Stripe charge was not successful.');
                return redirect()->back();
            }

            // Step 7: Store encrypted credit card details in the database
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
            Log::debug('Stored encrypted card details in the database.', ['card_id' => $card->id]);

            // Step 8: Update user's balance
            $updatedBalance = $request->user()->balance + $subtotal;
            $request->user()->update(['balance' => $updatedBalance]);
            Log::debug('Updated user balance.', ['user_id' => $request->user()->id, 'new_balance' => $updatedBalance]);

            // Step 9: Log the transaction in the database
            Transaction::create([
                'amount' => $subtotal,
                'user_id' => $request->user()->id,
                'sign' => true,
                'card_id' => $card->id,
            ]);
            Log::debug('Logged transaction in the database.');

            // Step 10: Redirect user back with success message
            return redirect()->back()->with(['message' => 'Payment successful and card added.']);
        } catch (CardException $e) {
            Log::error('Stripe card error: ' . $e->getMessage());
            return abort(400, $e->getMessage());  // Bad request status for card errors
        } catch (ApiErrorException $e) {
            Log::error('Stripe API error: ' . $e->getMessage());
            return abort(502, $e->getMessage());  // Bad Gateway status for API errors
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage());
            return abort(500, $e->getMessage());  // Internal server error for general exceptions
        }
    }


    public function stripeStore(Request $request)
    {
        // Step 1: Validate incoming request data
        $request->validate([
            'amount' => 'required|numeric|integer|min:1',
            'paymentMethod' => 'required'
        ]);

        $user = $request->user();

        $paymentMethod = $request->input('payment_method');

        // Step 2: Calculate final payment amount
        $subtotal = (float) $request->amount;
        $processingFee = $subtotal * 0.03;
        $totalWithFee = $subtotal + $processingFee;
        $finalAmount = number_format($totalWithFee, 2, '.', '');


        DB::beginTransaction();

        try {
            // Step 3: Create Charge
            $user->charge($totalWithFee, $paymentMethod);
    
            // Step 4: Update user's balance
            $updatedBalance = $request->user()->balance + $subtotal;
            $request->user()->update(['balance' => $updatedBalance]);
    
            // Step 5: Log the transaction in the database
            Transaction::create([
                'amount' => $subtotal,
                'user_id' => $user->id,
                'sign' => true,
            ]);
    
            DB::commit();
    
            return redirect()->back()->with(['message' => 'Payment successful.']);
        } catch (Exception $e) {
            DB::rollback();
            // Log the error for debugging
            Log::error('Payment failed: ' . $e->getMessage());
    
            return redirect()->back()->with(['error' => 'Payment failed.']);
        }
    }
}
