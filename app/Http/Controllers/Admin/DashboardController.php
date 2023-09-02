<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveUser;
use App\Models\Call;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        // dd($request);
        $totalUserCount = User::count();
        $activeUsersCount = ActiveUser::count();
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $spendData = Call::select(DB::raw('date(call_taken) as date'), DB::raw('sum(amount_spent) as sum'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $callData = Call::select(DB::raw('date(call_taken) as date'), DB::raw('count(*) as count'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalCalls = Call::where('call_taken', '>=', $sevenDaysAgo)
            ->count();


        $totalAmountSpent = Call::where('call_taken', '>=', $sevenDaysAgo)
            ->sum('amount_spent');

        $averageCallDuration = Call::where('call_taken', '>=', $sevenDaysAgo)
            ->average('call_duration_in_seconds');

        return Inertia::render('Admin/Dashboard', [
            'totalUserCount' => $totalUserCount,
            'activeUsersCount' => $activeUsersCount,
            'spendData' => $spendData,
            'callData' => $callData,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
        ]);
    }
}
