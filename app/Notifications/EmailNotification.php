<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $emailData;

    /**
     * Create a new notification instance.
     */
    public function __construct(?array $emailData = null)
    {
        //
        $this->emailData = $emailData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $subject = $this->emailData['subject'] ?? 'AllCalls Notification'; // Use the provided subject or a default

        // Prepare data to be passed to the email view
        $viewData = [
            'user' => $notifiable,
            'greeting' => $this->emailData['greeting'] ?? null,
            // 'introLines' => [$this->message], // Assuming this is your intro line
            'actionText' => $this->emailData['buttonText'] ?? null,
            'actionUrl' => $this->emailData['buttonUrl'] ?? null,
            'outroLines' => ['Thank you for using our application!'],
            'salutation' => $this->emailData['salutation'] ?? null,
            'title' => $this->emailData['title'] ?? null,
            'description' => $this->emailData['description'] ?? null,
            // 'level' => 'success', // Define the level (success, error, etc.) if applicable
            // Add any other necessary data you want to include in the email
        ];

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.custom-zoom-meeting', $viewData); // Assuming your Blade view is named 'emails.custom_zoom_meeting'
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
}
