@extends('layouts.app')

@section('content')

<style>
    body { background:#f3f4f6; }

    .page-wrap {
        max-width:1450px;
        margin:0 auto;
        padding:32px 22px;
    }

    .hero {
        background:linear-gradient(135deg,#111827,#1f2937);
        color:white;
        border-radius:28px;
        padding:32px;
        margin-bottom:26px;
        box-shadow:0 24px 50px rgba(15,23,42,.22);
    }

    .hero h1 {
        font-size:38px;
        font-weight:900;
        margin:0;
        letter-spacing:-.8px;
    }

    .hero p {
        margin:8px 0 0;
        color:#cbd5e1;
    }

    .stats-grid {
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(210px,1fr));
        gap:18px;
        margin-bottom:26px;
    }

    .stat-card {
        background:white;
        padding:22px;
        border-radius:22px;
        border:1px solid #e5e7eb;
        box-shadow:0 10px 30px rgba(15,23,42,.08);
    }

    .stat-title {
        font-size:13px;
        font-weight:800;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    .stat-value {
        font-size:34px;
        font-weight:900;
        color:#111827;
        margin-top:8px;
    }

    .panel {
        background:white;
        border-radius:26px;
        overflow:hidden;
        border:1px solid #e5e7eb;
        box-shadow:0 14px 35px rgba(15,23,42,.08);
    }

    .panel-head {
        padding:24px;
        border-bottom:1px solid #e5e7eb;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:15px;
        flex-wrap:wrap;
    }

    .panel-head h2 {
        font-size:24px;
        font-weight:900;
        margin:0;
        color:#111827;
    }

    .table-wrap { overflow:auto; }

    table {
        width:100%;
        border-collapse:collapse;
        min-width:1250px;
    }

    th {
        background:#f8fafc;
        padding:16px 18px;
        font-size:13px;
        color:#64748b;
        font-weight:900;
        text-align:left;
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    td {
        padding:18px;
        vertical-align:top;
        border-bottom:1px solid #f1f5f9;
    }

    .vehicle-box {
        display:flex;
        gap:14px;
        align-items:center;
    }

    .vehicle-box img {
        width:96px;
        height:72px;
        object-fit:cover;
        border-radius:16px;
        border:1px solid #e5e7eb;
    }

    .main-text {
        font-weight:900;
        color:#111827;
    }

    .muted {
        color:#64748b;
        font-size:13px;
        margin-top:4px;
    }

    .money {
        font-size:20px;
        font-weight:900;
        color:#111827;
    }

    .badge {
        display:inline-flex;
        align-items:center;
        padding:8px 13px;
        border-radius:999px;
        font-size:13px;
        font-weight:900;
        text-transform:capitalize;
    }

    .badge-pending { background:#fef3c7; color:#92400e; }
    .badge-confirmed { background:#dcfce7; color:#166534; }
    .badge-rejected { background:#fee2e2; color:#991b1b; }
    .badge-cancelled { background:#e5e7eb; color:#374151; }
    .badge-completed { background:#dbeafe; color:#1d4ed8; }
    .badge-paid { background:#dcfce7; color:#166534; }
    .badge-unpaid { background:#fef3c7; color:#92400e; }

    .action-stack {
        display:flex;
        flex-direction:column;
        gap:9px;
        min-width:140px;
    }

    .btn-action {
        border:none;
        width:100%;
        padding:10px 14px;
        border-radius:12px;
        font-weight:900;
        color:white;
        cursor:pointer;
        transition:.2s;
    }

    .btn-action:hover {
        opacity:.92;
        transform:translateY(-1px);
    }

    .btn-approve { background:#16a34a; }
    .btn-reject { background:#dc2626; }
    .btn-paid { background:#111827; }
    .btn-complete { background:#2563eb; }

    .empty {
        padding:60px 20px;
        text-align:center;
        color:#64748b;
        font-weight:700;
    }

    .alert-success {
        background:#dcfce7;
        color:#166534;
        padding:14px 18px;
        border-radius:16px;
        margin-bottom:18px;
        font-weight:800;
    }

    .alert-danger {
        background:#fee2e2;
        color:#991b1b;
        padding:14px 18px;
        border-radius:16px;
        margin-bottom:18px;
        font-weight:800;
    }
</style>

<div class="page-wrap">

    <div class="hero">
        <h1>Booking Management</h1>
        <p>Manage customer reservations, approval workflow, payment status, and rental completion.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Bookings</div>
            <div class="stat-value">{{ $bookings->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Pending</div>
            <div class="stat-value" style="color:#d97706;">
                {{ $bookings->where('status','pending')->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Confirmed</div>
            <div class="stat-value" style="color:#166534;">
                {{ $bookings->where('status','confirmed')->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Completed</div>
            <div class="stat-value" style="color:#1d4ed8;">
                {{ $bookings->where('status','completed')->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Revenue</div>
            <div class="stat-value">
                Rs {{ number_format($bookings->whereIn('status', ['confirmed','completed'])->sum('total_amount'), 0) }}
            </div>
        </div>
    </div>

    <div class="panel">

        <div class="panel-head">
            <h2>All Reservations</h2>
            <div class="muted">{{ $bookings->count() }} booking(s) found</div>
        </div>

        @if($bookings->count())

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Customer</th>
                            <th>Rental Dates</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Timeline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bookings as $booking)

                            @php
                                $primaryImage = $booking->vehicle && $booking->vehicle->images
                                    ? $booking->vehicle->images->firstWhere('is_primary', true)
                                    : null;

                                if (!$primaryImage && $booking->vehicle && $booking->vehicle->images && $booking->vehicle->images->count()) {
                                    $primaryImage = $booking->vehicle->images->first();
                                }

                                $imageUrl = $primaryImage
                                    ? asset('storage/' . $primaryImage->image_path)
                                    : (($booking->vehicle && $booking->vehicle->image)
                                        ? asset('storage/' . $booking->vehicle->image)
                                        : 'https://placehold.co/600x400?text=No+Image');

                                $statusClass = match($booking->status) {
                                    'pending' => 'badge-pending',
                                    'confirmed' => 'badge-confirmed',
                                    'rejected' => 'badge-rejected',
                                    'cancelled' => 'badge-cancelled',
                                    'completed' => 'badge-completed',
                                    default => 'badge-cancelled',
                                };

                                $totalAmount = $booking->total_amount ?? $booking->total_price ?? 0;
                                $depositAmount = $booking->deposit_amount ?? 0;
                            @endphp

                            <tr>
                                <td>
                                    <div class="vehicle-box">
                                        <img src="{{ $imageUrl }}" alt="Vehicle">

                                        <div>
                                            <div class="main-text">
                                                {{ $booking->vehicle->brand ?? 'Vehicle' }}
                                                {{ $booking->vehicle->model ?? '' }}
                                            </div>

                                            <div class="muted">
                                                {{ $booking->vehicle->year ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="main-text">
                                        {{ $booking->customer->name ?? $booking->user->name ?? 'Customer' }}
                                    </div>

                                    <div class="muted">
                                        {{ $booking->customer->email ?? $booking->user->email ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="main-text">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                    </div>

                                    <div class="muted">to</div>

                                    <div class="main-text">
                                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                    </div>

                                    <div class="muted">
                                        {{ $booking->total_days ?? '-' }} day(s)
                                    </div>
                                </td>

                                <td>
                                    <div class="money">
                                        Rs {{ number_format($totalAmount, 0) }}
                                    </div>

                                    <div class="muted">
                                        Deposit: Rs {{ number_format($depositAmount, 0) }}
                                    </div>
                                </td>

                                <td>
                                    @if($booking->payment_status === 'paid')
                                        <span class="badge badge-paid">Paid</span>
                                    @else
                                        <span class="badge badge-unpaid">Pending</span>

                                        @if(Route::has('company.bookings.markPaid'))
                                            <div style="margin-top:10px;">
                                                <form method="POST"
                                                      action="{{ route('company.bookings.markPaid', $booking->id) }}">
                                                    @csrf

                                                    <button type="submit" class="btn-action btn-paid">
                                                        Mark Paid
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="muted">
                                        Created:
                                        {{ $booking->created_at ? $booking->created_at->format('d M Y H:i') : '-' }}
                                    </div>

                                    @if($booking->confirmed_at)
                                        <div class="muted">
                                            Confirmed:
                                            {{ $booking->confirmed_at->format('d M Y H:i') }}
                                        </div>
                                    @endif

                                    @if($booking->rejected_at)
                                        <div class="muted">
                                            Rejected:
                                            {{ $booking->rejected_at->format('d M Y H:i') }}
                                        </div>
                                    @endif

                                    @if($booking->cancelled_at)
                                        <div class="muted">
                                            Cancelled:
                                            {{ $booking->cancelled_at->format('d M Y H:i') }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-stack">

                                        @if($booking->status === 'pending')

                                            <form method="POST"
                                                  action="{{ route('company.bookings.approve', $booking->id) }}"
                                                  onsubmit="return confirm('Approve this booking?')">
                                                @csrf

                                                <button type="submit" class="btn-action btn-approve">
                                                    Approve
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('company.bookings.reject', $booking->id) }}"
                                                  onsubmit="return confirm('Reject this booking?')">
                                                @csrf

                                                <button type="submit" class="btn-action btn-reject">
                                                    Reject
                                                </button>
                                            </form>

                                        @elseif($booking->status === 'confirmed' && Route::has('company.bookings.complete'))

                                            <form method="POST"
                                                  action="{{ route('company.bookings.complete', $booking->id) }}"
                                                  onsubmit="return confirm('Mark this booking as completed?')">
                                                @csrf

                                                <button type="submit" class="btn-action btn-complete">
                                                    Complete
                                                </button>
                                            </form>

                                        @else

                                            <div class="muted">No actions</div>

                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            <div class="empty">
                No bookings available.
            </div>

        @endif

    </div>

</div>

@endsection