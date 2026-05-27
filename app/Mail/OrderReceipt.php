<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $bookings;
    public $user;
    public float $totalAmount;
    public string $reference;

    /**
     * Create a new message instance.
     *
     * @param Collection $bookings
     */
    public function __construct(Collection $bookings)
    {
        $this->bookings = $bookings;
        $this->user = $bookings->first()->user;
        $this->totalAmount = $bookings->sum('total_price');
        $this->reference = $bookings->first()->reference ?? 'EVX-' . str_pad($bookings->first()->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Eventix Payment Receipt - Order #' . $this->reference,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.receipt',
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
