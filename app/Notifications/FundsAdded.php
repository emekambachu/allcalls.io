<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Notifications\PushChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FundsAdded extends Notification
{
    use Queueable;

    public $amount;

    /**
     * Create a new notification instance.
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Funds Added',
            'body' => '$' . $this->amount . ' added to your funds.',
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
