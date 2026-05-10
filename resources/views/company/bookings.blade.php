@extends('layouts.app')

@section('content')

<div style="
    max-width:1400px;
    margin:0 auto;
    padding:30px 20px;
">

    {{-- HEADER --}}
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        flex-wrap:wrap;
        margin-bottom:28px;
    ">

        <div>
            <h1 style="
                font-size:38px;
                font-weight:800;
                margin-bottom:6px;
            ">
                Booking Management
            </h1>

            <p style="
                color:#6b7280;
            ">
                Manage reservations, approvals, payments, and occupancy.
            </p>
        </div>

    </div>

    @if(session('success'))

        <div style="
            background:#dcfce7;
            color:#166534;
            padding:14px 16px;
            border-radius:12px;
            margin-bottom:20px;
        ">
            {{ session('success') }}
        </div>

    @endif

    {{-- STATS --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
        gap:20px;
        margin-bottom:30px;
    ">

        <div class="stat-card">
            <div class="stat-title">Total Bookings</div>
            <div class="stat-value">
                {{ $stats['total_bookings'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Pending</div>
            <div class="stat-value" style="color:#d97706;">
                {{ $stats['pending_bookings'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Approved</div>
            <div class="stat-value" style="color:#166534;">
                {{ $stats['approved_bookings'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Revenue</div>
            <div class="stat-value">
                Rs {{ number_format($stats['total_revenue'], 0) }}
            </div>
        </div>

    </div>

    {{-- BOOKINGS TABLE --}}
    <div style="
        background:white;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 8px 24px rgba(0,0,0,0.06);
    ">

        <div style="
            padding:24px;
            border-bottom:1px solid #e5e7eb;
        ">

            <h2 style="
                font-size:24px;
                margin:0;
            ">
                All Reservations
            </h2>

        </div>

        @if($bookings->count())

            <div style="overflow:auto;">

                <table style="
                    width:100%;
                    border-collapse:collapse;
                    min-width:1200px;
                ">

                    <thead>

                        <tr style="
                            background:#f9fafb;
                            text-align:left;
                        ">

                            <th class="table-head">Vehicle</th>
                            <th class="table-head">Customer</th>
                            <th class="table-head">Dates</th>
                            <th class="table-head">Amount</th>
                            <th class="table-head">Payment</th>
                            <th class="table-head">Status</th>
                            <th class="table-head">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($bookings as $booking)

                            @php

                                $primaryImage = $booking->vehicle->images
                                    ? $booking->vehicle->images->firstWhere('is_primary', true)
                                    : null;

                                if (!$primaryImage &&
                                    $booking->vehicle->images &&
                                    $booking->vehicle->images->count()) {

                                    $primaryImage = $booking->vehicle->images->first();
                                }

                                $imageUrl = $primaryImage
                                    ? asset('storage/' . $primaryImage->image_path)
                                    : ($booking->vehicle->image
                                        ? asset('storage/' . $booking->vehicle->image)
                                        : 'https://placehold.co/600x400?text=No+Image');

                            @endphp

                            <tr style="
                                border-bottom:1px solid #f3f4f6;
                            ">

                                {{-- VEHICLE --}}
                                <td class="table-cell">

                                    <div style="
                                        display:flex;
                                        gap:14px;
                                        align-items:center;
                                    ">

                                        <img
                                            src="{{ $imageUrl }}"
                                            style="
                                                width:90px;
                                                height:70px;
                                                object-fit:cover;
                                                border-radius:14px;
                                            "
                                        >

                                        <div>

                                            <div style="
                                                font-weight:700;
                                                margin-bottom:4px;
                                            ">
                                                {{ $booking->vehicle->brand }}
                                                {{ $booking->vehicle->model }}
                                            </div>

                                            <div style="
                                                color:#6b7280;
                                                font-size:13px;
                                            ">
                                                {{ $booking->vehicle->year }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- CUSTOMER --}}
                                <td class="table-cell">

                                    <div style="font-weight:600;">
                                        {{ $booking->customer->name }}
                                    </div>

                                    <div style="
                                        color:#6b7280;
                                        font-size:13px;
                                    ">
                                        {{ $booking->customer->email }}
                                    </div>

                                </td>

                                {{-- DATES --}}
                                <td class="table-cell">

                                    <div style="margin-bottom:4px;">
                                        <strong>
                                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                        </strong>
                                    </div>

                                    <div style="margin-bottom:4px;">
                                        to
                                    </div>

                                    <div>
                                        <strong>
                                            {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                        </strong>
                                    </div>

                                    <div style="
                                        margin-top:8px;
                                        color:#6b7280;
                                        font-size:13px;
                                    ">
                                        {{ $booking->total_days }} Days
                                    </div>

                                </td>

                                {{-- AMOUNT --}}
                                <td class="table-cell">

                                    <div style="
                                        font-size:20px;
                                        font-weight:800;
                                        margin-bottom:6px;
                                    ">
                                        Rs {{ number_format($booking->total_amount, 0) }}
                                    </div>

                                    <div style="
                                        color:#6b7280;
                                        font-size:13px;
                                    ">
                                        Deposit:
                                        Rs {{ number_format($booking->deposit_amount, 0) }}
                                    </div>

                                </td>

                                {{-- PAYMENT --}}
                                <td class="table-cell">

                                    @if($booking->payment_status === 'paid')

                                        <div class="badge-success">
                                            Paid
                                        </div>

                                    @else

                                        <div class="badge-warning">
                                            Pending
                                        </div>

                                        <div style="
                                            margin-top:10px;
                                        ">

                                            <form method="POST"
                                                  action="{{ route('company.bookings.markPaid', $booking->id) }}">

                                                @csrf

                                                <button class="btn-dark">
                                                    Mark Paid
                                                </button>

                                            </form>

                                        </div>

                                    @endif

                                </td>

                                {{-- STATUS --}}
                                <td class="table-cell">

                                    @if($booking->status === 'approved')

                                        <div class="badge-success">
                                            Approved
                                        </div>

                                    @elseif($booking->status === 'rejected')

                                        <div class="badge-danger">
                                            Rejected
                                        </div>

                                    @else

                                        <div class="badge-warning">
                                            Pending
                                        </div>

                                    @endif

                                </td>

                                {{-- ACTIONS --}}
                                <td class="table-cell">

                                    @if($booking->status === 'pending')

                                        <div style="
                                            display:flex;
                                            flex-direction:column;
                                            gap:10px;
                                        ">

                                            <form method="POST"
                                                  action="{{ route('company.bookings.approve', $booking->id) }}">

                                                @csrf

                                                <button class="btn-success">
                                                    Approve
                                                </button>

                                            </form>

                                            <form method="POST"
                                                  action="{{ route('company.bookings.reject', $booking->id) }}">

                                                @csrf

                                                <button class="btn-danger">
                                                    Reject
                                                </button>

                                            </form>

                                        </div>

                                    @else

                                        <div style="
                                            color:#6b7280;
                                            font-size:13px;
                                        ">
                                            No actions
                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div style="
                padding:60px 20px;
                text-align:center;
                color:#6b7280;
            ">

                No bookings available.

            </div>

        @endif

    </div>

</div>

<style>

body {
    background:#f3f4f6;
}

.stat-card {
    background:white;
    padding:24px;
    border-radius:22px;
    box-shadow:0 8px 24px rgba(0,0,0,0.06);
}

.stat-title {
    color:#6b7280;
    margin-bottom:10px;
    font-size:14px;
}

.stat-value {
    font-size:34px;
    font-weight:800;
}

.table-head {
    padding:18px;
    font-size:14px;
    color:#6b7280;
    font-weight:700;
}

.table-cell {
    padding:18px;
    vertical-align:top;
}

.badge-success,
.badge-warning,
.badge-danger {
    display:inline-block;
    padding:7px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

.badge-success {
    background:#dcfce7;
    color:#166534;
}

.badge-warning {
    background:#fef3c7;
    color:#92400e;
}

.badge-danger {
    background:#fee2e2;
    color:#991b1b;
}

.btn-success,
.btn-danger,
.btn-dark {
    width:100%;
    border:none;
    padding:10px 12px;
    border-radius:10px;
    cursor:pointer;
    color:white;
    font-weight:600;
}

.btn-success {
    background:#166534;
}

.btn-danger {
    background:#dc2626;
}

.btn-dark {
    background:#111827;
}

button:hover {
    opacity:0.94;
}

</style>

@endsection