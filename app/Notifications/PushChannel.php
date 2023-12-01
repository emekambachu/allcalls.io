<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;

// class PushChannel
// {
//     /**
//      * Send the given notification.
//      */
//     public function send(object $notifiable, Notification $notification): void
//     {
//         $data = $notification->toPush($notifiable);
//         Log::debug('Notification Data To Send:');
//         Log::debug($data);

//         // Assuming $notifiable->device_token is where the device token is stored
//         $deviceTokens = $notifiable->devices->pluck('fcm_token');

//         Log::debug('Tokens:');
//         Log::debug($deviceTokens);

//         foreach ($deviceTokens as $token) {
//             $serverKey = env('PUSH_TEST_SERVER_KEY');

//             $notification = [
//                 'to' => $token,
//                 // 'notification' => [
//                 //     'title' => $data['title'],
//                 //     'body' => $data['body'],
//                 // ],
//                 'data' => [ // Add custom data payload for Zoom link
//                     'zoomLink' => $data['zoomLink'] ?? null,
//                     'title' => $data['title'],
//                     'body' => $data['body'],
//                 ],
//                 'priority' => 'high',  // Set priority to high for urgent notifications
//                 'android' => [
//                     'direct_boot_ok' => true,
//                     'priority' => 'high', // Also set Android-specific priority
//                 ],
//             ];

//             $headers = [
//                 'Authorization: key=' . $serverKey,
//                 'Content-Type: application/json',
//             ];

//             $ch = curl_init('https://fcm.googleapis.com/fcm/send');
//             curl_setopt($ch, CURLOPT_POST, true);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
//             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//             $response = curl_exec($ch);

//             if ($response === false) {
//                 Log::debug('Error sending push notification: ' . curl_error($ch));
//             } else {
//                 Log::debug('Push notification sent: ' . $response);
//             }

//             curl_close($ch);
//         }
//     }
// }


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

        foreach ($notifiable->devices as $device) {
            $serverKey = env('PUSH_TEST_SERVER_KEY');

            // Check the device type and construct the payload accordingly
            if (strpos(strtolower($device->device_type), 'android') !== false) {
                // Android device, send data-only payload
                $payload = [
                    'to' => $device->fcm_token,
                    'data' => [
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'zoomLink' => $data['zoomLink'] ?? null,
                    ],
                    'android' => [
                        'direct_boot_ok' => true,
                        'priority' => 'high', // Optional, set priority as high
                    ],
                ];
            } else {
                // iOS or other device, send notification payload
                $payload = [
                    'to' => $device->fcm_token,
                    'notification' => [
                        'title' => $data['title'],
                        'body' => $data['body'],
                    ],
                    'data' => [
                        'zoomLink' => $data['zoomLink'] ?? null,
                    ],
                ];
            }

            // Send the notification via FCM
            $this->sendToFcm($payload, $serverKey);
        }
    }

    private function sendToFcm(array $payload, string $serverKey): void
    {
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init('https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            Log::debug('Error sending push notification: ' . curl_error($ch));
        } else {
            Log::debug('Push notification sent: ' . $response);
        }

        curl_close($ch);
    }
}