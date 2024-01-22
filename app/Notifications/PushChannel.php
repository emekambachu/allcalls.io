<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;


class PushChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toPush($notifiable);
        Log::debug('Notification Data To Send:', [$data]);

        $this->sendNotifications($notifiable->devices, $data);
    }

    private function sendNotifications($devices, $data)
    {
        $serverKey = env('PUSH_TEST_SERVER_KEY');

        $headers = [
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ];

        $chunks = $devices->chunk(500); // FCM allows up to 500 recipients per batch
        foreach ($chunks as $chunk) {
            $payloads = $chunk->map(function ($device) use ($data) {
                return $this->createPayload($device, $data);
            });

            $response = Http::withHeaders($headers)->post('https://fcm.googleapis.com/fcm/send', ['registration_ids' => $payloads->pluck('to'), 'data' => $data])
                ->body();

            if ($response === false) {
                Log::error('Error sending push notifications.');
            } else {
                Log::info('Push notifications sent successfully.', [$response]);
            }
        }
    }

    private function createPayload($device, $data)
    {
        if (strpos(strtolower($device->device_type), 'android') !== false) {
            return [
                'to' => $device->fcm_token,
                'data' => [
                    'title' => $data['title'],
                    'body' => $data['body'],
                    'zoomLink' => $data['zoomLink'] ?? null,
                ],
                'android' => [
                    'priority' => 'high',
                ],
            ];
        } else {
            return [
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
    }
}

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

//         foreach ($notifiable->devices as $device) {
//             $serverKey = env('PUSH_TEST_SERVER_KEY');

//             // Check the device type and construct the payload accordingly
//             if (strpos(strtolower($device->device_type), 'android') !== false) {
//                 // Android device, send data-only payload
//                 $payload = [
//                     'to' => $device->fcm_token,
//                     'data' => [
//                         'title' => $data['title'],
//                         'body' => $data['body'],
//                         'zoomLink' => $data['zoomLink'] ?? null,
//                     ],
//                     'android' => [
//                         'direct_boot_ok' => true,
//                         'priority' => 'high', // Optional, set priority as high
//                     ],
//                 ];
//             } else {
//                 // iOS or other device, send notification payload
//                 $payload = [
//                     'to' => $device->fcm_token,
//                     'notification' => [
//                         'title' => $data['title'],
//                         'body' => $data['body'],
//                     ],
//                     'data' => [
//                         'zoomLink' => $data['zoomLink'] ?? null,
//                     ],
//                 ];
//             }

//             // Send the notification via FCM
//             $this->sendToFcm($payload, $serverKey);
//         }
//     }

//     private function sendToFcm(array $payload, string $serverKey): void
//     {
//         $headers = [
//             'Authorization: key=' . $serverKey,
//             'Content-Type: application/json',
//         ];

//         $ch = curl_init('https://fcm.googleapis.com/fcm/send');
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//         $response = curl_exec($ch);

//         if ($response === false) {
//             Log::debug('Error sending push notification: ' . curl_error($ch));
//         } else {
//             Log::debug('Push notification sent: ' . $response);
//         }

//         curl_close($ch);
//     }
// }
