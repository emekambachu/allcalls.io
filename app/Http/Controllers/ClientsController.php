<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::where('user_id', $request->user()->id)->paginate(10);

        dd($clients->toArray());
    
        return Inertia::render('Clients/Index', compact('clients'));
    }
    
}
