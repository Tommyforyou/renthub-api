<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Booking $booking;
    public string $title;
    public string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        Booking $booking,
        string $title,
        string $message
    ) {
        $this->booking = $booking;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Notification channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Array representation for database notifications.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,

            'vehicle' => trim(
                ($this->booking->vehicle->brand ?? '') . ' ' .
                ($this->booking->vehicle->model ?? '')
            ),

            'status' => $this->booking->status,

            'title' => $this->title,

            'message' => $this->message,

            'start_date' => optional($this->booking->start_date)
                ->format('d M Y'),

            'end_date' => optional($this->booking->end_date)
                ->format('d M Y'),
        ];
    }
}