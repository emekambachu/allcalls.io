<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailBlacklist;
use DocuSign\eSign\Model\Email;
use App\Models\UnsubscribeToken;

class EmailBlacklistController extends Controller
{
    public function store(Request $request, $token)
    {
        dd($token ?? 'No token found');

        $user = UnsubscribeToken::where('token', $token)->firstOrFail()->user;

        if (!$user) {
            return redirect()->route('take-calls.show')->with(['message' => 'Invalid unsubscribe token.']);
        }

        return view('unsubscribe-email');
    }
}
