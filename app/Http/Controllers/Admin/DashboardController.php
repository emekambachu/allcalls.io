<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveUser;
use App\Models\Call;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
         // Change the format here
        $fromTo = [];
        $previousTo = [];
        $excludeRoles = Role::whereIn('name', ['admin'])->pluck('id');
        $sevenDaysAgo = Carbon::now()->subDays(7);

        if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
            $fromDate = Carbon::parse($request->from);
            $toDate = Carbon::parse($request->to);

            // Check if the two dates are not the same day
            if (!$fromDate->isSameDay($toDate)) {
                $diffInDays = $fromDate->diffInDays($toDate) + 1;
            } else {
                $diffInDays = 1; // The two dates are the same day
            }

            $subDayKey = Carbon::parse($request->from);

            $previousDays = $subDayKey->subDays($diffInDays);

            $fromTo = [$fromDate->format('Y-m-d 00:00:00'), $toDate->format('Y-m-d 23:59:59')];

            if( $fromDate->format('m') == $toDate->format('m') && $fromDate->format('d') == '01' && $toDate->format('Y-m-d') == $toDate->format('Y-m-t')){
                $previousTo = [$fromDate->subMonth()->format('Y-m-d 00:00:00'), $fromDate->format('Y-m-t 23:59:59')];
            }else{
                $previousTo = [$previousDays->format('Y-m-d 00:00:00'), $fromDate->subDay()->format('Y-m-d 23:59:59')];
            }

            $previousDaysUsers = User::whereDoesntHave('roles', function ($query) use ($excludeRoles) {
                if (count($excludeRoles)) {
                    $query->whereIn('role_id', $excludeRoles);
                }
            })->where(function ($query) use ($previousTo) {
                if (count($previousTo)) {
                    $query->whereBetween('created_at', $previousTo);
                }
            })->count();

            $previousActiveUsers = ActiveUser::where(function ($query) use ($previousTo) {
                if (count($previousTo)) {
                    $query->whereBetween('created_at', $previousTo);
                }
            })->count();

            $previousRevenue = Call::where(function ($query) use ($previousTo) {
                if (count($previousTo)) {
                    $query->whereBetween('call_taken', $previousTo);
                }
            })->count();

        }

        $totalUserCount = User::whereDoesntHave('roles', function ($query) use ($excludeRoles) {
            if (count($excludeRoles)) {
                $query->whereIn('role_id', $excludeRoles);
            }
        })->where(function ($query) use ($fromTo, $sevenDaysAgo) {
            if (count($fromTo)) {
                $query->whereBetween('created_at', $fromTo);
            } else {
                $query->where('created_at', '>=', $sevenDaysAgo);
            }
        })->count();


        if (isset($previousDaysUsers) && $previousDaysUsers > 0) {
            $userDiffInPercentage = (($totalUserCount - $previousDaysUsers) / $previousDaysUsers) * 100;
        }


        $activeUsersCount = ActiveUser::where(function ($query) use ($fromTo, $sevenDaysAgo) {
            if (count($fromTo)) {
                $query->whereBetween('created_at', $fromTo);
            } else {
                $query->where('created_at', '>=', $sevenDaysAgo);
            }
        })->count();

        if (isset($previousActiveUsers) && $previousActiveUsers > 0) {
            $activeUsersDiffInPercentage = (($activeUsersCount - $previousActiveUsers) / $previousActiveUsers) * 100;
        }

        $totalRevenue = Call::where(function ($query) use ($fromTo, $sevenDaysAgo) {
            if (count($fromTo)) {
                $query->whereBetween('call_taken', $fromTo);
            } else {
                $query->where('call_taken', '>=', $sevenDaysAgo);
            }
        })->sum('amount_spent');

        if (isset($previousRevenue) && $previousRevenue > 0) {
            $diffInRevenue = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
        }

        //Graphs
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
        return Inertia::render('Admin/Dashboard', [
            'from' => isset($request->from) ? $request->from : '',
            'to' => isset($request->to) ? $request->to : '',
            'userDiffInPercentage' => isset($userDiffInPercentage) ? $userDiffInPercentage : 0,
            'activeUsersDiffInPercentage' => isset($activeUsersDiffInPercentage) ? $activeUsersDiffInPercentage : 0,
            'diffInRevenuePercentage' => isset($diffInRevenue) ? $diffInRevenue : 0,
            'totalUserCount' => $totalUserCount,
            'activeUsersCount' => $activeUsersCount,
            'totalAmountSpent' => $totalRevenue,
            'spendData' => $spendData,
            'callData' => $callData,
        ]);
    }
}
