<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Booking Confirmed',
            'message' => 'Your booking for ' . $this->booking->vehicle->brand . ' has been confirmed.',
            'booking_id' => $this->booking->id,
            'type' => 'booking_confirmed',
        ];
    }
}
