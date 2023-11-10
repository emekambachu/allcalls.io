<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Notifications\PushChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserOffline extends Notification
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
        return ['database', PushChannel::class];
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
            'title' => 'Status Turned Offline',
            'body' => 'You are now set to offline and will not receive any calls.'
        ];
    }


    /**
     * Send the array representation of the notification to a custom API.
     *
     * @param object $notifiable
     * @return void
     */
    public function toPush(object $notifiable)
    {
        return $this->toArray($notifiable);
    }
}
