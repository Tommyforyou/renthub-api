@extends('layouts.app')

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | Payments Page
    |--------------------------------------------------------------------------
    */

    .payments-page {
        background: white;
        border-radius: 28px;
        padding: 32px;
        box-shadow: 0 20px 50px rgba(15,23,42,0.08);
        border: 1px solid #e5e7eb;
    }

    .payments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .payments-title {
        font-size: 34px;
        font-weight: 900;
        margin: 0 0 8px;
        color: #111827;
    }

    .payments-subtitle {
        color: #6b7280;
        margin: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Cards
    |--------------------------------------------------------------------------
    */

    .payment-card {
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 18px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(15,23,42,0.05);
    }

    .payment-top {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .payment-vehicle {
        font-size: 22px;
        font-weight: 900;
        color: #111827;
    }

    .payment-company {
        color: #6b7280;
        margin-top: 6px;
    }

    .payment-amount {
        font-size: 28px;
        font-weight: 900;
        color: #111827;
        text-align: right;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .payment-box {
        background: #f9fafb;
        border-radius: 16px;
        padding: 16px;
    }

    .payment-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .payment-value {
        font-weight: 900;
        color: #111827;
    }

    /*
    |--------------------------------------------------------------------------
    | Status Badges
    |--------------------------------------------------------------------------
    */

    .status-badge {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .status-paid {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .empty-card {
        text-align: center;
        padding: 70px 20px;
        color: #6b7280;
    }

    .empty-icon {
        font-size: 58px;
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 28px;
        font-weight: 900;
        color: #111827;
        margin-bottom: 8px;
    }
</style>

<div class="payments-page">

    {{--
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    --}}

    <div class="payments-header">
        <div>
            <h1 class="payments-title">
                Payment History
            </h1>

            <p class="payments-subtitle">
                View your submitted payments, booking references and payment status.
            </p>
        </div>
    </div>

    {{--
    |--------------------------------------------------------------------------
    | Payment List
    |--------------------------------------------------------------------------
    --}}

    @forelse($payments as $payment)

        @php
            /*
            |--------------------------------------------------------------------------
            | Safe Related Data
            |--------------------------------------------------------------------------
            */

            $booking = $payment->booking;
            $vehicle = $booking?->vehicle;
            $company = $payment->rentalCompany;

            /*
            |--------------------------------------------------------------------------
            | Status Styling
            |--------------------------------------------------------------------------
            */

            $statusClass = match($payment->status) {
                'paid' => 'status-paid',
                'failed' => 'status-failed',
                default => 'status-pending',
            };
        @endphp

        <div class="payment-card">

            <div class="payment-top">

                <div>
                    <div class="payment-vehicle">
                        {{ $vehicle?->brand ?? 'Vehicle' }}
                        {{ $vehicle?->model ?? '' }}
                    </div>

                    <div class="payment-company">
                        Booking #{{ $booking?->id ?? 'N/A' }}
                        · {{ $company?->company_name ?? 'Rental Company' }}
                    </div>
                </div>

                <div>
                    <div class="payment-amount">
                        Rs {{ number_format((float) $payment->amount, 2) }}
                    </div>

                    <div style="text-align:right;margin-top:8px;">
                        <span class="status-badge {{ $statusClass }}">
                            {{ ucfirst($payment->status ?? 'pending') }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="payment-grid">

                <div class="payment-box">
                    <div class="payment-label">
                        Payment Method
                    </div>

                    <div class="payment-value">
                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'manual')) }}
                    </div>
                </div>

                <div class="payment-box">
                    <div class="payment-label">
                        Transaction Reference
                    </div>

                    <div class="payment-value">
                        {{ $payment->transaction_reference ?? 'N/A' }}
                    </div>
                </div>
                <div class="payment-box">
                    <div class="payment-label">
                        Submitted
                    </div>

                    <div class="payment-value">
                        {{ $payment->created_at?->format('d M Y H:i') }}
                    </div>
                </div>

                <div class="payment-box">
                    <div class="payment-label">
                        Paid At
                    </div>

                    <div class="payment-value">
                        {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : 'Not confirmed yet' }}
                    </div>
                </div>

            </div>

        </div>

    @empty

        <div class="empty-card">
            <div class="empty-icon">
                💳
            </div>

            <div class="empty-title">
                No payments yet
            </div>

            <p>
                Once you submit a booking payment, it will appear here.
            </p>
        </div>

    @endforelse

</div>

@endsection