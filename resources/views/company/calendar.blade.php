@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<div style="
    max-width:1450px;
    margin:0 auto;
    padding:28px 20px 60px;
">

    {{-- HERO --}}
    <div style="
        background:linear-gradient(135deg,#111827 0%, #1f2937 55%, #374151 100%);
        border-radius:34px;
        padding:36px;
        color:white;
        margin-bottom:32px;
        box-shadow:0 18px 44px rgba(17,24,39,0.18);
        position:relative;
        overflow:hidden;
    ">

        <div style="
            position:absolute;
            right:-80px;
            top:-80px;
            width:260px;
            height:260px;
            border-radius:50%;
            background:rgba(255,255,255,0.05);
        "></div>

        <div style="
            position:relative;
            z-index:2;
            display:flex;
            justify-content:space-between;
            gap:30px;
            flex-wrap:wrap;
            align-items:center;
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
                    🗓 Fleet Occupancy Calendar
                </div>

                <h1 style="
                    font-size:44px;
                    font-weight:900;
                    margin-bottom:12px;
                    line-height:1.1;
                ">
                    Fleet Calendar
                </h1>

                <p style="
                    color:rgba(255,255,255,0.82);
                    max-width:700px;
                    line-height:1.8;
                    font-size:16px;
                ">
                    Monitor reservations, occupancy, approvals,
                    vehicle scheduling, and booking operations across
                    your rental fleet in real-time.
                </p>

            </div>

            <div style="
                display:flex;
                gap:12px;
                flex-wrap:wrap;
            ">

                <a href="{{ route('company.dashboard') }}"
                   class="hero-btn-light">
                    Dashboard
                </a>

                <a href="{{ route('company.bookings') }}"
                   class="hero-btn-light">
                    Bookings
                </a>

            </div>

        </div>

    </div>

    {{-- STATS --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:22px;
        margin-bottom:30px;
    ">

        <div class="stat-card">

            <div class="stat-icon green">
                📅
            </div>

            <div class="stat-title">
                Total Reservations
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

            <div class="stat-icon blue">
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
                Revenue
            </div>

            <div class="stat-value">
                Rs {{ number_format($bookings->sum('owner_payout_amount'), 0) }}
            </div>

        </div>

    </div>

    {{-- LEGEND --}}
    <div class="premium-card" style="margin-bottom:28px;">

        <div style="
            display:flex;
            justify-content:space-between;
            gap:20px;
            align-items:center;
            flex-wrap:wrap;
        ">

            <div>

                <div class="section-title">
                    Booking Status Legend
                </div>

                <div class="section-sub">
                    Understand reservation statuses visually.
                </div>

            </div>

            <div style="
                display:flex;
                gap:14px;
                flex-wrap:wrap;
            ">

                <div class="legend-item">
                    <div class="legend-dot pending-dot"></div>
                    Pending
                </div>

                <div class="legend-item">
                    <div class="legend-dot approved-dot"></div>
                    Approved
                </div>

                <div class="legend-item">
                    <div class="legend-dot completed-dot"></div>
                    Completed
                </div>

                <div class="legend-item">
                    <div class="legend-dot rejected-dot"></div>
                    Rejected
                </div>

            </div>

        </div>

    </div>

    {{-- CALENDAR --}}
    <div class="premium-card">

        <div style="
            display:flex;
            justify-content:space-between;
            gap:20px;
            align-items:center;
            flex-wrap:wrap;
            margin-bottom:28px;
        ">

            <div>

                <div class="section-title">
                    Occupancy Calendar
                </div>

                <div class="section-sub">
                    Vehicle reservation scheduling overview
                </div>

            </div>

        </div>

        <div id="calendar"></div>

    </div>

</div>

<style>

body {
    background:#f3f4f6;
}

/* PREMIUM CARD */

.premium-card {
    background:white;
    border-radius:30px;
    padding:28px;
    box-shadow:0 12px 32px rgba(0,0,0,0.06);
}

/* STATS */

.stat-card {
    background:white;
    border-radius:26px;
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

.green {
    background:#dcfce7;
}

.orange {
    background:#fef3c7;
}

.blue {
    background:#dbeafe;
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
    font-size:38px;
    font-weight:900;
}

/* SECTION */

.section-title {
    font-size:28px;
    font-weight:800;
    margin-bottom:6px;
}

.section-sub {
    color:#6b7280;
    font-size:14px;
}

/* HERO BUTTONS */

.hero-btn-light {
    text-decoration:none;
    background:rgba(255,255,255,0.12);
    color:white;
    padding:14px 18px;
    border-radius:16px;
    font-weight:800;
    backdrop-filter:blur(10px);
}

/* LEGEND */

.legend-item {
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:700;
}

.legend-dot {
    width:14px;
    height:14px;
    border-radius:50%;
}

.pending-dot {
    background:#f59e0b;
}

.approved-dot {
    background:#2563eb;
}

.completed-dot {
    background:#16a34a;
}

.rejected-dot {
    background:#dc2626;
}

/* FULLCALENDAR */

.fc {
    font-family:inherit;
}

.fc-toolbar-title {
    font-size:28px !important;
    font-weight:900 !important;
}

.fc-button {
    background:#111827 !important;
    border:none !important;
    border-radius:12px !important;
    padding:10px 14px !important;
    font-weight:700 !important;
}

.fc-button:hover {
    opacity:0.92;
}

.fc-daygrid-day {
    transition:0.2s;
}

.fc-daygrid-day:hover {
    background:#f9fafb;
}

.fc-event {
    border:none !important;
    padding:6px !important;
    border-radius:12px !important;
    font-size:12px !important;
    font-weight:700 !important;
    cursor:pointer;
}

.fc-event-title {
    white-space:normal !important;
}

.fc-col-header-cell {
    padding:12px 0;
    background:#f9fafb;
}

.fc-day-today {
    background:#eff6ff !important;
}

@media (max-width:900px) {

    .fc-toolbar {
        flex-direction:column;
        gap:14px;
    }

}

</style>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const rawEvents = @json($events);

    const events = rawEvents.map(event => {

        let color = '#2563eb';

        if (event.status === 'pending') {
            color = '#f59e0b';
        }

        if (event.status === 'approved' ||
            event.status === 'confirmed') {

            color = '#2563eb';
        }

        if (event.status === 'completed') {
            color = '#16a34a';
        }

        if (event.status === 'rejected') {
            color = '#dc2626';
        }

        return {
            title: event.title + ' • Rs ' + Number(event.amount).toLocaleString(),
            start: event.start,
            end: event.end,
            backgroundColor: color,
            borderColor: color,
            textColor: '#ffffff'
        };
    });

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        height: 'auto',

        events: events,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },

        eventDisplay: 'block',

        dayMaxEvents: 3

    });

    calendar.render();

});

</script>

@endsection