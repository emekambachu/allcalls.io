<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\State;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $Clients = Client::orderBy('created_at', 'desc')
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
            ->with(['call', 'user', 'call.callType'])
            ->paginate(100);

        $allClients = Client::orderBy('created_at', 'desc')
            ->take(20)->get();

        $totalClients = Client::count();
        $states = State::all();
        return Inertia::render('Admin/Clients/Index', [
            'Clients' => $Clients,
            'allClients' => $allClients,
            'totalClients' => $totalClients,
            'states' => $states,
            'requestData' => $request->all() ? $request->all() : ''
        ]);
    }

    public function getClients(Request $request)
    {
        // dd($request->all(), empty($request->all()));
        $allClients = Client::orderBy('created_at', 'desc')
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
            ->get();
        return response()->json([
            'allClients' => $allClients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Client $client, Request $request)
    {
        $request->validate([
            "first_name" => 'required',
            "last_name" => 'required',
            "phone" => 'required',
            "zipCode" => 'required',
            "email" => 'required',
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
            "unlocked" => $request->unlocked,
            "dob" => $request->dob,
            "status" => $request->status,
            "state" => $request->state,
        ]);

        return redirect()->back()->with([
            'message' => 'Client updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
