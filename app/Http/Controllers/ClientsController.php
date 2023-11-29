<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Call;
use App\Models\State;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        
        $user_id = $request->user()->id;
        
        $Clients = Client::where('user_id', $user_id)
                        ->where('unlocked', true)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        $totalClients = Client::where('user_id', $user_id)->count();
        $states = State::all();
        return Inertia::render('Clients/Index', [
            'Clients' => $Clients,
            'totalClients' => $totalClients,
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
