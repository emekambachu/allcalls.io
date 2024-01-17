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
            ->paginate(100);

        $allClients = Client::orderBy('created_at', 'desc')
            ->get();
        $totalClients = Client::count();
        $states = State::all();
        return Inertia::render('Admin/Clients/Index', [
            'Clients' => $Clients,
            'allClients' => $allClients,
            'totalClients' => $totalClients,
            'states' => $states,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
