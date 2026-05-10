@extends('layouts.app')

@section('content')
<style>
    body {
        background: #f4f6fb;
    }

    .page-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 35px 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #111827, #1f2937);
        color: white;
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.18);
    }

    .page-header h1 {
        margin: 0;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .page-header p {
        margin: 8px 0 0;
        color: #cbd5e1;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 22px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 34px;
        font-weight: 900;
        margin-top: 6px;
        color: #111827;
    }

    .booking-card {
        background: white;
        border-radius: 22px;
        padding: 22px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }

    .booking-top {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .booking-title {
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }

    .booking-sub {
        color: #64748b;
        font-size: 14px;
        margin-top: 4px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-top: 14px;
    }

    .detail-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 14px;
        border: 1px solid #eef2f7;
    }

    .detail-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #111827;
        font-weight: 800;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 800;
        text-transform: capitalize;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-confirmed {
        background: #dcfce7;
        color: #166534;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-cancelled {
        background: #e5e7eb;
        color: #374151;
    }

    .badge-completed {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .timeline {
        margin-top: 14px;
        color: #64748b;
        font-size: 13px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .btn-action {
        border: none;
        border-radius: 14px;
        padding: 10px 16px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        opacity: 0.92;
    }

    .btn-approve {
        background: #16a34a;
        color: white;
    }

    .btn-reject {
        background: #dc2626;
        color: white;
    }

    .btn-complete {
        background: #2563eb;
        color: white;
    }

    .no-action {
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
        margin-top: 18px;
    }

    .empty-box {
        background: white;
        border-radius: 22px;
        padding: 35px;
        text-align: center;
        color: #64748b;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    @media(max-width: 900px) {
        .stats-grid,
        .details-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .booking-top {
            flex-direction: column;
        }
    }

    @media(max-width: 600px) {
        .stats-grid,
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-wrap">

    <div class="page-header">
        <h1>Company Bookings</h1>
        <p>Review customer requests, confirm rentals, reject invalid bookings, and complete finished trips.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $bookings->where('status', 'pending')->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Confirmed</div>
            <div class="stat-value">{{ $bookings->where('status', 'confirmed')->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Cancelled</div>
            <div class="stat-value">{{ $bookings->where('status', 'cancelled')->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value">{{ $bookings->where('status', 'completed')->count() }}</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger rounded-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @forelse($bookings as $booking)

        @php
            $statusClass = match($booking->status) {
                'pending' => 'badge-pending',
                'confirmed' => 'badge-confirmed',
                'rejected' => 'badge-rejected',
                'cancelled' => 'badge-cancelled',
                'completed' => 'badge-completed',
                default => 'badge-cancelled',
            };

            $vehicleName = $booking->vehicle->title
                ?? (($booking->vehicle->brand ?? '') . ' ' . ($booking->vehicle->model ?? ''));

            $total = $booking->total_amount
                ?? $booking->total_price
                ?? 0;

            $commission = $booking->commission_amount
                ?? $booking->platform_commission
                ?? 0;

            $companyEarning = $booking->company_earning
                ?? $booking->owner_amount
                ?? ($total - $commission);
        @endphp

        <div class="booking-card">

            <div class="booking-top">
                <div>
                    <div class="booking-title">
                        Booking #{{ $booking->id }} — {{ trim($vehicleName) ?: 'Vehicle' }}
                    </div>

                    <div class="booking-sub">
                        Customer:
                        <strong>{{ $booking->customer->name ?? $booking->user->name ?? 'Customer' }}</strong>
                    </div>
                </div>

                <span class="badge-status {{ $statusClass }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="details-grid">
                <div class="detail-box">
                    <div class="detail-label">Pickup Date</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">Return Date</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">Total Amount</div>
                    <div class="detail-value">
                        Rs {{ number_format($total, 2) }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">Commission</div>
                    <div class="detail-value">
                        Rs {{ number_format($commission, 2) }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">Company Earning</div>
                    <div class="detail-value">
                        Rs {{ number_format($companyEarning, 2) }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">Booked On</div>
                    <div class="detail-value">
                        {{ $booking->created_at ? $booking->created_at->format('d M Y H:i') : '-' }}
                    </div>
                </div>
            </div>

            <div class="timeline">
                @if($booking->confirmed_at)
                    <span>Confirmed: {{ $booking->confirmed_at->format('d M Y H:i') }}</span>
                @endif

                @if($booking->rejected_at)
                    <span>Rejected: {{ $booking->rejected_at->format('d M Y H:i') }}</span>
                @endif

                @if($booking->cancelled_at)
                    <span>Cancelled: {{ $booking->cancelled_at->format('d M Y H:i') }}</span>
                @endif
            </div>

            <div class="actions">

                @if($booking->status === 'pending')

                    <form method="POST"
                          action="{{ route('company.bookings.approve', $booking->id) }}"
                          onsubmit="return confirm('Approve this booking?');">
                        @csrf

                        <button type="submit" class="btn-action btn-approve">
                            Approve
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('company.bookings.reject', $booking->id) }}"
                          onsubmit="return confirm('Reject this booking?');">
                        @csrf

                        <button type="submit" class="btn-action btn-reject">
                            Reject
                        </button>
                    </form>

                @elseif($booking->status === 'confirmed')

                    <form method="POST"
                          action="{{ route('company.bookings.complete', $booking->id) }}"
                          onsubmit="return confirm('Mark this booking as completed?');">
                        @csrf

                        <button type="submit" class="btn-action btn-complete">
                            Mark as Completed
                        </button>
                    </form>

                @else

                    <div class="no-action">
                        No action available
                    </div>

                @endif

            </div>

        </div>

    @empty

        <div class="empty-box">
            No bookings found yet.
        </div>

    @endforelse

</div>
@endsection