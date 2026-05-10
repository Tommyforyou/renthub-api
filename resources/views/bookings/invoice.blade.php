@extends('layouts.app')

@section('content')

<div style="background:white; padding:30px; border-radius:12px; max-width:800px; margin:auto;">

    <h1>Rental Booking Invoice</h1>

    <p><strong>Invoice No:</strong> INV-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
    <p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>

    <hr>

    <h3>Customer</h3>
    <p>{{ $booking->customer->name }}</p>
    <p>{{ $booking->customer->email }}</p>

    <h3>Vehicle</h3>
    <p>{{ $booking->vehicle->title }}</p>
    <p>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>

    <h3>Rental Period</h3>
    <p>{{ $booking->start_date }} to {{ $booking->end_date }}</p>
    <p>Total Days: {{ $booking->total_days }}</p>

    <h3>Charges</h3>

    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td>Daily Rate</td>
            <td style="text-align:right;">Rs {{ number_format($booking->daily_rate, 2) }}</td>
        </tr>

        <tr>
            <td>Subtotal</td>
            <td style="text-align:right;">Rs {{ number_format($booking->subtotal, 2) }}</td>
        </tr>

        <tr>
            <td>Deposit</td>
            <td style="text-align:right;">Rs {{ number_format($booking->deposit_amount, 2) }}</td>
        </tr>

        <tr>
            <td>Remaining Balance</td>
            <td style="text-align:right;">Rs {{ number_format($booking->remaining_balance, 2) }}</td>
        </tr>

        <tr style="font-weight:bold; border-top:2px solid black;">
            <td>Total Amount</td>
            <td style="text-align:right;">Rs {{ number_format($booking->total_amount, 2) }}</td>
        </tr>
    </table>

    <br>

    <p><strong>Status:</strong> {{ $booking->status }}</p>
    <p><strong>Payment Status:</strong> {{ $booking->payment_status }}</p>

    <br>

    <button onclick="window.print()"
            style="background:black; color:white; padding:10px 16px; border:none; border-radius:8px;">
        Print / Save as PDF
    </button>

    <a href="{{ route('bookings.invoice.download', $booking->id) }}"
         style="background:green; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
        Download PDF
    </a>

</div>

@endsection