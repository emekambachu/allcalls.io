<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class DefaultCardController extends Controller
{
    public function update(Card $card)
    {
        $card->setAsDefault();

        return back()->with([
            'message' => 'Updated default card.'
        ]);
    }
}
