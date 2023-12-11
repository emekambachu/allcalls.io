<?php

namespace App\Http\Controllers\Admin;

use App\Events\OnlineUserListUpdated;
use Inertia\Inertia;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\User;
use App\Models\Role;
use App\Models\CallType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OnlineAgentsController extends Controller
{
    public function index(Request $request)
    {
        $onlineUsers = OnlineUser::with(['user.states', 'user.latestActivity', 'callType'])
            ->when(isset($request->state_filteration) && count($request->state_filteration), function ($query) use ($request) {
                $query->whereHas('user.states', function ($query) use ($request) {
                    $query->whereIn('states.id', $request->state_filteration);
                });
            })
            ->orderBy("created_at", "DESC")
            ->get();

        $onlineStats = State::whereHas('users.onlineUser')->withCount(['users as user_count' => function ($query) {
            $query->whereHas('onlineUser')->select(DB::raw('count(*)'));
        }])->get();

        // dd($onlineStats);
        $filters = $request->all();
        return Inertia::render('Admin/OnlineAgents/Index', compact('onlineUsers', 'onlineStats', 'filters'));
    }

    public function destroy($userId)
    {
        OnlineUser::whereUserId($userId)->delete();

        OnlineUserListUpdated::dispatch();

        return redirect()->back()->with([
            'message' => 'Agent removed from online list.'
        ]);
    }
}
