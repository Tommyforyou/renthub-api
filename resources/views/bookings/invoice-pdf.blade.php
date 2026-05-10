<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ddd; padding: 8px; }
        .right { text-align: right; }
        .total { font-weight: bold; background: #f2f2f2; }
    </style>
</head>
<body>

<h1>Rental Booking Invoice</h1>

<p><strong>Invoice No:</strong> INV-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
<p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>

<h3>Customer</h3>
<p>{{ $booking->customer->name }}<br>{{ $booking->customer->email }}</p>

<h3>Vehicle</h3>
<p>{{ $booking->vehicle->title }}<br>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>

<h3>Rental Period</h3>
<p>{{ $booking->start_date }} to {{ $booking->end_date }}</p>
<p>Total Days: {{ $booking->total_days }}</p>

<table>
    <tr>
        <td>Daily Rate</td>
        <td class="right">Rs {{ number_format($booking->daily_rate, 2) }}</td>
    </tr>
    <tr>
        <td>Subtotal</td>
        <td class="right">Rs {{ number_format($booking->subtotal, 2) }}</td>
    </tr>
    <tr>
        <td>Deposit</td>
        <td class="right">Rs {{ number_format($booking->deposit_amount, 2) }}</td>
    </tr>
    <tr>
        <td>Remaining Balance</td>
        <td class="right">Rs {{ number_format($booking->remaining_balance, 2) }}</td>
    </tr>
    <tr class="total">
        <td>Total Amount</td>
        <td class="right">Rs {{ number_format($booking->total_amount, 2) }}</td>
    </tr>
</table>

<p><strong>Status:</strong> {{ $booking->status }}</p>
<p><strong>Payment Status:</strong> {{ $booking->payment_status }}</p>

</body>
</html>