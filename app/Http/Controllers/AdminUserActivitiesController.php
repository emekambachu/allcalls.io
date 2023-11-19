<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminUserActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $userActivities = UserActivity::latest()->with('user')->paginate(10);

        return Inertia::render('Admin/UserActivities/Index', compact('userActivities'));
    }
}
