<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activity;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


class UsageActivityController extends Controller
{
    public function index() 
    {
        if (Gate::allows('view-activities')) {
            $query = UserActivity::with('user')->orderBy('created_at', 'desc');

            $activities = $query->paginate(100); 
        } 
        else {     
            $user = Auth::user();
            // $query = $user->activities()->with('user')->orderBy('created_at', 'desc');
            $activities = UserActivity::whereUserId($user->id)->orderBy('created_at', 'desc')->with('user')->paginate(100);
            // $activities = $query->paginate(100);
        }

        return Inertia::render('Activities/Index', compact('activities'));
    }
}
