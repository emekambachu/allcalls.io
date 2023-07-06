<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class UsageActivityController extends Controller
{
    public function index() 
    {
        // $user = auth()->user();
        // $activities = Activity::with('user')->get();
        // // dd($users->toArray());
        
        // $activities = $user->activities()->with('user')->get();
        // dd(Gate::allows('view-activities'));
        if (Gate::allows('view-activities')) {
            // $activities = Activity::all();
            // dd($activities);
            $activities = Activity::with('user')->get();
            // dd($users->toArray());
            // return Inertia::render('Activities/Index', compact('activities'));
        } 
        else {
            $activities = $user->activities()->with('user')->get();
            // abort(403);
        }
        
        return Inertia::render('Activities/Index', compact('activities'));
        // return Inertia::render('Activities/Index', compact('activities'));
        // return Inertia::render('Activities/Index', compact('activities'));
    }
}
