<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailBlacklist;
use DocuSign\eSign\Model\Email;

class EmailBlacklistController extends Controller
{
    public function store(Request $request)
    {
        dd($request->input('token') ?? 'No token found');


        $exists = EmailBlacklist::where('email', $request->user()->email)->exists();

        if ($exists) {
            return redirect()->route('take-calls.show')->with(['message' => 'You have already unsubscribed from our mailing list.']);
        }

        EmailBlacklist::create([
            'email' => $request->user()->email,
        ]);

        return redirect()->route('take-calls.show')->with(['message' => 'You have been successfully unsubscribed from our mailing list.']);
    }
}
