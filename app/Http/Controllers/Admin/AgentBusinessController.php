<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentMyBusiness;
use App\Models\State;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class AgentBusinessController extends Controller
{
    public function index(Request $request)
    {
        $businesses = InternalAgentMyBusiness::where(function ($query) use ($request) {
            if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
                $startDate = Carbon::parse($request->from)->startOfDay();
                $endDate = Carbon::parse($request->to)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        })
        ->paginate(10);
        $states = State::get();
        // dd($businesses);
        return Inertia::render('InternalAgent/MyBusiness/Index', [
            'businesses' => $businesses,
            'states' => $states,
            'requestData' =>  $request->all()
        ]);
    }
}
