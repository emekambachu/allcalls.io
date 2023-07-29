<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsAPIController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        $clients = Client::whereUserId($request->user()->id)->paginate($perPage);

        return [
            'clients' => $clients
        ];
    }
}