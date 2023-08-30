<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ActiveUser;
use Illuminate\Http\Request;
use App\Events\ActiveUserStatusUpdated;

class ActiveUsersController extends Controller
{
    public function index()
    {
        $activeUsers = ActiveUser::all();

        return Inertia::render('ActiveUsers/Index', compact('activeUsers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:Waiting,Ringing,Talking',
        ]);

        // $userId = $request->user()->id;
        $userId = 11;

        // $activeUser = ActiveUser::whereUserId($request->user()->id)->first();
        $activeUser = ActiveUser::whereUserId($userId)->first();

        if (!$activeUser) {
            $activeUser = ActiveUser::create([
                'user_id' => $userId,
                'status' => $request->status ?? 'Listening',
            ]);
        }

        ActiveUserStatusUpdated::dispatch($activeUser);

        return [
            'message' => 'Status updated successfully.'
        ];
    }
}
