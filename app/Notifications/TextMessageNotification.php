<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Redis;
use App\Broadcasting\TextMessageChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TextMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $textMessageString;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $textMessageString)
    {
        //
        $this->textMessageString = $textMessageString;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        $channels[] = TextMessageChannel::class;
        return $channels;
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
            //
        ];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toTextMessage($notifiable)
    {
        // Format your SMS message here
        return [
            'fromDID' => '8006778036', // Replace with your sender's number (800) 677-8036 3073428099
            'textMessageString' => $this->textMessageString // Your message content
        ];
    }
}
