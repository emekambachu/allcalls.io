<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsAPIController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $clients = Client::where('user_id', $user_id)->with('callType')->paginate(10);

        $totalCalls = Client::where('user_id', $user_id)->count();
    
        $totalAmountSpent = Client::where('user_id', $user_id)->sum('amount_spent');
    
        $averageCallDuration = Client::where('user_id', $user_id)->average('call_duration_in_seconds');

        return [
            'clients' => $clients,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
        ];
    }
}