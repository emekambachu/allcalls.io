<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $totalAgentsCount;
    public $approvedAgentsCount;

    /**
     * Create a new message instance.
     */
    public function __construct($totalAgentsCount, $approvedAgentsCount)
    {
        $this->totalAgentsCount = $totalAgentsCount;
        $this->approvedAgentsCount = $approvedAgentsCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'AllCalls Weekly Report',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.weekly-report',
            with: [
                'totalAgentsCount' => $this->totalAgentsCount,
                'approvedAgentsCount' => $this->approvedAgentsCount,
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
