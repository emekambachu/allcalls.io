<?php
 
namespace App\Notifications;
 
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
 
class PushChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $data = $notification->toPush($notifiable);
        Log::debug('Notification Data To Send:');
        Log::debug($data);
    }
}