@extends('layouts.app')

@section('content')

<div style="
    max-width:1450px;
    margin:0 auto;
    padding:28px 20px 60px;
">

    {{-- HERO --}}
    <div style="
        background:linear-gradient(135deg,#111827 0%, #1f2937 45%, #374151 100%);
        border-radius:34px;
        padding:40px;
        color:white;
        margin-bottom:34px;
        position:relative;
        overflow:hidden;
        box-shadow:0 20px 50px rgba(17,24,39,0.18);
    ">

        <div style="
            position:absolute;
            right:-60px;
            top:-60px;
            width:240px;
            height:240px;
            border-radius:50%;
            background:rgba(255,255,255,0.06);
        "></div>

        <div style="
            position:absolute;
            right:120px;
            bottom:-80px;
            width:220px;
            height:220px;
            border-radius:50%;
            background:rgba(255,255,255,0.04);
        "></div>

        <div style="
            position:relative;
            z-index:2;
            display:flex;
            justify-content:space-between;
            gap:30px;
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
                    🚘 Rental Company Dashboard
                </div>

                <h1 style="
                    font-size:48px;
                    font-weight:900;
                    line-height:1.1;
                    margin-bottom:12px;
                ">
                    {{ $company->company_name }}
                </h1>

                <p style="
                    color:rgba(255,255,255,0.82);
                    max-width:720px;
                    line-height:1.8;
                    font-size:16px;
                ">
                    Monitor fleet performance, booking operations, revenue,
                    occupancy, customer reservations, and business growth in
                    one unified management dashboard.
                </p>

            </div>

            <div style="
                display:flex;
                gap:14px;
                flex-wrap:wrap;
            ">

                <a href="{{ route('vehicles.create') }}"
                   class="hero-btn-dark">
                    + Add Vehicle
                </a>

                <a href="{{ route('company.bookings') }}"
                   class="hero-btn-light">
                    Manage Bookings
                </a>

            </div>

        </div>

    </div>

    {{-- KPI GRID --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
        gap:24px;
        margin-bottom:34px;
    ">

        {{-- REVENUE --}}
        <div class="premium-card">

            <div class="kpi-icon green">
                💰
            </div>

            <div class="kpi-label">
                Total Revenue
            </div>

            <div class="kpi-value">
                Rs {{ number_format($stats['total_revenue'], 0) }}
            </div>

            <div class="kpi-sub">
                Owner payout earnings
            </div>

        </div>

        {{-- BOOKINGS --}}
        <div class="premium-card">

            <div class="kpi-icon blue">
                📅
            </div>

            <div class="kpi-label">
                Total Bookings
            </div>

            <div class="kpi-value">
                {{ $stats['total_bookings'] }}
            </div>

            <div class="kpi-sub">
                Reservations received
            </div>

        </div>

        {{-- VEHICLES --}}
        <div class="premium-card">

            <div class="kpi-icon purple">
                🚘
            </div>

            <div class="kpi-label">
                Fleet Vehicles
            </div>

            <div class="kpi-value">
                {{ $stats['total_vehicles'] }}
            </div>

            <div class="kpi-sub">
                {{ $stats['available_vehicles'] }} available now
            </div>

        </div>

        {{-- PENDING --}}
        <div class="premium-card">

            <div class="kpi-icon orange">
                ⏳
            </div>

            <div class="kpi-label">
                Pending Requests
            </div>

            <div class="kpi-value">
                {{ $stats['pending_bookings'] }}
            </div>

            <div class="kpi-sub">
                Awaiting approval
            </div>

        </div>

    </div>

    {{-- MAIN GRID --}}
    <div style="
        display:grid;
        grid-template-columns:1.2fr 420px;
        gap:30px;
        align-items:start;
    " class="dashboard-grid">

        {{-- LEFT --}}
        <div>

            {{-- BOOKING PIPELINE --}}
            <div class="premium-card" style="margin-bottom:30px;">

                <div class="section-header">

                    <div>
                        <div class="section-title">
                            Booking Pipeline
                        </div>

                        <div class="section-sub">
                            Reservation workflow overview
                        </div>
                    </div>

                </div>

                <div style="
                    display:grid;
                    grid-template-columns:repeat(3,1fr);
                    gap:18px;
                " class="pipeline-grid">

                    <div class="pipeline-box pending-box">

                        <div class="pipeline-number">
                            {{ $stats['pending_bookings'] }}
                        </div>

                        <div class="pipeline-label">
                            Pending
                        </div>

                    </div>

                    <div class="pipeline-box approved-box">

                        <div class="pipeline-number">
                            {{ $stats['approved_bookings'] }}
                        </div>

                        <div class="pipeline-label">
                            Approved
                        </div>

                    </div>

                    <div class="pipeline-box completed-box">

                        <div class="pipeline-number">
                            {{ $stats['completed_bookings'] }}
                        </div>

                        <div class="pipeline-label">
                            Completed
                        </div>

                    </div>

                </div>

            </div>

            {{-- RECENT BOOKINGS --}}
            <div class="premium-card">

                <div class="section-header">

                    <div>
                        <div class="section-title">
                            Recent Bookings
                        </div>

                        <div class="section-sub">
                            Latest reservation activity
                        </div>
                    </div>

                    <a href="{{ route('company.bookings') }}"
                       class="view-link">
                        View All
                    </a>

                </div>

                @if($bookings->count())

                    <div style="
                        display:flex;
                        flex-direction:column;
                        gap:18px;
                    ">

                        @foreach($bookings->take(6) as $booking)

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

                                <div style="
                                    display:flex;
                                    gap:16px;
                                    align-items:center;
                                ">

                                    <img
                                        src="{{ $imageUrl }}"
                                        style="
                                            width:92px;
                                            height:74px;
                                            object-fit:cover;
                                            border-radius:18px;
                                        "
                                    >

                                    <div>

                                        <div style="
                                            font-weight:800;
                                            margin-bottom:6px;
                                            font-size:17px;
                                        ">
                                            {{ $booking->vehicle->brand }}
                                            {{ $booking->vehicle->model }}
                                        </div>

                                        <div style="
                                            color:#6b7280;
                                            margin-bottom:6px;
                                            font-size:14px;
                                        ">
                                            {{ $booking->customer->name }}
                                        </div>

                                        <div style="
                                            color:#6b7280;
                                            font-size:13px;
                                        ">
                                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }}
                                            →
                                            {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                        </div>

                                    </div>

                                </div>

                                <div style="text-align:right;">

                                    <div style="
                                        font-size:20px;
                                        font-weight:900;
                                        margin-bottom:10px;
                                    ">
                                        Rs {{ number_format($booking->total_amount, 0) }}
                                    </div>

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

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div style="
                        color:#6b7280;
                    ">
                        No reservations available.
                    </div>

                @endif

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            {{-- QUICK ACTIONS --}}
            <div class="premium-card" style="margin-bottom:30px;">

                <div class="section-title" style="margin-bottom:22px;">
                    Quick Actions
                </div>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:16px;
                ">

                    <a href="{{ route('vehicles.create') }}"
                       class="action-box">
                        <div class="action-icon">🚗</div>

                        <div>
                            <div class="action-title">
                                Add New Vehicle
                            </div>

                            <div class="action-sub">
                                Expand your rental fleet
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('company.bookings') }}"
                       class="action-box">
                        <div class="action-icon">📅</div>

                        <div>
                            <div class="action-title">
                                Manage Reservations
                            </div>

                            <div class="action-sub">
                                Approve and monitor bookings
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('company.calendar') }}"
                       class="action-box">
                        <div class="action-icon">🗓</div>

                        <div>
                            <div class="action-title">
                                Fleet Calendar
                            </div>

                            <div class="action-sub">
                                View occupancy schedule
                            </div>
                        </div>
                    </a>

                </div>

            </div>

            {{-- FLEET SNAPSHOT --}}
            <div class="premium-card">

                <div class="section-header">

                    <div>
                        <div class="section-title">
                            Fleet Snapshot
                        </div>

                        <div class="section-sub">
                            Current vehicle inventory
                        </div>
                    </div>

                </div>

                @if($vehicles->count())

                    <div style="
                        display:flex;
                        flex-direction:column;
                        gap:16px;
                    ">

                        @foreach($vehicles->take(5) as $vehicle)

                            @php

                                $primaryImage = $vehicle->images
                                    ? $vehicle->images->firstWhere('is_primary', true)
                                    : null;

                                if (!$primaryImage &&
                                    $vehicle->images &&
                                    $vehicle->images->count()) {

                                    $primaryImage = $vehicle->images->first();
                                }

                                $imageUrl = $primaryImage
                                    ? asset('storage/' . $primaryImage->image_path)
                                    : ($vehicle->image
                                        ? asset('storage/' . $vehicle->image)
                                        : 'https://placehold.co/600x400?text=No+Image');

                            @endphp

                            <div class="fleet-row">

                                <div style="
                                    display:flex;
                                    gap:14px;
                                    align-items:center;
                                ">

                                    <img
                                        src="{{ $imageUrl }}"
                                        style="
                                            width:80px;
                                            height:64px;
                                            object-fit:cover;
                                            border-radius:16px;
                                        "
                                    >

                                    <div>

                                        <div style="
                                            font-weight:800;
                                            margin-bottom:5px;
                                        ">
                                            {{ $vehicle->brand }}
                                            {{ $vehicle->model }}
                                        </div>

                                        <div style="
                                            color:#6b7280;
                                            font-size:13px;
                                        ">
                                            Rs {{ number_format($vehicle->price_per_day, 0) }}/day
                                        </div>

                                    </div>

                                </div>

                                @if($vehicle->available)

                                    <div class="badge-success">
                                        Available
                                    </div>

                                @else

                                    <div class="badge-danger">
                                        Unavailable
                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <div style="
                        color:#6b7280;
                    ">
                        No fleet vehicles yet.
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

