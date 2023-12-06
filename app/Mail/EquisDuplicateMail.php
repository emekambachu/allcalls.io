<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class EquisDuplicateMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $agentName;
    // public $efNumber;
    // public $agentEmail;
    public $csvFilePath;

    /**
     * Create a new message instance.
     *
     * @param string $agentName
     * @param string $efNumber
     * @param string $agentEmail
     */
    public function __construct(string $csvFilePath)
    {
        // $this->agentName = $agentName;
        // $this->efNumber = $efNumber;
        // $this->agentEmail = $agentEmail;
        $this->csvFilePath = $csvFilePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'AllCalls Free Agents Request';
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
            markdown: 'emails.equis-duplicate',
            with: [
                // 'agentName' => $this->agentName,
                // 'efNumber' => $this->efNumber,
                // 'agentEmail' => $this->agentEmail,
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
        return [
            Attachment::fromPath($this->csvFilePath)
                ->as('Agents.csv')
                ->withMime('text/csv'),
        ];    
    }
}
