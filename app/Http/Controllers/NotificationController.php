<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        $request->user()->refresh();

        return $request->user()->notifications;
    }

    public function clearAll(Request $request)
    {
        $request->user()->notifications()->delete();

        $request->user()->refresh();

        return [];
    }
}
