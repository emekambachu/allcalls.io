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
                        ->orderBy('created_at', 'desc')
                        ->paginate(100);
    
        // Iterate over each call and modify the 'from' field based on call_duration_in_seconds
        $calls->getCollection()->transform(function ($call) {
            if ($call->call_duration_in_seconds > 60) {
                $call->from = $call->from; // This is redundant but illustrates that you can modify the value here
            } else {
                $call->from = "-"; // Replace 'from' with "-" when call_duration_in_seconds <= 60
            }
            return $call;
        });
    
        $totalCalls = Call::where('user_id', $user_id)->count();
        $totalAmountSpent = Call::where('user_id', $user_id)->sum('amount_spent');
        $averageCallDuration = Call::where('user_id', $user_id)->average('call_duration_in_seconds');
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
