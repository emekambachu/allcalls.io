<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\UserSavedNonNullStatus;

class WebAPIClientsController extends Controller
{
    public function update(Client $client, Request $request)
    {
        // $request->validate([
        //     "first_name" => 'required',
        //     "last_name" => 'required',
        //     "phone" => 'required',
        //     "zipCode" => 'required',
        //     "email" => 'required|email',
        //     "address" => 'required',
        //     "dob" => 'required',
        //     "status" => 'required',
        //     "state" => 'required',
        // ]);

        $client->update([
            "first_name" => $request->first_name ?? $client->first_name,
            "last_name" => $request->last_name ?? $client->last_name,
            "phone" => $request->phone ?? $client->phone,
            "zipCode" => $request->zipCode ?? $client->zipCode,
            "email" => $request->email ?? $client->email,
            "address" => $request->address ?? $client->address,
            "dob" => $request->dob ?? $client->dob,
            "status" => $request->status ?? null,
            "state" => $request->state ?? $client->state,
        ]);

        $client = $client->refresh();

        Log::debug('web-clients-api: updated');
        if ($client->status !== null) {
            Log::debug('web-clients-api: status is not null');
            UserSavedNonNullStatus::dispatch($client->user, $client->status);
        }

        // return a json response
        return response()->json([
            'message' => 'Client updated successfully.',
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

        UserSavedNonNullStatus::dispatch($client->user, $client->status);

        // return a json response
        return response()->json([
            'message' => 'Client disposition updated successfully.',
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'status' => $client->status,
        ]);
    }
}
