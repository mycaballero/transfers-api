<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeliveryNoteMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected $info,
        protected $pdf
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('blu.EMAILS.STORE.ADDRESS'),config('blu.EMAILS.STORE.NAME')),
            subject: !empty($this->info['claim']) ? $this->info['order'].' - HEAVY MOGUL - URBANO'
                : $this->info['order'].' - HEAVY MOGUL'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.deliveryNoteEmail',
            with: [
                'claim' => $this->info['claim'],
                'order' => $this->info['order'],
                'carrier' => $this->info['carrier'],
                'freight' => $this->info['freight'],
                'packed' => $this->info['packed'],
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
            Attachment::fromData(fn() => $this->pdf, 'Albarán de entrega '.$this->info['order'].'.pdf'),
        ];
    }
}
