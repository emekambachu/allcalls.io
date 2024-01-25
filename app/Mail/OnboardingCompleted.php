<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OnboardingCompleted extends Mailable
{
    use Queueable, SerializesModels;
    public User $user;
    public $name;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->name = $user->first_name.' '.$user->last_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "$this->name Waiting for Contracting Review",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.onboarding_completed',
            with: [
                'user' => $this->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