<style>

body {
    background:#f3f4f6;
}

/* CARDS */

.premium-card {
    background:white;
    border-radius:30px;
    padding:28px;
    box-shadow:0 12px 32px rgba(0,0,0,0.06);
}

/* KPI */

.kpi-icon {
    width:60px;
    height:60px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    margin-bottom:20px;
}

.green {
    background:#dcfce7;
}

.blue {
    background:#dbeafe;
}

.purple {
    background:#ede9fe;
}

.orange {
    background:#fef3c7;
}

.kpi-label {
    color:#6b7280;
    margin-bottom:12px;
    font-size:14px;
}

.kpi-value {
    font-size:40px;
    font-weight:900;
    margin-bottom:8px;
}

.kpi-sub {
    color:#6b7280;
    font-size:14px;
}

/* SECTION */

.section-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:24px;
}

.section-title {
    font-size:28px;
    font-weight:800;
    margin-bottom:6px;
}

.section-sub {
    color:#6b7280;
    font-size:14px;
}

/* PIPELINE */

.pipeline-box {
    border-radius:22px;
    padding:28px;
    text-align:center;
}

.pending-box {
    background:#fff7ed;
}

.approved-box {
    background:#ecfdf5;
}

.completed-box {
    background:#eff6ff;
}

.pipeline-number {
    font-size:42px;
    font-weight:900;
    margin-bottom:10px;
}

