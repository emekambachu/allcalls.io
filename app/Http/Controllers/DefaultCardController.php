<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class DefaultCardController extends Controller
{
    public function update(Card $card, Request $request)
    {
        if ($request->user()->id !== $card->user_id) {
            return abort(401);
        }

        $card->setAsDefault();

        return back()->with([
            'message' => 'Updated default card.'
        ]);
    }
}
