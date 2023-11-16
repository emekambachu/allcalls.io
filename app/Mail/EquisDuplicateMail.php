<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EquisDuplicateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agentName;
    public $efNumber;
    public $agentEmail;

    /**
     * Create a new message instance.
     *
     * @param string $agentName
     * @param string $efNumber
     * @param string $agentEmail
     */
    public function __construct(string $agentName, string $efNumber, string $agentEmail)
    {
        $this->agentName = $agentName;
        $this->efNumber = $efNumber;
        $this->agentEmail = $agentEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'AllCalls Free Agent Request for ' . $this->agentName . ' (' . $this->efNumber . ')';
        return new Envelope(
            subject: $subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.equis-duplicate',
            with: [
                'agentName' => $this->agentName,
                'efNumber' => $this->efNumber,
                'agentEmail' => $this->agentEmail,
            ]
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