.pipeline-label {
    color:#6b7280;
}

/* BOOKING CARD */

.booking-card {
    display:flex;
    justify-content:space-between;
    gap:20px;
    align-items:center;
    padding:18px;
    border-radius:24px;
    border:1px solid #f3f4f6;
}

/* ACTIONS */

.action-box {
    display:flex;
    gap:16px;
    align-items:center;
    background:#f9fafb;
    padding:18px;
    border-radius:22px;
    text-decoration:none;
    color:#111827;
    transition:0.2s;
}

.action-box:hover {
    transform:translateY(-2px);
}

.action-icon {
    width:56px;
    height:56px;
    border-radius:18px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
}

.action-title {
    font-weight:800;
    margin-bottom:4px;
}

.action-sub {
    color:#6b7280;
    font-size:14px;
}

/* FLEET */

.fleet-row {
    display:flex;
    justify-content:space-between;
    gap:18px;
    align-items:center;
    padding:14px;
    border-radius:22px;
    border:1px solid #f3f4f6;
}

/* BUTTONS */

.hero-btn-dark,
.hero-btn-light {
    text-decoration:none;
    padding:15px 20px;
    border-radius:16px;
    font-weight:800;
}

.hero-btn-dark {
    background:white;
    color:#111827;
}

.hero-btn-light {
    background:rgba(255,255,255,0.12);
    color:white;
    backdrop-filter:blur(10px);
}

.view-link {
    color:#111827;
    font-weight:700;
    text-decoration:none;
}

/* BADGES */

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

/* RESPONSIVE */

@media (max-width:1000px) {

    .dashboard-grid {
        grid-template-columns:1fr !important;
    }

    .pipeline-grid {
        grid-template-columns:1fr !important;
    }

    .booking-card,
    .fleet-row {
        flex-direction:column;
        align-items:flex-start;
    }

}

</style>

@endsection