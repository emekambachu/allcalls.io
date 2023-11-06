<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MissedCall extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'You missed a call from a client.'
        ];
    }


    /**
     * Send the array representation of the notification to a custom API.
     *
     * @param object $notifiable
     * @return void
     */
    public function toPushNotification(object $notifiable)
    {
        // Assuming $notifiable->device_token is where the device token is stored
        $deviceToken = $notifiable->device_token;
        
        $notificationData = $this->toArray($notifiable);
        
        $serverKey = env('PUSH_TEST_SERVER_KEY');

        $notification = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $notificationData['title'],
                'body' => $notificationData['message'],
            ],
            'android' => [
                'direct_boot_ok' => true,
            ],
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init('https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            Log::error('Error sending push notification: ' . curl_error($ch));
        } else {
            Log::info('Push notification sent: ' . $response);
        }

        curl_close($ch);
    }
}
