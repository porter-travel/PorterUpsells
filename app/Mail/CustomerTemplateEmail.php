<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerTemplateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = '';

    public $body = '';

    public $hotel;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, Hotel $hotel)
    {
        $this->subject = $subject;
        $this->body = is_string($body) ? json_decode($body, true) : $body;
        $this->hotel = $hotel;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {


        return new Content(
            view: 'email.builder-template',
            with: [
                'body' => $this->body,
                'hotel' => $this->hotel,
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
