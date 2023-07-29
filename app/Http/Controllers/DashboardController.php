<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $spendData = Client::select(DB::raw('date(call_taken) as date'), DB::raw('sum(amount_spent) as sum'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return Inertia::render('Dashboard', [
            'spendData' => $spendData
        ]);
    }
}
