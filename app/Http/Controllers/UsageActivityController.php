<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsageActivityController extends Controller
{
    public function index() 
    {
        // $activities = Activity::with('user')->get();
        // // dd($users->toArray());
        $user = auth()->user();
        $activities = $user->activities()->with('user')->get();
        
        return Inertia::render('Activities/Index', compact('activities'));
    }
}
