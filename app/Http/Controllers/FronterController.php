<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FronterController extends Controller
{
    public function store(Request $request)
    {
        // First of all, get the user_id from the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // To make a user a fronter, first fetch it:
        $user = User::findOrFail($request->user_id);


        // Next update their call type:
    }
}
