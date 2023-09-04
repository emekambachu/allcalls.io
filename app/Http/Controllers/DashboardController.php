<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $fromTo = [];
        $previousTo = [];

        if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
            $fromDate = Carbon::parse($request->from);
            $toDate = Carbon::parse($request->to);

            $diffInDays = $fromDate->diffInDays($toDate);

            $subDayKey = Carbon::parse($request->from);
            $previousDays = $subDayKey->subDays($diffInDays);

            $fromTo = [$fromDate->format('y-m-d'), $toDate->format('y-m-d')];
            $previousTo = [$previousDays->format('y-m-d'), $fromDate->format('y-m-d')];


            $previousTotalCalls = Call::where(function ($query) use ($previousTo) {
                if (count($previousTo)) {
                    $query->whereBetween('call_taken', $previousTo);
                }
            })->where('user_id', $request->user()->id)
                ->count();

            $previousTotalAmountSpent = Call::where(function ($query) use ($previousTo) {
                if (count($previousTo)) {
                    $query->whereBetween('call_taken', $previousTo);
                }
            })->where('user_id', $request->user()->id)
                ->sum('amount_spent');


            $previousAverageCallDuration = Call::where(function ($query) use ($previousTo) {
                if (count($previousTo)) {
                    $query->whereBetween('call_taken', $previousTo);
                }
            })->where('user_id', $request->user()->id)
                ->average('call_duration_in_seconds');
        }


        $totalCalls = Call::where(function ($query) use ($fromTo, $sevenDaysAgo) {
            if (count($fromTo)) {
                $query->whereBetween('call_taken', $fromTo);
            } else {
                $query->where('call_taken', '>=', $sevenDaysAgo);
            }
        })
            ->where('user_id', $request->user()->id)
            ->count();

        if (isset($previousTotalCalls) && $previousTotalCalls > 0) {
            $callDiffInPercentage = (($totalCalls - $previousTotalCalls) / $previousTotalCalls) * 100;
        }



        $totalAmountSpent = Call::where(function ($query) use ($fromTo, $sevenDaysAgo) {
            if (count($fromTo)) {
                $query->whereBetween('call_taken', $fromTo);
            } else {
                $query->where('call_taken', '>=', $sevenDaysAgo);
            }
        })
            ->where('user_id', $request->user()->id)
            ->sum('amount_spent');

        if (isset($previousTotalAmountSpent) && $previousTotalAmountSpent > 0) {
            $totalAmountSpentPercentage = (($totalAmountSpent - $previousTotalAmountSpent) / $previousTotalAmountSpent) * 100;
        }


        $averageCallDuration = Call::where(function ($query) use ($fromTo, $sevenDaysAgo) {
            if (count($fromTo)) {
                $query->whereBetween('call_taken', $fromTo);
            } else {
                $query->where('call_taken', '>=', $sevenDaysAgo);
            }
        })
            ->where('user_id', $request->user()->id)
            ->average('call_duration_in_seconds');
        if (isset($previousAverageCallDuration) && $previousAverageCallDuration > 0) {
            $averageCallDurationPercentage = (($averageCallDuration - $previousAverageCallDuration) / $previousAverageCallDuration) * 100;
        }



        $spendData = Call::select(DB::raw('date(call_taken) as date'), DB::raw('sum(amount_spent) as sum'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $callData = Call::select(DB::raw('date(call_taken) as date'), DB::raw('count(*) as count'))
            ->where('call_taken', '>=', $sevenDaysAgo)
            ->where('user_id', $request->user()->id)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return Inertia::render('Dashboard', [
            'from' => isset($request->from) ? $request->from : '',
            'to' => isset($request->to) ? $request->to : '',
            'callDiffInPercentage' => isset($callDiffInPercentage) ? $callDiffInPercentage : 0,
            'totalAmountSpentPercentage' => isset($totalAmountSpentPercentage) ? $totalAmountSpentPercentage : 0,
            'averageCallDurationPercentage' => isset($averageCallDurationPercentage) ? $averageCallDurationPercentage : 0,
            'spendData' => $spendData,
            'callData' => $callData,
            'totalCalls' => $totalCalls,
            'totalAmountSpent' => $totalAmountSpent,
            'averageCallDuration' => $averageCallDuration,
        ]);
    }

    public function DestopUser(Request $request)
    {

        return Inertia::render('DestopDevice/DestopDevice', [

        ]);
    }
}
