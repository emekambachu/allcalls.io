<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FundsDeducted extends Notification
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Funds Deducted',
            'body' => '$' . $this->amount . ' deducted from your funds.'
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
