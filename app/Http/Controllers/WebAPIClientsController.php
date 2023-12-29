<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class WebAPIClientsController extends Controller
{
    public function update(Client $client, Request $request)
    {
        $request->validate([
            "first_name" => 'required',
            "last_name" => 'required',
            "phone" => 'required',
            "zipCode" => 'required',
            "email" => 'required|email',
            "address" => 'required',
            "dob" => 'required',
            "status" => 'required',
            "state" => 'required',
        ]);

        $client->update([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "phone" => $request->phone,
            "zipCode" => $request->zipCode,
            "email" => $request->email,
            "address" => $request->address,
            "dob" => $request->dob,
            "status" => $request->status,
            "state" => $request->state,
        ]);

        // return a json response
        return response()->json([
            'message' => 'Client updated successfully.'
        ]);
    }

    public function updateDispositionOnly(Client $client, Request $request)
    {
        $request->validate([
            "status" => 'required',
        ]);

        if ($client->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to update this client.'
            ], 403);
        }   

        $client->update([
            "status" => $request->status,
        ]);

        // return a json response
        return response()->json([
            'message' => 'Client disposition updated successfully.'
        ]);
    }
}
