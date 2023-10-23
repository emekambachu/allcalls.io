<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\CallType;
use Illuminate\Http\Request;

class CallTypeBidsController extends Controller
{
    public function update(CallType $callType, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:35'
        ]);

        $bid = Bid::getForUserAndCallType($request->user(), $callType);

        if ($bid) {
            $bid->update(['amount' => $request->amount]);
        } else {
            // Create a new bid
            Bid::create([
                'user_id' => $request->user()->id,
                'call_type_id' => $callType->id,
                'amount' => $request->amount
            ]);
        }

        return redirect()->back()->with(['message' => 'Bid updated successfully for vertical ' . $callType->type]);
    }
}
