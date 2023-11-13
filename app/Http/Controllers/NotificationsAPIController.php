<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationsAPIController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications;

        return response()->json($notifications);
    }

    public function markNotificationAsRead($notificationId, Request $request)
    {
        Log::debug('NotificationsAPIController::markNotificationAsRead');

        if ($request->user()->notifications->filter(function ($notification) use ($notificationId) {
            return $notification->id == $notificationId;
        })->count() == 0) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        Log::debug('NotificationsAPIController::markNotificationAsRead: found notification');

        $notification = $request->user()->notifications->find($notificationId);

        $notification->markAsRead();

        Log::debug('NotificationsAPIController::markNotificationAsRead: marked as read');

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $user = $request->user();

        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function destroy($notificationId, Request $request)
    {
        if ($request->user()->notifications->filter(function ($notification) use ($notificationId) {
            return $notification->id == $notificationId;
        })->count() == 0) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification = $request->user()->notifications->find($notificationId);

        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function destroyAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return response()->json(['success' => true]);
    }
}
