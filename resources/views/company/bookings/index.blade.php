@extends('layouts.app')

@section('content')
    <title>Company Bookings</title>

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

        .approve{
            background:green;
            color:white;
            border:none;
            padding:8px 12px;
            border-radius:6px;
        }

        .reject{
            background:red;
            color:white;
            border:none;
            padding:8px 12px;
            border-radius:6px;
        }

        .badge{
            padding:6px 10px;
            border-radius:20px;
            background:#eee;
            font-size:13px;
        }
    </style>


<h1>Company Bookings</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Vehicle</th>
        <th>Customer</th>
        <th>Dates</th>
        <th>Total</th>
        <th>Commission</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    @foreach($bookings as $booking)

    <tr>
        <td>{{ $booking->id }}</td>

        <td>
            {{ $booking->vehicle->title }}
        </td>

        <td>
            {{ $booking->customer->name ?? 'Customer' }}
        </td>

        <td>
            {{ $booking->start_date }} to {{ $booking->end_date }}
        </td>

        <td>
            Rs {{ number_format($booking->total_amount, 2) }}
        </td>

        <td>
            Rs {{ number_format($booking->commission_amount, 2) }}
        </td>

        <td>
            <span class="badge">{{ $booking->status }}</span>
        </td>

        <td>
            @if($booking->status === 'pending')

                <form method="POST"
                      action="{{ route('company.bookings.approve', $booking->id) }}"
                      style="display:inline;">
                    @csrf
                    <button class="approve">Approve</button>
                </form>

                <form method="POST"
                      action="{{ route('company.bookings.reject', $booking->id) }}"
                      style="display:inline;">
                    @csrf
                    <button class="reject">Reject</button>
                </form>

            @else
                No action
            @endif
        </td>
    </tr>

    @endforeach

</table>

@endsection