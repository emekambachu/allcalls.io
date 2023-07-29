<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $spendData = Client::select(DB::raw('date(call_taken) as date'), DB::raw('sum(amount_spent) as sum'))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        return Inertia::render('Dashboard', [
            'spendData' => $spendData
        ]);
    }
}
