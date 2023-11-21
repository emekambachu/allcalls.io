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
        // dd($request->all());
        $calls = Call::with('user.roles','getClient','callType')
        ->where(function($query) use ($request) {
            if(isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
                $fromDate = Carbon::parse($request->from)->startOfDay();
                $toDate = Carbon::parse($request->to)->endOfDay();
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
        })
        ->where(function($query) use ($request) {
            if((isset($request->sortColumn) && $request->sortColumn != '') || (isset($request->sortOrder) && $request->sortOrder != '')) {
                $query->orderBy('call_duration_in_seconds', 'ASC');
            }
            else {
                $query->orderBy("created_at","DESC");
            }
        })
        ->paginate(50);
        // dd($calls);
        return Inertia::render('Admin/Calls/Index', [
            'requestData' => $request->all(),
            'calls' => $calls
        ]);
    }
}
