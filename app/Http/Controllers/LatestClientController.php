<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LatestClientController extends Controller
{
    public function show(Request $request)
    {
        $latestClient = $request->user()->clients()->latest()->first();

        return [
            'client' => $latestClient ?? null
        ];
    }
}
