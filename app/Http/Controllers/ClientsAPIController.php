<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsAPIController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        $user_id = $request->user()->id;

        $clients = Client::whereUserId($user_id)->paginate($perPage);

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