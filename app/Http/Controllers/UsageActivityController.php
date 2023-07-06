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
        if (Gate::allows('view-activities')) {
            $activities = Activity::with('user')->get();
        } 
        else {            
            $user = auth()->user();
            $activities = $user->activities()->with('user')->get();
        }
        
        return Inertia::render('Activities/Index', compact('activities'));
    }
}
