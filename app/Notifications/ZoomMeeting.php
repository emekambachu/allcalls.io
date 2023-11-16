<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Notifications\PushChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ZoomMeeting extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $zoomLink;

    /**
     * Zoom Meeting Notifiction instance.
     *
     * @param string $title
     * @param string $message
     * @param string $zoomLink
     */
    public function __construct(string $title, string $message, ?string $zoomLink = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->zoomLink = $zoomLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array
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
     * @param object $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->message,
            'zoomLink' => $this->zoomLink
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
        // Here you will need to implement the logic to send the notification
        // via your PushChannel, using the data provided.
        return $this->toArray($notifiable);
    }
}