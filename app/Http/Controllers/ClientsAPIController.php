<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\UserSavedNonNullStatus;

class ClientsAPIController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $clients = Client::where('user_id', $user_id)
            ->where('unlocked', 1)  // Added this line to filter unlocked clients
            ->whereHas('call')
            ->with('call.callType')
            ->orderBy('id', 'desc')  // Sort by 'id' in descending order
            ->paginate(10);


        $totalCalls = Client::where('user_id', $user_id)
            ->whereHas('call')
            ->count();

        $totalAmountSpent = Client::where('clients.user_id', $user_id)
            ->join('calls', 'calls.id', '=', 'clients.call_id')
            ->sum('amount_spent');

        $averageCallDuration = Client::where('clients.user_id', $user_id)
            ->join('calls', 'calls.id', '=', 'clients.call_id')
            ->average('call_duration_in_seconds');

        return [
            'clients' => $clients,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
        ];
    }


    public function update(Client $client, Request $request)
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $updated = $client->update([
            'first_name' => $request->first_name ?? $client->first_name,
            'last_name' => $request->last_name ?? $client->last_name,
            'phone' => $request->phone ?? $client->phone,
            'zipCode' => $request->zipCode ?? $client->zipCode,
            'email' => $request->email ?? $client->email,
            'address' => $request->address ?? $client->address,
            'dob' => $request->dob ?? $client->dob,
            'status' => $request->status ?? null,
            'state' => $request->state ?? $client->state,
            'beneficiary' => $request->beneficiary ?? $client->beneficiary,
        ]);

        $client = $client->refresh();

        Log::debug('clients-api: updated');
        if ($client->status !== null) {
            Log::debug('clients-api: status is not null');
            UserSavedNonNullStatus::dispatch($client->user, $client->status);
        }

        if ($updated) {
            return response()->json(['client' => $client, 'message' => 'Client updated successfully'], 200);
        } else {
            return response()->json(['message' => 'No changes were made'], 200);
        }
    }
}
