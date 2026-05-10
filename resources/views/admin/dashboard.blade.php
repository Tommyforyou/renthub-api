@extends('layouts.app')

@section('content')

<div class="admin-wrapper">

    {{-- EXECUTIVE HERO --}}
    <section class="executive-hero">

        <div class="hero-glow glow-1"></div>
        <div class="hero-glow glow-2"></div>

        <div class="hero-content">

            <div>

                <div class="hero-badge">
                    🛠 RentHub Administration
                </div>

                <h1>
                    Executive Control Center
                </h1>

                <p>
                    Monitor marketplace operations, fleet activity,
                    booking intelligence, company approvals,
                    revenue performance, and overall platform health.
                </p>

            </div>

            <div class="hero-side">

                <div class="hero-metric">
                    <span>Total Revenue</span>

                    <strong>
                        Rs {{ number_format($totalRevenue, 0) }}
                    </strong>
                </div>

                <div class="hero-metric">
                    <span>Platform Commission</span>

                    <strong>
                        Rs {{ number_format($totalCommission, 0) }}
                    </strong>
                </div>

            </div>

        </div>

    </section>

    {{-- KPI GRID --}}
    <section class="kpi-grid">

        <div class="premium-kpi-card revenue-card">

            <div class="kpi-icon">
                💰
            </div>

            <div class="kpi-label">
                Marketplace Revenue
            </div>

            <div class="kpi-value">
                Rs {{ number_format($totalRevenue, 0) }}
            </div>

            <div class="kpi-sub">
                Gross platform bookings
            </div>

        </div>

        <div class="premium-kpi-card">

            <div class="kpi-icon blue-bg">
                🏢
            </div>

            <div class="kpi-label">
                Rental Companies
            </div>

            <div class="kpi-value">
                {{ $totalCompanies }}
            </div>

            <div class="kpi-sub">
                {{ $approvedCompanies }} approved
            </div>

        </div>

        <div class="premium-kpi-card warning-card">

            <div class="kpi-icon orange-bg">
                ⏳
            </div>

            <div class="kpi-label">
                Pending Approvals
            </div>

            <div class="kpi-value">
                {{ $pendingCompanies }}
            </div>

            <div class="kpi-sub">
                Awaiting admin review
            </div>

        </div>

        <div class="premium-kpi-card">

            <div class="kpi-icon purple-bg">
                🚘
            </div>

            <div class="kpi-label">
                Fleet Vehicles
            </div>

            <div class="kpi-value">
                {{ $totalVehicles }}
            </div>

            <div class="kpi-sub">
                {{ $availableVehicles }} available
            </div>

        </div>

    </section>

    {{-- MAIN GRID --}}
    <div class="admin-grid">

        {{-- LEFT --}}
        <div>

            {{-- BOOKING OPERATIONS --}}
            <div class="glass-card">

                <div class="card-header">

                    <div>
                        <div class="card-title">
                            Booking Operations
                        </div>

                        <div class="card-sub">
                            Reservation activity pipeline
                        </div>
                    </div>

                </div>

                <div class="pipeline-grid">

                    <div class="pipeline-box pending-box">

                        <div class="pipeline-number">
                            {{ $pendingBookings }}
                        </div>

                        <div class="pipeline-label">
                            Pending
                        </div>

                    </div>

                    <div class="pipeline-box approved-box">

                        <div class="pipeline-number">
                            {{ $approvedBookings }}
                        </div>

                        <div class="pipeline-label">
                            Approved
                        </div>

                    </div>

                    <div class="pipeline-box completed-box">

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
            <div class="glass-card">

                <div class="card-header">

                    <div>
                        <div class="card-title">
                            Live Booking Feed
                        </div>

                        <div class="card-sub">
                            Latest marketplace reservations
                        </div>
                    </div>

                </div>

                <div class="booking-feed">

                    @foreach($recentBookings as $booking)

                        <div class="booking-feed-row">

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

            {{-- SYSTEM OVERVIEW --}}
            <div class="glass-card">

                <div class="card-title">
                    System Overview
                </div>

                <div class="overview-list">

                    <div class="overview-row">
                        <span>Total Users</span>
                        <strong>{{ $totalUsers }}</strong>
                    </div>

                    <div class="overview-row">
                        <span>Total Companies</span>
                        <strong>{{ $totalCompanies }}</strong>
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
            <div class="glass-card">

                <div class="card-title">
                    Recent Companies
                </div>

                <div class="company-list">

                    @foreach($recentCompanies as $company)

                        <div class="company-row">

                            <div>

                                <div class="company-name">
                                    {{ $company->company_name }}
                                </div>

                                <div class="company-date">
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

            {{-- QUICK ACTIONS --}}
            <div class="glass-card">

                <div class="card-title">
                    Quick Actions
                </div>

                <div class="quick-actions">

                    <a href="#" class="quick-action">
                        🏢 Review Companies
                    </a>

                    <a href="#" class="quick-action">
                        📅 Monitor Bookings
                    </a>

                    <a href="#" class="quick-action">
                        🚘 Fleet Oversight
                    </a>

                    <a href="#" class="quick-action">
                        📈 Revenue Analytics
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

