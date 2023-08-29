<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FundTransaction extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public $subTotal;
    public $processingFee;
    public $total;
    public $bonus;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $subTotal, $processingFee, $total, $bonus)
    {
        $this->user = $user;
        $this->subTotal = $subTotal;
        $this->processingFee = $processingFee;
        $this->total = $total;
        $this->bonus = $bonus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Funds Added To Your Account',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.funds_added',
            with: [
                'user' => $this->user,
                'subTotal' => $this->subTotal,
                'processingFee' => $this->processingFee,
                'total' => $this->total,
                'bonus' => $this->bonus,
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
