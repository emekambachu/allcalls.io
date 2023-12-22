<?php

namespace App\Http\Controllers;

use App\Events\FundsAddedNotification;
use Exception;
use Stripe\Token;
use Stripe\Stripe;
use App\Models\Card;
use Inertia\Inertia;
use App\Events\FundsAdded;
use App\Models\Transaction;
use App\Notifications\FundsAdded as FundsAddedUserNotification;
use App\Services\NMIGateway;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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


    public function store(Request $request, NMIGateway $gw)
    {
        // dd($request->all(), $gw);
        // 1. Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'cardId' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('cards', 'id')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user()->id);
                }),
            ],
            'number' => 'required_without:cardId|numeric|digits_between:15,16',
            'month' => 'required_without:cardId|numeric|min:1|max:12',
            'year' => 'required_without:cardId|numeric|max:' . (date('Y') + 10),
            'cvv' => 'required_without:cardId|numeric|digits_between:3,4',
            'address' => 'required_without:cardId|string|max:255',
            'city' => 'required_without:cardId|string|max:255',
            'state' => 'required_without:cardId|string|max:255',
            'zip' => ['required_without:cardId', 'regex:/^[0-9]{5}(?:-[0-9]{4})?$/'],
        ]);

        // 2. Set up and process payment
        $paymentDetails = $this->processPayment($request, $gw);
        $response = $paymentDetails['response'];
        $subtotal = $paymentDetails['subtotal'];
        $processingFee = $paymentDetails['processingFee'];
        $finalAmount = $paymentDetails['finalAmount'];


        if (!in_array($response, ['SUCCESS', 'Approved'])) {
            Log::debug('The status is not SUCCESS or Approved payment failed');
            Log::debug('RESPONSE TEXT: ' . $response);
            Transaction::create([
                'amount' => 0,
                'user_id' => $request->user()->id,
                'status_code' => 200,
                'nmi_response' => $response,
                'sign' => false,
                'bonus' => false,
                'label' => 'Failed',
            ]);
            return redirect()->back()->with(['message' => 'Payment failed.']);
        }
        // dd($response, $paymentDetails);
        // 3. If payment is successful, save the card
        [$card, $cardStatus] = $this->saveCardDetails($request);  // Returning both the card and its status

        $totalWithBonus = false;
        if ($request->user()->roles->contains('name', 'internal-agent')) {
            $totalWithBonus = (float)$request->amount * 2;
        }

        // 4. Update user balance and transaction
        $this->updateUserBalanceAndTransaction($request, $card, $totalWithBonus);

        FundsAdded::dispatch($request->user(), $subtotal, $processingFee, $finalAmount, $totalWithBonus ? $subtotal : 0, $card);
        FundsAddedNotification::dispatch($request->user()->id, $finalAmount);
        $request->user()->notify(new FundsAddedUserNotification($subtotal));
        Log::debug('FundsAddedNotification dispatched');

        // Prepare the flash message
        $flashMessage = 'Payment successful!';
        if ($cardStatus === 'NEW_CARD') {
            $flashMessage .= ' A new card has been added to your account.';
        }
        if ($totalWithBonus) {
            $flashMessage .= ' A bonus has also been added to your account!';
        }

        return redirect()->back()->with(['message' => $flashMessage]);
    }

    // Helper Method to Process Payment
    private function processPayment(Request $request, NMIGateway $gw)
    {
        $gw->setLogin(env('NMI_KEY'));

        $cardId = $request->input('cardId');

        if ($cardId) {
            $card = Card::find($cardId);
            // Decrypt and use the card details from the database
            $number = Crypt::decryptString($card->number);
            $month = Crypt::decryptString($card->month);
            $year = Crypt::decryptString($card->year);
        } else {
            // Use the card details from the request
            $number = $request->number;
            $month = $request->month;
            $year = $request->year;
        }

        $gw->setBilling(
            $request->user()->first_name,
            $request->user()->last_name,
            $request->address,
            $request->city,
            $request->state,
            $request->zip,
            'US',
            $request->user()->phone,
            $request->user()->email
        );

        $subtotal = (float)$request->amount;
        $totalWithFee = $subtotal * 1.03;
        $finalAmount = number_format($totalWithFee, 2, '.', '');

        $lastFourDigits = substr($number, -4);  // Extract the last 4 digits of the card number
        $userId = $request->user()->id;
        Log::debug("$userId is attempting to add $finalAmount using a card ending in $lastFourDigits");

        $r = $gw->doSale($finalAmount, $number, $month . substr($year, -2));

        Log::debug('RESPONSE TEXT: ' . $gw->responses['responsetext']);

        return [
            'response' => $gw->responses['responsetext'],
            'subtotal' => $subtotal,
            'processingFee' => $totalWithFee - $subtotal,
            'finalAmount' => $finalAmount
        ];
    }


    private function saveCardDetails(Request $request)
    {
        $cardId = $request->input('cardId');

        if ($cardId) {
            // Return existing card from database
            $card = Card::find($cardId);
            return [$card, 'EXISTING_CARD'];
        }

        $encryptedNumber = Crypt::encryptString($request->number);
        $encryptedMonth = Crypt::encryptString($request->month);
        $encryptedYear = Crypt::encryptString($request->year);
        $encryptedCvv = Crypt::encryptString($request->cvv);

        $isFirstCard = !Card::where('user_id', $request->user()->id)->exists();

        $newCard = Card::create([
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

        return [$newCard, 'NEW_CARD'];
    }

    /**
     * Update the user's balance and log transactions.
     * 
     * @param Request $request The incoming request containing the amount and other details.
     * @param object $card The card object containing details of the card used.
     * @param float $totalWithBonus The amount with a potential bonus (default is false).
     * @return void
     */
    private function updateUserBalanceAndTransaction(Request $request, $card, $totalWithBonus = false)
    {
        // Check if the user is an internal agent for possible bonus
        if ($request->user()->roles->contains('name', 'internal-agent')) {
            $isInternalAgent = true;
            $totalWithBonus = $request->amount * 2; // Double the amount for internal agents
        }

        // Update the user's balance
        $updatedBalance = $request->user()->balance + ($totalWithBonus ? $totalWithBonus : $request->amount);
        $request->user()->update(['balance' => $updatedBalance]);

        // Extract the last 4 digits of the credit card number
        $cardLast4 = substr(Crypt::decryptString($card->number), -4);

        // Log the normal transaction
        Transaction::create([
            'amount' => (float)$request->amount,
            'user_id' => $request->user()->id,
            'sign' => true,
            'card_id' => $card->id,
            'label' => 'Funds added manually with credit card ending in ' . $cardLast4,
            'status_code' => 200,
            'nmi_response' => 'Funds added manually with credit card ending in ' . $cardLast4,
        ]);

        // Log a separate bonus transaction if the user is an internal agent
        if (isset($isInternalAgent)) {
            Transaction::create([
                'amount' => $request->amount,
                'user_id' => $request->user()->id,
                'sign' => true,
                'bonus' => true,
                'card_id' => $card->id,
                'label' => 'Bonus funds',
                'status_code' => 200,
                'nmi_response' => 'Bonus funds',
            ]);
        }
    }
}
