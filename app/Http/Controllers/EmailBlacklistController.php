<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailBlacklist;

class EmailBlacklistController extends Controller
{
    public function store(Request $request)
    {
        EmailBlacklist::create([
            'email' => $request->user()->email,
        ]);

        return redirect()->route('take-calls.show')->with(['message' => 'You have been successfully unsubscribed from our mailing list.']);
    }
}
