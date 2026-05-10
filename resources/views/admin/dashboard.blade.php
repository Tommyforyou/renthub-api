@extends('layouts.app')

@section('content')

<div style="
    max-width:1450px;
    margin:0 auto;
    padding:28px 20px 60px;
">

    {{-- HERO --}}
    <div class="admin-hero">

        <div class="hero-bg-circle"></div>

        <div class="admin-hero-content">

            <div>

                <div class="hero-pill">
                    🛠 RentHub Administration
                </div>

                <h1>
                    Platform Control Center
                </h1>

                <p>
                    Monitor users, companies, vehicles, bookings,
                    approvals, revenue, and overall marketplace operations.
                </p>

            </div>

        </div>

    </div>

    {{-- KPI --}}
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-icon green">💰</div>
            <div class="kpi-label">Platform Revenue</div>
            <div class="kpi-value">
                Rs {{ number_format($totalRevenue, 0) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon blue">🏢</div>
            <div class="kpi-label">Rental Companies</div>
            <div class="kpi-value">
                {{ $totalCompanies }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon orange">⏳</div>
            <div class="kpi-label">Pending Approvals</div>
            <div class="kpi-value">
                {{ $pendingCompanies }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon purple">🚘</div>
            <div class="kpi-label">Fleet Vehicles</div>
            <div class="kpi-value">
                {{ $totalVehicles }}
            </div>
        </div>

    </div>

    {{-- MAIN GRID --}}
    <div class="admin-grid">

        {{-- LEFT --}}
        <div>

            {{-- BOOKING PIPELINE --}}
            <div class="premium-card">

                <div class="section-title">
                    Booking Operations
                </div>

                <div class="pipeline-grid">

                    <div class="pipeline-card pending">
                        <div class="pipeline-number">
                            {{ $pendingBookings }}
                        </div>

                        <div class="pipeline-label">
                            Pending
                        </div>
                    </div>

                    <div class="pipeline-card approved">
                        <div class="pipeline-number">
                            {{ $approvedBookings }}
                        </div>

                        <div class="pipeline-label">
                            Approved
                        </div>
                    </div>

                    <div class="pipeline-card completed">
                        <div class="pipeline-number">
                            {{ $completedBookings }}
                        </div>

                        <div class="pipeline-label">
                            Completed
                        </div>
                    </div>

                </div>

            </div>

            {{-- RECENT BOOKINGS --}}
            <div class="premium-card">

                <div class="card-header">

                    <div>
                        <div class="section-title">
                            Recent Bookings
                        </div>

                        <div class="section-sub">
                            Latest marketplace reservations
                        </div>
                    </div>

                </div>

                <div class="booking-list">

                    @foreach($recentBookings as $booking)

                        <div class="booking-row">

                            <div>

                                <div class="booking-title">
                                    {{ $booking->vehicle->brand }}
                                    {{ $booking->vehicle->model }}
                                </div>

                                <div class="booking-sub">
                                    {{ $booking->customer->name }}
                                </div>

                            </div>

                            <div style="text-align:right;">

                                <div class="booking-amount">
                                    Rs {{ number_format($booking->total_amount, 0) }}
                                </div>

                                @if($booking->status === 'approved')

                                    <div class="badge-success">
                                        Approved
                                    </div>

                                @elseif($booking->status === 'completed')

                                    <div class="badge-completed">
                                        Completed
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

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            {{-- SYSTEM STATS --}}
            <div class="premium-card">

                <div class="section-title">
                    System Overview
                </div>

                <div class="overview-list">

                    <div class="overview-row">
                        <span>Total Users</span>
                        <strong>{{ $totalUsers }}</strong>
                    </div>

                    <div class="overview-row">
                        <span>Approved Companies</span>
                        <strong>{{ $approvedCompanies }}</strong>
                    </div>

                    <div class="overview-row">
                        <span>Available Vehicles</span>
                        <strong>{{ $availableVehicles }}</strong>
                    </div>

                    <div class="overview-row">
                        <span>Total Bookings</span>
                        <strong>{{ $totalBookings }}</strong>
                    </div>

                    <div class="overview-row">
                        <span>Platform Commission</span>
                        <strong>
                            Rs {{ number_format($totalCommission, 0) }}
                        </strong>
                    </div>

                </div>

            </div>

            {{-- RECENT COMPANIES --}}
            <div class="premium-card">

                <div class="section-title">
                    Recent Companies
                </div>

                <div class="company-list">

                    @foreach($recentCompanies as $company)

                        <div class="company-row">

                            <div>

                                <div class="company-name">
                                    {{ $company->company_name }}
                                </div>

                                <div class="company-sub">
                                    {{ $company->created_at->format('d M Y') }}
                                </div>

                            </div>

                            @if($company->status === 'approved')

                                <div class="badge-success">
                                    Approved
                                </div>

                            @elseif($company->status === 'pending')

                                <div class="badge-warning">
                                    Pending
                                </div>

                            @else

                                <div class="badge-danger">
                                    Rejected
                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

<style>

body {
    background:#f3f4f6;
}

/* HERO */

.admin-hero {
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,#111827 0%,#1f2937 55%,#374151 100%);
    border-radius:36px;
    padding:42px;
    margin-bottom:34px;
    color:white;
    box-shadow:0 20px 50px rgba(17,24,39,.18);
}

.hero-bg-circle {
    position:absolute;
    right:-60px;
    top:-60px;
    width:240px;
    height:240px;
    border-radius:50%;
    background:rgba(255,255,255,.05);
}

.admin-hero-content {
    position:relative;
    z-index:2;
}

.hero-pill {
    display:inline-flex;
    background:rgba(255,255,255,.12);
    padding:10px 16px;
    border-radius:999px;
    margin-bottom:18px;
    font-size:14px;
    font-weight:700;
}

.admin-hero h1 {
    font-size:56px;
    line-height:1.05;
    margin:0 0 16px;
    font-weight:900;
}

.admin-hero p {
    color:rgba(255,255,255,.82);
    max-width:760px;
    line-height:1.9;
}

/* KPI */

.kpi-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:24px;
    margin-bottom:34px;
}

.kpi-card,
.premium-card {
    background:white;
    border-radius:30px;
    padding:28px;
    box-shadow:0 12px 32px rgba(0,0,0,.06);
}

.kpi-icon {
    width:60px;
    height:60px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    margin-bottom:18px;
}

.green { background:#dcfce7; }
.blue { background:#dbeafe; }
.orange { background:#fef3c7; }
.purple { background:#ede9fe; }

.kpi-label {
    color:#6b7280;
    margin-bottom:10px;
    font-size:14px;
}

.kpi-value {
    font-size:42px;
    font-weight:900;
}

/* GRID */

.admin-grid {
    display:grid;
    grid-template-columns:1.2fr 420px;
    gap:30px;
    align-items:start;
}

/* SECTION */

.section-title {
    font-size:28px;
    font-weight:900;
    margin-bottom:8px;
}

.section-sub {
    color:#6b7280;
}

/* PIPELINE */

.pipeline-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin-top:24px;
}

.pipeline-card {
    padding:26px;
    border-radius:24px;
    text-align:center;
}

.pending {
    background:#fff7ed;
}

.approved {
    background:#eff6ff;
}

.completed {
    background:#ecfccb;
}

.pipeline-number {
    font-size:44px;
    font-weight:900;
    margin-bottom:10px;
}

.pipeline-label {
    color:#6b7280;
}

/* LISTS */

.booking-list,
.company-list,
.overview-list {
    display:flex;
    flex-direction:column;
    gap:18px;
}

.booking-row,
.company-row,
.overview-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    padding:18px;
    border-radius:22px;
    border:1px solid #f3f4f6;
}

.booking-title,
.company-name {
    font-size:18px;
    font-weight:800;
    margin-bottom:6px;
}

.booking-sub,
.company-sub {
    color:#6b7280;
    font-size:14px;
}

.booking-amount {
    font-size:18px;
    font-weight:900;
    margin-bottom:8px;
}

/* BADGES */

.badge-success,
.badge-warning,
.badge-danger,
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

.badge-completed {
    background:#ecfccb;
    color:#3f6212;
}

/* RESPONSIVE */

@media (max-width:1000px) {

    .admin-grid {
        grid-template-columns:1fr;
    }

    .pipeline-grid {
        grid-template-columns:1fr;
    }

}

</style>

@endsection