<?php

namespace App\Http\Controllers;

use Exception;
use App\Events\FundsAdded;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeFundsController extends Controller
{
    public function store(Request $request)
    {
        // Step 1: Validate incoming request data
        $request->validate([
            'amount' => 'required|numeric|integer|min:1',
            'paymentMethodId' => 'required'
        ]);

        // Step 2: Calculate final payment amount
        $subtotal = (float) $request->amount;
        $processingFee = $subtotal * 0.03;
        $totalWithFee = $subtotal + $processingFee;
        $finalAmount = number_format($totalWithFee, 2, '.', '');


        DB::beginTransaction();

        try {
            // Step 3: Create Charge
            $totalWithFeeInCents = round($totalWithFee * 100);
            $request->user()->charge($totalWithFeeInCents, $request->paymentMethodId);
    
            // Step 4: Update user's balance
            $updatedBalance = $request->user()->balance + $subtotal;
            $request->user()->update(['balance' => $updatedBalance]);
    
            // Step 5: Log the transaction in the database
            Transaction::create([
                'amount' => $subtotal,
                'user_id' => $request->user()->id,
                'sign' => true,
            ]);
    
            DB::commit();

            FundsAdded::dispatch($request->user(), $subtotal, $totalWithFee);
    
            return redirect()->back()->with(['message' => 'Payment successful.']);
        } catch (Exception $e) {
            DB::rollback();
            // Log the error for debugging
            Log::error('Payment failed: ' . $e->getMessage());
    
            return redirect()->back()->with(['error' => 'Payment failed.']);
        }
    }

}
