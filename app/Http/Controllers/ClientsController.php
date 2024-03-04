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
            ->where(function ($query) use ($request) {
                if (isset($request->email) && $request->email != '') {
                    $query->where('email', 'LIKE', '%' . $request->email . '%');
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->phone) && $request->phone != '') {
                    $query->where('phone', 'LIKE', '%' . $request->phone . '%');
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->name) && $request->name != '') {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $request->name . '%']);
                }
            })
            ->with('call')
            ->paginate(100);
        $allClients = Client::where('user_id', $user_id)->where('unlocked', true)->orderBy('created_at', 'desc')
            ->take(100)->get();
        $totalClients = Client::where('user_id', $user_id)->where('unlocked', true)->count();
        $states = State::all();
        return Inertia::render('Clients/Index', [
            'Clients' => $Clients,
            'allClients' => $allClients,
            'totalClients' => $totalClients,
            'states' => $states,
            'requestData' => $request->all() ? $request->all() : ''

        ]);
    }

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
            "first_name" => $request->first_name ?? '',
            "last_name" => $request->last_name ?? '',
            "phone" => $request->phone ?? '',
            "zipCode" => $request->zipCode ?? '',
            "email" => $request->email ?? '',
            "address" => $request->address ?? '',
            "dob" => $request->dob ?? '',
            "status" => $request->status ?? '',
            "state" => $request->state ?? '',
        ]);

        return redirect()->back()->with([
            'message' => 'Client updated successfully.'
        ]);
    }
    public function getClients(Request $request)
    {
        $user_id = $request->user()->id;
        $allClients = Client::orderBy('created_at', 'desc')->where('user_id', $user_id)->where('unlocked', true)
        ->when(isset($request->email) && $request->email != '', function ($query) use ($request) {
            // Conditionally add the email filter if it is set
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        })
        ->when(isset($request->phone) && $request->phone != '', function ($query) use ($request) {
            // Conditionally add the phone filter if it is set
            $query->where('phone', 'LIKE', '%' . $request->phone . '%');
        })
        ->when(isset($request->name) && $request->name != '', function ($query) use ($request) {
            // Conditionally add the name filter if it is set
            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $request->name . '%']);
        })
        ->when(!isset($request->phone) && $request->phone != '' && isset($request->name) && $request->name != '' && isset($request->email) && $request->email != '', function ($query) use ($request) {
            // Conditionally add the phone filter if it is set
            $query->take(1);
        })
         // Always take the first 10 records
        ->get(); // Execute the query and retrieve the results
            return response()->json([
                'allClients' => $allClients
            ]);
    }
}
