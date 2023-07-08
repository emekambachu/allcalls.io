<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Activity;
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
            $query = Activity::with('user')->orderBy('created_at', 'desc');

            $activities = $query->paginate(10); 
        } 
        else {            
            $user = Auth::user();
            $query = $user->activities()->with('user')->orderBy('created_at', 'desc');

            $activities = $query->paginate(10);
        }

        return Inertia::render('Activities/Index', compact('activities'));
    }
}
