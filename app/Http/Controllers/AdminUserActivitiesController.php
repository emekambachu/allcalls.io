<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminUserActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $userActivities = UserActivity::latest()->with('user')->paginate(100);

        return Inertia::render('Admin/UserActivities/Index', compact('userActivities'));
    }

    public function clearAll()
    {
        UserActivity::truncate();

        return redirect()->back()->with('message', 'All user activities has been cleared.');
    }
}
