<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Client;
use App\Models\Call;
use App\Models\State;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $calls = Call::where('user_id', $user_id)
                        ->with(['getClient', 'callType'])
                        ->orderBy('id', 'desc')
                        ->paginate(10);
        
        $totalCalls = Call::where('user_id', $user_id)->count();

        
        $totalAmountSpent = Call::where('user_id', $user_id)
                                    ->sum('amount_spent');

        $averageCallDuration = Call::where('user_id', $user_id)
        ->average('call_duration_in_seconds');
        $states = State::all();
        return Inertia::render('Calls/Index', [
            'calls' => $calls,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
            'states' => $states,
        ]);
    }

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

        return redirect()->back()->with([
            'message' => 'Client updated successfully.'
        ]);
    }
}
