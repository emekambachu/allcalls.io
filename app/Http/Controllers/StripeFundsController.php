<?php

namespace App\Http\Controllers;

use App\Events\FundsAdded;
use App\Models\Transaction;
use Exception;
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
        $subtotal = (float)$request->amount;
        $processingFee = $subtotal * 0.03;
        $totalWithFee = $subtotal + $processingFee;

        $finalAmount = number_format($totalWithFee, 2, '.', '');

        DB::beginTransaction();

        try {
            // Step 3: Create Charge
            $totalWithFeeInCents = round($totalWithFee * 100);
            $request->user()->charge($totalWithFeeInCents, $request->paymentMethodId);
            $totalWithBonus = false;

            if ($request->user()->roles->contains('name', 'internal-agent')) {
                $isInternalAgent = true;
                $totalWithBonus = $subtotal * 2;
            }

            // Step 4: Update user's balance
            $updatedBalance = $request->user()->balance + ($totalWithBonus ? $totalWithBonus : $subtotal);
            $request->user()->update(['balance' => $updatedBalance]);

            // Step 5: Log the transaction in the database
            Transaction::create([
                'amount' => $subtotal,
                'user_id' => $request->user()->id,
                'sign' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (isset($isInternalAgent)) {
                Transaction::create(
                    [
                        'amount' => $subtotal,
                        'user_id' => $request->user()->id,
                        'sign' => true,
                        'bonus' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();

            if (isset($isInternalAgent)) {
                $returnMsg = [
                    'message' => 'Payment successful.',
                    'bonus' => 'Added $' . $subtotal . ' bonus to the account',
                ];
            } else {
                $returnMsg = [
                    'message' => 'Payment successful.'
                ];
            }


            FundsAdded::dispatch($request->user(), $subtotal, $processingFee, $finalAmount, $totalWithBonus ? $subtotal : 0);

            return redirect()->back()->with($returnMsg);
        } catch (Exception $e) {
            DB::rollback();
            // Log the error for debugging
            Log::error('Payment failed: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Payment failed.']);
        }
    }

}
