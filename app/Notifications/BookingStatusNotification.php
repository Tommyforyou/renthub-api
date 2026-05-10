<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    public Booking $booking;
    public string $messageText;

    public function __construct(Booking $booking, string $messageText)
    {
        $this->booking = $booking;
        $this->messageText = $messageText;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'vehicle' => $this->booking->vehicle->title,
            'message' => $this->messageText,
            'status' => $this->booking->status,
            'url' => route('bookings.invoice', $this->booking->id),
        ];
    }
}