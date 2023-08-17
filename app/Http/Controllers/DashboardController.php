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
            ->join('calls','calls.id','=','clients.call_id')
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->where('clients.user_id', $request->user()->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $callData = Client::select(DB::raw('date(call_taken) as date'), DB::raw('count(*) as count'))
            ->join('calls','calls.id','=','clients.call_id')
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->where('clients.user_id', $request->user()->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalCalls = Client::where('call_taken', '>=', $sevenDaysAgo)
            ->join('calls','calls.id','=','clients.call_id')
            ->where('clients.user_id', $request->user()->id)
            ->count();


        $totalAmountSpent = Client::where('call_taken', '>=', $sevenDaysAgo)
            ->join('calls','calls.id','=','clients.call_id')
            ->where('clients.user_id', $request->user()->id)
            ->sum('amount_spent');

        $averageCallDuration = Client::where('call_taken', '>=', $sevenDaysAgo)
            ->join('calls','calls.id','=','clients.call_id')
            ->where('clients.user_id', $request->user()->id)
            ->average('call_duration_in_seconds');

        return Inertia::render('Dashboard', [
            'spendData' => $spendData,
            'callData' => $callData,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
        ]);
    }
}
