<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CheckoutAcceptanceResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public bool $wasAccepted;
    public User $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(User $recipient, bool $wasAccepted)
    {
        $this->recipient = $recipient;
        $this->wasAccepted = $wasAccepted;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A checkout you initiated was ' . ($this->wasAccepted ? 'accepted' : 'declined'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.markdown.checkout-acceptance-response',
            with: [
                'recipient' => $this->recipient,
                'wasAccepted' => $this->wasAccepted,
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
