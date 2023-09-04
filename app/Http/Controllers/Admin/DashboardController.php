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
        $fromTo = [];
        $previousTo = [];
        $excludeRoles = Role::whereIn('name', ['admin'])->pluck('id');

        if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
            $fromDate = Carbon::parse($request->from);
            $toDate = Carbon::parse($request->to);

            $diffInDays = $fromDate->diffInDays($toDate);

            $subDayKey = Carbon::parse($request->from);
            $previousDays = $subDayKey->subDays($diffInDays);

            $fromTo = [$fromDate->format('y-m-d'), $toDate->format('y-m-d')];
            $previousTo = [$previousDays->format('y-m-d'), $fromDate->format('y-m-d')];

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
        })->where(function ($query) use ($fromTo) {
            if (count($fromTo)) {
                $query->whereBetween('created_at', $fromTo);
            }
        })->count();


        if (isset($previousDaysUsers) && $previousDaysUsers > 0) {
            $userDiffInPercentage = (($totalUserCount - $previousDaysUsers) / $previousDaysUsers) * 100;
        }


        $activeUsersCount = ActiveUser::where(function ($query) use ($fromTo) {
            if (count($fromTo)) {
                $query->whereBetween('created_at', $fromTo);
            }
        })->count();

        if (isset($previousActiveUsers) && $previousActiveUsers > 0) {
            $activeUsersDiffInPercentage = (($activeUsersCount - $previousActiveUsers) / $previousActiveUsers) * 100;
        }


        $totalRevenue = Call::where(function ($query) use ($fromTo) {
            if (count($fromTo)) {
                $query->whereBetween('call_taken', $fromTo);
            }
        })->sum('amount_spent');

        if (isset($previousRevenue) && $previousRevenue > 0) {
            $diffInRevenue = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
        }

        //Graphs
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

        return Inertia::render('Admin/Dashboard', [
            'from' => isset($request->from)?$request->from:'',
            'to' => isset($request->to)?$request->to:'',
            'userDiffInPercentage' => isset($userDiffInPercentage)?$userDiffInPercentage:0,
            'activeUsersDiffInPercentage' => isset($activeUsersDiffInPercentage)?$activeUsersDiffInPercentage:0,
            'diffInRevenue' => isset($diffInRevenue)?$diffInRevenue:0,
            'totalUserCount' => $totalUserCount,
            'activeUsersCount' => $activeUsersCount,
            'totalAmountSpent' => $totalRevenue,
            'spendData' => $spendData,
            'callData' => $callData,
        ]);
    }
}
