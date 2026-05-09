<h2>Booking Status Update</h2>

<p>{{ $messageText }}</p>

<p>
    Vehicle: {{ $booking->vehicle->title }}
</p>

<p>
    Dates: {{ $booking->start_date }} to {{ $booking->end_date }}
</p>

<p>
    Total: Rs {{ number_format($booking->total_amount, 2) }}
</p>

<p>
    Status: {{ $booking->status }}
</p>