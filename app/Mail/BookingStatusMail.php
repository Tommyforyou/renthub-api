<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $messageText;

    public function __construct(Booking $booking, string $messageText)
    {
        $this->booking = $booking;
        $this->messageText = $messageText;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Status Update'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-status'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}