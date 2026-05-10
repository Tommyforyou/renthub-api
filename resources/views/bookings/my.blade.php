@extends('layouts.app')

@section('content')

<div style="
    max-width:1400px;
    margin:0 auto;
    padding:28px 20px 60px;
">

    {{-- HERO --}}
    <div style="
        background:linear-gradient(135deg,#111827 0%, #1f2937 55%, #374151 100%);
        border-radius:34px;
        padding:36px;
        color:white;
        margin-bottom:34px;
        position:relative;
        overflow:hidden;
        box-shadow:0 18px 44px rgba(17,24,39,0.18);
    ">

        <div style="
            position:absolute;
            right:-80px;
            top:-80px;
            width:240px;
            height:240px;
            border-radius:50%;
            background:rgba(255,255,255,0.05);
        "></div>

        <div style="
            position:relative;
            z-index:2;
            display:flex;
            justify-content:space-between;
            gap:20px;
            align-items:center;
            flex-wrap:wrap;
        ">

            <div>

                <div style="
                    display:inline-flex;
                    align-items:center;
                    gap:8px;
                    background:rgba(255,255,255,0.12);
                    padding:10px 16px;
                    border-radius:999px;
                    margin-bottom:18px;
                    font-size:14px;
                    backdrop-filter:blur(10px);
                ">
                    📅 My Reservations
                </div>

                <h1 style="
                    font-size:46px;
                    font-weight:900;
                    margin-bottom:12px;
                    line-height:1.1;
                ">
                    My Bookings
                </h1>

                <p style="
                    color:rgba(255,255,255,0.82);
                    max-width:700px;
                    line-height:1.8;
                    font-size:16px;
                ">
                    Track reservation status, payment progress,
                    invoices, booking schedules, and rental activity.
                </p>

            </div>

            <a href="{{ route('cars.index') }}"
               class="hero-btn">
                Browse Vehicles
            </a>

        </div>

    </div>

    {{-- STATS --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:22px;
        margin-bottom:34px;
    ">

        <div class="stat-card">

            <div class="stat-icon blue">
                📅
            </div>

            <div class="stat-title">
                Total Bookings
            </div>

            <div class="stat-value">
                {{ $bookings->count() }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon orange">
                ⏳
            </div>

            <div class="stat-title">
                Pending
            </div>

            <div class="stat-value">
                {{ $bookings->where('status', 'pending')->count() }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon green">
                ✅
            </div>

            <div class="stat-title">
                Approved
            </div>

            <div class="stat-value">
                {{ $bookings->whereIn('status', ['approved','confirmed'])->count() }}
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon purple">
                💰
            </div>

            <div class="stat-title">
                Total Spending
            </div>

            <div class="stat-value">
                Rs {{ number_format($bookings->sum('total_amount'), 0) }}
            </div>

        </div>

    </div>

    {{-- BOOKINGS --}}
    @if($bookings->count())

        <div style="
            display:flex;
            flex-direction:column;
            gap:24px;
        ">

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

                <div class="booking-card">

                    {{-- LEFT --}}
                    <div style="
                        display:flex;
                        gap:22px;
                        align-items:flex-start;
                        flex:1;
                    " class="booking-main">

                        <img
                            src="{{ $imageUrl }}"
                            style="
                                width:240px;
                                height:180px;
                                object-fit:cover;
                                border-radius:24px;
                                background:#f3f4f6;
                            "
                        >

                        <div style="flex:1;">

                            <div style="
                                display:flex;
                                justify-content:space-between;
                                gap:20px;
                                flex-wrap:wrap;
                                margin-bottom:16px;
                            ">

                                <div>

                                    <div style="
                                        font-size:30px;
                                        font-weight:900;
                                        margin-bottom:6px;
                                    ">
                                        {{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}
                                    </div>

                                    <div style="
                                        color:#6b7280;
                                    ">
                                        {{ $booking->vehicle->year }}
                                    </div>

                                </div>

                                <div style="
                                    text-align:right;
                                ">

                                    <div style="
                                        font-size:32px;
                                        font-weight:900;
                                        margin-bottom:6px;
                                    ">
                                        Rs {{ number_format($booking->total_amount, 0) }}
                                    </div>

                                    <div style="
                                        color:#6b7280;
                                        font-size:14px;
                                    ">
                                        Total Booking Amount
                                    </div>

                                </div>

                            </div>

                            {{-- TIMELINE --}}
                            <div style="
                                display:grid;
                                grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
                                gap:16px;
                                margin-bottom:22px;
                            ">

                                <div class="timeline-box">

                                    <div class="timeline-label">
                                        Start Date
                                    </div>

                                    <div class="timeline-value">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                    </div>

                                </div>

                                <div class="timeline-box">

                                    <div class="timeline-label">
                                        End Date
                                    </div>

                                    <div class="timeline-value">
                                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                    </div>

                                </div>

                                <div class="timeline-box">

                                    <div class="timeline-label">
                                        Rental Duration
                                    </div>

                                    <div class="timeline-value">
                                        {{ $booking->total_days }} Days
                                    </div>

                                </div>

                            </div>

                            {{-- PAYMENT BREAKDOWN --}}
                            <div style="
                                display:grid;
                                grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
                                gap:16px;
                                margin-bottom:22px;
                            ">

                                <div class="payment-box">

                                    <div class="payment-label">
                                        Deposit
                                    </div>

                                    <div class="payment-value">
                                        Rs {{ number_format($booking->deposit_amount, 0) }}
                                    </div>

                                </div>

                                <div class="payment-box">

                                    <div class="payment-label">
                                        Remaining Balance
                                    </div>

                                    <div class="payment-value">
                                        Rs {{ number_format($booking->remaining_balance, 0) }}
                                    </div>

                                </div>

                                <div class="payment-box">

                                    <div class="payment-label">
                                        Daily Rate
                                    </div>

                                    <div class="payment-value">
                                        Rs {{ number_format($booking->daily_rate, 0) }}
                                    </div>

                                </div>

                            </div>

                            {{-- BADGES --}}
                            <div style="
                                display:flex;
                                gap:12px;
                                flex-wrap:wrap;
                                margin-bottom:22px;
                            ">
                                @php
                                    $statusLabel = match($booking->status) {
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'confirmed' => 'Confirmed',
                                        'rejected' => 'Rejected',
                                        'cancelled' => 'Cancelled',
                                        'completed' => 'Completed',
                                        default => ucfirst($booking->status),
                                    };

                                    $statusClass = match($booking->status) {
                                        'pending' => 'badge-warning',
                                        'approved', 'confirmed' => 'badge-success',
                                        'rejected', 'cancelled' => 'badge-danger',
                                        'completed' => 'badge-completed',
                                        default => 'badge-warning',
                                    };
                                @endphp

                                <div class="{{ $statusClass }}">
                                    {{ $statusLabel }}
                                </div>

                            </div>

                            {{-- ACTIONS --}}
                            <div style="
                                display:flex;
                                gap:14px;
                                flex-wrap:wrap;
                            ">

                                <a href="{{ route('cars.show', $booking->vehicle->id) }}"
                                   class="action-btn dark-btn">
                                    View Vehicle
                                </a>

                                <a href="{{ route('bookings.invoice', $booking->id) }}"
                                   class="action-btn light-btn">
                                    Invoice
                                </a>

                                <a href="{{ route('bookings.invoice.download', $booking->id) }}"
                                   class="action-btn light-btn">
                                    Download PDF
                                </a>

                                {{-- Cancel Booking by Customer --}}
                                
                                @if(
                                        in_array($booking->status, ['pending', 'confirmed']) &&
                                        now()->lt($booking->start_date)
                                    )

                                        <form method="POST"
                                            action="{{ route('bookings.cancel', $booking->id) }}"
                                            onsubmit="return confirm('Are you sure you want to cancel this booking?')">

                                            @csrf

                                            <button type="submit" class="action-btn cancel-btn">
                                                Cancel Booking
                                            </button>

                                        </form>

                                 @endif

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty-card">

            <div style="
                font-size:60px;
                margin-bottom:18px;
            ">
                🚘
            </div>

            <h2 style="
                font-size:32px;
                margin-bottom:12px;
            ">
                No Bookings Yet
            </h2>

            <p style="
                color:#6b7280;
                margin-bottom:28px;
                max-width:600px;
            ">
                Browse vehicles and start your first reservation with RentHub.
            </p>

            <a href="{{ route('cars.index') }}"
               class="hero-btn">
                Explore Vehicles
            </a>

        </div>

    @endif

</div>

<style>

body {
    background:#f3f4f6;
}

/* HERO */

.hero-btn {
    text-decoration:none;
    background:white;
    color:#111827;
    padding:14px 20px;
    border-radius:16px;
    font-weight:800;
}

/* STATS */

.stat-card {
    background:white;
    border-radius:28px;
    padding:26px;
    box-shadow:0 12px 32px rgba(0,0,0,0.06);
}

.stat-icon {
    width:58px;
    height:58px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    margin-bottom:18px;
}

.blue {
    background:#dbeafe;
}

.orange {
    background:#fef3c7;
}

.green {
    background:#dcfce7;
}

.purple {
    background:#ede9fe;
}

.stat-title {
    color:#6b7280;
    margin-bottom:10px;
    font-size:14px;
}

.stat-value {
    font-size:40px;
    font-weight:900;
}

/* BOOKING */

.booking-card {
    background:white;
    border-radius:32px;
    padding:26px;
    box-shadow:0 14px 34px rgba(0,0,0,0.06);
}

.timeline-box,
.payment-box {
    background:#f9fafb;
    padding:18px;
    border-radius:20px;
}

.timeline-label,
.payment-label {
    color:#6b7280;
    margin-bottom:10px;
    font-size:13px;
}

.timeline-value,
.payment-value {
    font-size:20px;
    font-weight:800;
}

/* ACTIONS */

.action-btn {
    border:none;
    padding:13px 18px;
    border-radius:14px;
    font-weight:800;
    text-decoration:none;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.dark-btn {
    background:#111827;
    color:white;
}

.light-btn {
    background:#f3f4f6;
    color:#111827;
}

.cancel-btn {
    background:#dc2626;
    color:white;
}

/* BADGES */

.badge-success,
.badge-warning,
.badge-danger,
.badge-paid,
.badge-unpaid,
.badge-completed {
    display:inline-block;
    padding:8px 14px;
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

.badge-paid {
    background:#dbeafe;
    color:#1d4ed8;
}

.badge-unpaid {
    background:#ede9fe;
    color:#6d28d9;
}

.badge-completed {
    background:#ecfccb;
    color:#3f6212;
}

/* EMPTY */

.empty-card {
    background:white;
    border-radius:34px;
    padding:80px 40px;
    text-align:center;
    box-shadow:0 12px 32px rgba(0,0,0,0.06);
}

/* RESPONSIVE */

@media (max-width:1000px) {

    .booking-main {
        flex-direction:column;
    }

}

</style>

@endsection