body {
    background:#f3f4f6;
}

.admin-wrapper {
    max-width:1450px;
    margin:0 auto;
    padding:28px 20px 60px;
}

/* HERO */

.executive-hero {
    position:relative;
    overflow:hidden;
    background:
        linear-gradient(135deg,#111827 0%,#1f2937 55%,#374151 100%);
    border-radius:40px;
    padding:48px;
    margin-bottom:36px;
    color:white;
    box-shadow:0 24px 60px rgba(17,24,39,.20);
}

.hero-glow {
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,.05);
}

.glow-1 {
    width:260px;
    height:260px;
    top:-80px;
    right:-80px;
}

.glow-2 {
    width:200px;
    height:200px;
    bottom:-70px;
    right:180px;
}

.hero-content {
    position:relative;
    z-index:2;
    display:flex;
    justify-content:space-between;
    gap:30px;
    flex-wrap:wrap;
    align-items:flex-start;
}

.hero-badge {
    display:inline-flex;
    background:rgba(255,255,255,.12);
    padding:10px 16px;
    border-radius:999px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:700;
}

.executive-hero h1 {
    font-size:62px;
    line-height:1.05;
    margin:0 0 18px;
    font-weight:900;
}

.executive-hero p {
    color:rgba(255,255,255,.82);
    line-height:1.9;
    max-width:760px;
}

.hero-side {
    display:flex;
    flex-direction:column;
    gap:18px;
}

.hero-metric {
    background:rgba(255,255,255,.10);
    backdrop-filter:blur(10px);
    border-radius:24px;
    padding:22px;
    min-width:260px;
}

.hero-metric span {
    display:block;
    color:rgba(255,255,255,.72);
    margin-bottom:8px;
}

.hero-metric strong {
    font-size:34px;
}

/* KPI */

.kpi-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:24px;
    margin-bottom:34px;
}

.premium-kpi-card,
.glass-card {
    background:white;
    border-radius:32px;
    padding:28px;
    box-shadow:0 14px 34px rgba(0,0,0,.06);
}

.revenue-card {
    background:linear-gradient(135deg,#111827 0%,#1f2937 100%);
    color:white;
}

.revenue-card .kpi-label,
.revenue-card .kpi-sub {
    color:rgba(255,255,255,.72);
}

.warning-card {
    border:2px solid #f59e0b22;
}

.kpi-icon {
    width:64px;
    height:64px;
    border-radius:20px;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    margin-bottom:20px;
}

.blue-bg { background:#dbeafe; }
.orange-bg { background:#fef3c7; }
.purple-bg { background:#ede9fe; }

.kpi-label {
    color:#6b7280;
    margin-bottom:10px;
    font-size:14px;
}

.kpi-value {
    font-size:44px;
    font-weight:900;
    margin-bottom:8px;
}

.kpi-sub {
    color:#6b7280;
    font-size:14px;
}

/* GRID */

.admin-grid {
    display:grid;
    grid-template-columns:1.2fr 420px;
    gap:30px;
    align-items:start;
}

/* CARDS */

.card-header {
    display:flex;
    justify-content:space-between;
    gap:20px;
    align-items:center;
    margin-bottom:26px;
}

.card-title {
    font-size:30px;
    font-weight:900;
    margin-bottom:6px;
}

.card-sub {
    color:#6b7280;
}

/* PIPELINE */

.pipeline-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
}

.pipeline-box {
    border-radius:24px;
    padding:28px;
    text-align:center;
}

.pending-box {
    background:#fff7ed;
}

.approved-box {
    background:#eff6ff;
}

.completed-box {
    background:#ecfccb;
}

.pipeline-number {
    font-size:46px;
    font-weight:900;
    margin-bottom:10px;
}

.pipeline-label {
    color:#6b7280;
}

/* FEEDS */

.booking-feed,
.company-list,
.overview-list {
    display:flex;
    flex-direction:column;
    gap:18px;
}

.booking-feed-row,
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
.company-date {
    color:#6b7280;
    font-size:14px;
}

.booking-amount {
    font-size:18px;
    font-weight:900;
    margin-bottom:8px;
}

/* QUICK ACTIONS */

.quick-actions {
    display:flex;
    flex-direction:column;
    gap:16px;
}

.quick-action {
    text-decoration:none;
    background:#f9fafb;
    padding:18px;
    border-radius:20px;
    color:#111827;
    font-weight:800;
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

    .executive-hero h1 {
        font-size:48px;
    }

}

</style>

@endsection