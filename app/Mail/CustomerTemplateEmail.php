<?php

namespace App\Mail;

use App\Models\Hotel;
use App\Models\Booking;
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

    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, Hotel $hotel, ?Booking $booking)
    {
        $this->subject = $subject;
        $this->body = is_string($body) ? json_decode($body, true) : $body;
        $this->hotel = $hotel;
        $this->booking = $booking;

        foreach ($this->body as $key => $item){
            if (isset($item['content']) && is_string($item['content']) && !empty($item['content'])) {
                $newText = $this->replaceMergeTags($item['content']);
                $this->body[$key]['content'] = $newText;

            }
            if(isset($item['blocks']) && is_array($item['blocks'])){
                foreach ($item['blocks']['column1'] as $bKey => $block){
                    if (isset($block['content']) && is_string($block['content']) && !empty($block['content'])) {
                        $newBlockText = $this->replaceMergeTags($block['content']);
                        $this->body[$key]['blocks']['column1'][$bKey]['content'] = $newBlockText;
                    }
                }
                foreach ($item['blocks']['column2'] as $bKey => $block){
                    if (isset($block['content']) && is_string($block['content']) && !empty($block['content'])) {
                        $newBlockText = $this->replaceMergeTags($block['content']);
                        $this->body[$key]['blocks']['column2'][$bKey]['content'] = $newBlockText;
                    }
                }
            }
        }
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

    private function replaceMergeTags($content){
        if(!$this->booking){
            return $content;
        }
        // Ensure content is a string before proceeding
        if (!is_string($content) || empty($content)) {
            return ''; // Return an empty string if content is not valid
        }

        $placeholders = [
            '[[first_name]]' => explode(' ', $this->booking->name)[0] ?? '',
            '[[last_name]]' => explode(' ', $this->booking->last_name)[0] ?? '',
            '[[business_name]]' => $this->hotel->name ?? '',
            // Ensure proper null checks on $this->booking->... properties
            // Use proper format for dates, e.g., 'Y-m-d' from database
            '[[arrival_date]]' => date('d/m/Y', strtotime($this->booking->arrival_date ?? 'now')),
            '[[departure_date]]' => date('d/m/Y', strtotime($this->booking->departure_date ?? 'now')),
            '[[days_until_arrival]]' => $this->booking->getDaysUntilArrival() ?? '0',
        ];
        return strtr($content, $placeholders);
    }
}
