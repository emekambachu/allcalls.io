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

        Bid::getForUserAndCallType($request->user(), $callType)
            ->update(['amount' => $request->amount]);

        return redirect()->back()->with(['message' => 'Bid updated successfully for vertical ' . $callType->type]);
    }
}
