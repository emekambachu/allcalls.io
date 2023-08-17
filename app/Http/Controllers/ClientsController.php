<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        // $clients = Client::where('user_id', $user_id)->paginate(10);
        $clients = Client::where('user_id', $user_id)
                ->whereHas('call')
                ->with('call.callType')->paginate(10);


        $totalCalls = Client::where('user_id', $user_id)
                ->whereHas('call')
                ->count();

        $totalAmountSpent = Client::where('clients.user_id', $user_id)
                                ->join('calls','calls.id','=','clients.call_id')
                                ->sum('amount_spent');

        $averageCallDuration = Client::where('clients.user_id', $user_id)
                                ->join('calls','calls.id','=','clients.call_id')
                                ->average('call_duration_in_seconds');

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
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
            'message' => 'Client updated.'
        ]);
    }
}
