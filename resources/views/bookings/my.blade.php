@extends('layouts.app')

@section('content')
    <title>My Bookings</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        th, td{
            padding:14px;
            border-bottom:1px solid #ddd;
            text-align:left;
        }

        .badge{
            padding:6px 10px;
            border-radius:20px;
            background:#eee;
            font-size:13px;
        }

        img{
            width:120px;
            border-radius:10px;
        }

    </style>


<h1>My Bookings</h1>

<table>

    <tr>
        <th>Vehicle</th>
        <th>Dates</th>
        <th>Total</th>
        <th>Status</th>
        <th>Action</th>
        <th>Payment</th>
        <th>Deposit</th>
        <th>Balance</th>
    </tr>

    @foreach($bookings as $booking)

    <tr>

        <td>

            @if($booking->vehicle->image)

                <img src="{{ asset('storage/' . $booking->vehicle->image) }}">

            @endif

            <br><br>

            {{ $booking->vehicle->title }}

        </td>

        <td>
            {{ $booking->start_date }}
            <br>
            to
            <br>
            {{ $booking->end_date }}
        </td>

        <td>
            Rs {{ number_format($booking->total_amount, 2) }}
        </td>

        <td>
            <span class="badge">
                {{ $booking->status }}
            </span>
        </td>

        <td>
            @if(in_array($booking->status, ['pending', 'confirmed']))

                <form method="POST"
                    action="{{ route('bookings.cancel', $booking->id) }}"
                    onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                    @csrf

                    <button style="
                        background:red;
                        color:white;
                        border:none;
                        padding:8px 12px;
                        border-radius:6px;
                    ">
                        Cancel
                    </button>
                </form>

            @else
                No action
            @endif
        </td>
        
        <td>
            {{ $booking->payment_status }}
        </td>

        <td>
            Rs {{ number_format($booking->deposit_amount, 2) }}
        </td>

        <td>
            Rs {{ number_format($booking->remaining_balance, 2) }}
        </td>

    </tr>

    @endforeach

</table>

@endsection