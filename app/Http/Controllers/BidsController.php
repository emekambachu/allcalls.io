<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\CallType;
use Illuminate\Http\Request;

class BidsController extends Controller
{
    public function update(Request $request)
    {
        if ( sizeof($request->bids) !== CallType::count()) {
            return abort(400);
        }

        foreach ( $request->bids as $bid ) {
            $savedBid = Bid::whereUserId($request->user()->id)->whereId($bid['bid_id'])->firstOrFail();

            $savedBid->update([
                'amount' => $bid['bid_amount']
            ]);
        }

        return back();
    }
}
