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
    protected $emailData;

    /**
     * Zoom Meeting Notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string|null $zoomLink
     * @param array|null $emailData
     */
    public function __construct(string $title, string $message, ?string $zoomLink = null, ?array $emailData = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->zoomLink = $zoomLink;
        $this->emailData = $emailData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        $channels = ['database', PushChannel::class];
        if ($this->emailData) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->from('notifications@allcalls.io', 'AllCalls Notifications')
            ->line($this->emailData['title'] ?? 'The introduction to the notification.');
    
        if (isset($this->emailData['buttonText']) && isset($this->emailData['buttonUrl'])) {
            $mailMessage->action($this->emailData['buttonText'], $this->emailData['buttonUrl']);
        }
    
        $mailMessage->line($this->emailData['description'] ?? 'Thank you for using our application!');
    
        return $mailMessage;
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
        // Implement the logic to send the notification via your PushChannel, using the data provided.
        return $this->toArray($notifiable);
    }
}
