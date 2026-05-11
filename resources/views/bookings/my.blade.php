@extends('layouts.app')

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | My Bookings Page
    |--------------------------------------------------------------------------
    */

    .bookings-wrapper {
        background: white;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 24px 60px rgba(15,23,42,0.08);
        overflow: hidden;
    }

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    .bookings-hero {
        padding: 36px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .bookings-icon {
        width: 88px;
        height: 88px;
        border-radius: 28px;
        background: linear-gradient(135deg,#dbeafe,#bfdbfe);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
    }

    .bookings-title {
        margin: 0 0 10px;
        font-size: 40px;
        font-weight: 900;
        color: #0f172a;
    }

    .bookings-subtitle {
        margin: 0;
        color: #64748b;
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Cards
    |--------------------------------------------------------------------------
    */

    .bookings-content {
        padding: 36px;
    }

    .booking-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 26px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 18px 36px rgba(15,23,42,0.06);
    }

    .booking-grid {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 24px;
    }

    .booking-vehicle {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .booking-meta {
        color: #64748b;
        font-weight: 700;
        margin-bottom: 8px;
    }

    /*
    |--------------------------------------------------------------------------
    | Badges
    |--------------------------------------------------------------------------
    */

    .badge-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 18px 0;
    }

    .status-badge {
        padding: 9px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .status-confirmed,
    .status-approved {
        background: #dcfce7;
        color: #15803d;
    }

    .status-pending {
        background: #fef9c3;
        color: #a16207;
    }

    .status-rejected,
    .status-cancelled {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-completed {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .payment-paid {
        background: #dcfce7;
        color: #15803d;
    }

    .payment-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    /*
    |--------------------------------------------------------------------------
    | Amount Box
    |--------------------------------------------------------------------------
    */

    .amount-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 22px;
        text-align: right;
    }

    .amount-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .amount-value {
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
    }

    /*
    |--------------------------------------------------------------------------
    | Buttons
    |--------------------------------------------------------------------------
    */

    .booking-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 22px;
    }

    .btn-premium {
        border: none;
        border-radius: 16px;
        padding: 13px 18px;
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-pay {
        background: linear-gradient(135deg,#16a34a,#22c55e);
        color: white;
        box-shadow: 0 14px 28px rgba(34,197,94,0.25);
    }

    .btn-dark-custom {
        background: #111827;
        color: white;
    }

    .btn-outline-custom {
        background: white;
        color: #111827;
        border: 1px solid #d1d5db;
    }

    .btn-danger-custom {
        background: #ef4444;
        color: white;
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Form
    |--------------------------------------------------------------------------
    */

    .payment-form {
        margin-top: 22px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 22px;
    }

    .payment-form-title {
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 16px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2,1fr);
        gap: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 900;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-control-custom {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 14px;
        padding: 13px 14px;
        font-size: 14px;
    }

    .form-full {
        grid-column: 1 / -1;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .empty-box {
        padding: 70px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-icon {
        font-size: 56px;
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media(max-width: 850px) {
        .booking-grid,
        .form-grid {
            grid-template-columns: 1fr;
        }

        .amount-box {
            text-align: left;
        }

        .bookings-hero,
        .bookings-content {
            padding: 22px;
        }
    }
</style>

<div class="bookings-wrapper">

    {{--
    |--------------------------------------------------------------------------
    | Page Header
    |--------------------------------------------------------------------------
    --}}

    <div class="bookings-hero">
        <div class="bookings-icon">
            🚘
        </div>

        <div>
            <h1 class="bookings-title">My Bookings</h1>
            <p class="bookings-subtitle">
                Manage your bookings, payments and invoices.
            </p>
        </div>
    </div>

    {{--
    |--------------------------------------------------------------------------
    | Booking List
    |--------------------------------------------------------------------------
    --}}

    <div class="bookings-content">

        @forelse($bookings as $booking)

            @php
                /*
                |--------------------------------------------------------------------------
                | Safe Booking Data
                |--------------------------------------------------------------------------
                */

                $vehicle = $booking->vehicle;
                $status = $booking->status ?? 'pending';
                $paymentStatus = $booking->payment_status ?? 'pending';
                $totalAmount = $booking->total_amount ?? 0;

                /*
                |--------------------------------------------------------------------------
                | Payment Button Logic
                |--------------------------------------------------------------------------
                */

                $canPay = in_array($status, ['confirmed', 'approved'])
                    && $paymentStatus !== 'paid';

                /*
                |--------------------------------------------------------------------------
                | Badge Classes
                |--------------------------------------------------------------------------
                */

                $statusClass = match($status) {
                    'confirmed' => 'status-confirmed',
                    'approved' => 'status-approved',
                    'completed' => 'status-completed',
                    'rejected' => 'status-rejected',
                    'cancelled' => 'status-cancelled',
                    default => 'status-pending',
                };

                $paymentClass = $paymentStatus === 'paid'
                    ? 'payment-paid'
                    : 'payment-pending';
            @endphp

            <div class="booking-card">

                <div class="booking-grid">

                    {{--
                    |--------------------------------------------------------------------------
                    | Booking Details
                    |--------------------------------------------------------------------------
                    --}}

                    <div>
                        <div class="booking-vehicle">
                            {{ $vehicle?->brand }} {{ $vehicle?->model }}
                        </div>

                        <div class="booking-meta">
                            Booking #{{ $booking->id }}
                        </div>

                        <div class="booking-meta">
                            📅 {{ $booking->start_date }} to {{ $booking->end_date }}
                        </div>

                        <div class="badge-row">
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>

                            <span class="status-badge {{ $paymentClass }}">
                                Payment: {{ ucfirst($paymentStatus) }}
                            </span>
                        </div>

                        {{--
                        |--------------------------------------------------------------------------
                        | Booking Actions
                        |--------------------------------------------------------------------------
                        --}}

                        <div class="booking-actions">

                            @if(Route::has('bookings.invoice'))
                                <a href="{{ route('bookings.invoice', $booking) }}"
                                   class="btn-premium btn-outline-custom">
                                    📄 View Invoice
                                </a>
                            @endif

                            @if($canPay)
                                <a href="#payment-form-{{ $booking->id }}"
                                   class="btn-premium btn-pay">
                                    💳 Pay Now
                                </a>
                            @endif

                            @if($status === 'pending')
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                    @csrf

                                    <button class="btn-premium btn-danger-custom">
                                        Cancel Booking
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>

                    {{--
                    |--------------------------------------------------------------------------
                    | Amount Summary
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="amount-box">
                        <div class="amount-label">
                            Total Amount
                        </div>

                        <div class="amount-value">
                            Rs {{ number_format($totalAmount, 2) }}
                        </div>
                    </div>

                </div>

                {{--
                |--------------------------------------------------------------------------
                | Manual Payment Form
                |--------------------------------------------------------------------------
                |
                | This appears only when booking is approved/confirmed
                | and payment is not yet marked as paid.
                |
                --}}

                @if($canPay)

                    <form id="payment-form-{{ $booking->id }}"
                          method="POST"
                          action="{{ route('payments.store', $booking) }}"
                          class="payment-form">

                        @csrf

                        <div class="payment-form-title">
                            Submit Payment Details
                        </div>

                        <div class="form-grid">

                            <div class="form-group">
                                <label>Payment Method</label>

                                <select name="payment_method"
                                        class="form-control-custom"
                                        required>
                                    <option value="">Choose payment method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="juice">Juice</option>
                                    <option value="myt_money">MyT Money</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Transaction Reference</label>

                                <input type="text"
                                       name="transaction_reference"
                                       class="form-control-custom"
                                       placeholder="Example: bank ref, Juice ref, receipt no.">
                            </div>

                            <div class="form-group form-full">
                                <label>Payment Notes</label>

                                <textarea name="notes"
                                          class="form-control-custom"
                                          rows="3"
                                          placeholder="Optional payment note"></textarea>
                            </div>

                        </div>

                        <div class="booking-actions">
                            <button class="btn-premium btn-pay">
                                Submit Payment
                            </button>
                        </div>

                    </form>

                @endif

            </div>

        @empty

            {{--
            |--------------------------------------------------------------------------
            | Empty State
            |--------------------------------------------------------------------------
            --}}

            <div class="empty-box">
                <div class="empty-icon">🚘</div>

                <div class="empty-title">
                    No bookings yet
                </div>

                <p>
                    Your car rental bookings will appear here.
                </p>
            </div>

        @endforelse

    </div>

</div>

@endsection