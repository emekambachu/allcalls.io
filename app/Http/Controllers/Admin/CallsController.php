<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Bid;
use App\Models\Call;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Client;
use App\Models\Activity;
use App\Models\CallType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CallsController extends Controller
{
    public function index(Request $request)
    {
        $orderColumn = "created_at";
        $orderBy = "DESC";

        if ((isset($request->sortColumn) && $request->sortColumn != '') || (isset($request->sortOrder) && $request->sortOrder != '')) {
            $orderColumn = $request->sortColumn;
            $orderBy = $request->sortOrder;
        }

        $calls = Call::with('user.roles', 'getClient', 'callType')
            ->where(function ($query) use ($request) {
                if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
                    $fromDate = Carbon::parse($request->from)->startOfDay();
                    $toDate = Carbon::parse($request->to)->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->status) && $request->status != '') {
                    if ($request->status == 'paid') {
                        $query->where('call_duration_in_seconds', '>', 60);
                    } else if ($request->status == 'unpaid') {
                        $query->where('call_duration_in_seconds', '<=', 60);
                    }
                }
            })
            ->orderBy($orderColumn, $orderBy)
            ->paginate(50);
        return Inertia::render('Admin/Calls/Index', [
            'requestData' => $request->all(),
            'calls' => $calls
        ]);
    }

    public function indexNew(Request $request)
    {
        $orderColumn = "id";
        $orderBy = "DESC";

        if ((isset($request->sortColumn) && $request->sortColumn != '') || (isset($request->sortOrder) && $request->sortOrder != '')) {
            $orderColumn = $request->sortColumn;
            $orderBy = $request->sortOrder;
        }

        $calls = Call::with('user.roles', 'getClient', 'callType')
            ->where(function ($query) use ($request) {
                if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
                    $fromDate = Carbon::parse($request->from)->startOfDay();
                    $toDate = Carbon::parse($request->to)->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->status) && $request->status != '') {
                    if ($request->status == 'paid') {
                        $query->where('call_duration_in_seconds', '>', 60);
                    } else if ($request->status == 'unpaid') {
                        $query->where('call_duration_in_seconds', '<=', 60);
                    }
                }
            })
            ->orderBy($orderColumn, $orderBy)
            ->paginate(50);


        $allCalls = Call::all();
        $callsGroupedByUser = $allCalls->groupBy(function ($call) {
            return $call->user_id;
        });


        return Inertia::render('Admin/Calls/IndexNew', [
            'requestData' => $request->all(),
            'calls' => $calls,
            'totalCalls' => Call::count(),
            'totalRevenue' => round((float) Call::sum('amount_spent'), 2),
            'callsGroupedByUser' => $callsGroupedByUser,
        ]);
    }
}
