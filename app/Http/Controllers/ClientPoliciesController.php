<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientPoliciesController extends Controller
{
    public function index(Request $request, Client $client)
    {
        if ($request->user()->id !== $client->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'policies' => $client->policies,
        ]);
    }
}
