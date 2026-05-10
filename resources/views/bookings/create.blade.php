@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@php
    $primaryImage = $vehicle->images
        ? $vehicle->images->firstWhere('is_primary', true)
        : null;

    if (!$primaryImage && $vehicle->images && $vehicle->images->count()) {
        $primaryImage = $vehicle->images->first();
    }

    $imageUrl = $primaryImage
        ? asset('storage/' . $primaryImage->image_path)
        : ($vehicle->image
            ? asset('storage/' . $vehicle->image)
            : 'https://placehold.co/600x400?text=No+Image');
@endphp

<div style="max-width:1200px; margin:0 auto; padding:30px 20px;">

    <div style="display:grid; grid-template-columns:1.3fr 420px; gap:28px; align-items:start;" class="booking-grid">

        <div>
            <h1 style="font-size:38px; font-weight:800; margin-bottom:6px;">Book Vehicle</h1>
            <p style="color:#6b7280; margin-bottom:24px;">Select your rental dates. Booked and unavailable dates are greyed out.</p>

            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <ul style="margin:0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <form method="POST" action="{{ route('bookings.store', $vehicle->id) }}" id="bookingForm">
                    @csrf

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;" class="date-grid">
                        <div>
                            <label>Start Date</label>
                            <input type="text" name="start_date" id="startDate" class="form-input" required placeholder="Select start date" value="{{ old('start_date') }}">
                        </div>

                        <div>
                            <label>End Date</label>
                            <input type="text" name="end_date" id="endDate" class="form-input" required placeholder="Select end date" value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div style="margin-top:22px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:18px; padding:22px;">
                        <h3 style="font-size:20px; margin-bottom:18px;">Price Breakdown</h3>

                        <div class="summary-row">
                            <span>Price Per Day</span>
                            <strong>Rs {{ number_format($vehicle->price_per_day, 0) }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Rental Days</span>
                            <strong id="rentalDays">0</strong>
                        </div>

                        <div class="summary-row">
                            <span>Deposit Estimate 30%</span>
                            <strong id="depositAmount">Rs 0</strong>
                        </div>

                        <div style="border-top:1px solid #e5e7eb; margin:18px 0;"></div>

                        <div style="display:flex; justify-content:space-between; font-size:22px; font-weight:800;">
                            <span>Total Amount</span>
                            <span id="totalPrice">Rs 0</span>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Confirm Booking
                    </button>
                </form>
            </div>
        </div>

        <div style="position:sticky; top:20px;">
            <div class="card" style="padding:0; overflow:hidden;">
                <img src="{{ $imageUrl }}" style="width:100%; height:260px; object-fit:cover;">

                <div style="padding:24px;">
                    <h2 style="font-size:28px; margin-bottom:6px;">
                        {{ $vehicle->brand }} {{ $vehicle->model }}
                    </h2>

                    <div style="color:#6b7280; margin-bottom:20px;">
                        {{ $vehicle->year }}
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:22px;">
                        <div class="badge">🚘 {{ $vehicle->transmission }}</div>
                        <div class="badge">⛽ {{ $vehicle->fuel_type }}</div>
                        <div class="badge">👥 {{ $vehicle->seats }} Seats</div>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:16px; border-top:1px solid #e5e7eb;">
                        <span style="color:#6b7280;">Per Day</span>
                        <strong style="font-size:28px;">Rs {{ number_format($vehicle->price_per_day, 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
body {
    background:#f3f4f6;
}

.card {
    background:white;
    padding:28px;
    border-radius:24px;
    box-shadow:0 8px 24px rgba(0,0,0,0.06);
}

label {
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#111827;
}

.form-input {
    width:100%;
    padding:13px 14px;
    border:1px solid #d1d5db;
    border-radius:12px;
    box-sizing:border-box;
    background:white;
    font-size:15px;
}

.form-input:focus {
    outline:none;
    border-color:#111827;
    box-shadow:0 0 0 3px rgba(17,24,39,0.08);
}

.summary-row {
    display:flex;
    justify-content:space-between;
    margin-bottom:14px;
}

.submit-btn {
    width:100%;
    margin-top:24px;
    background:#111827;
    color:white;
    border:none;
    padding:16px;
    border-radius:14px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
}

.badge {
    background:#f3f4f6;
    padding:8px 12px;
    border-radius:999px;
    font-size:13px;
    color:#374151;
}

.alert-error {
    background:#fee2e2;
    color:#991b1b;
    padding:14px 16px;
    border-radius:12px;
    margin-bottom:20px;
}

/* Disabled Flatpickr dates */
.flatpickr-day.flatpickr-disabled,
.flatpickr-day.flatpickr-disabled:hover,
.flatpickr-day.disabled,
.flatpickr-day.disabled:hover {
    background:#fee2e2 !important;
    color:#991b1b !important;
    border-color:#fecaca !important;
    text-decoration:line-through !important;
    cursor:not-allowed !important;
    opacity:1 !important;
}

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background:#111827 !important;
    border-color:#111827 !important;
}

@media (max-width:900px) {
    .booking-grid {
        grid-template-columns:1fr !important;
    }

    .date-grid {
        grid-template-columns:1fr !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const bookedRanges = @json($bookedDates ?? []);

    const availabilityDates = await fetch(
        "{{ route('vehicles.unavailable-dates', $vehicle->id) }}"
    ).then(response => response.json());

    const disabledRanges = [
        ...bookedRanges.map(range => ({
            from: range.start_date,
            to: range.end_date
        })),
        ...availabilityDates
    ];

    const rentalDays = document.getElementById('rentalDays');
    const totalPrice = document.getElementById('totalPrice');
    const depositAmount = document.getElementById('depositAmount');
    const startInput = document.getElementById('startDate');
    const endInput = document.getElementById('endDate');

    const dailyPrice = {{ $vehicle->price_per_day }};

    let endPicker;

    function resetTotals() {
        rentalDays.innerText = '0';
        totalPrice.innerText = 'Rs 0';
        depositAmount.innerText = 'Rs 0';
    }

    function selectedRangeContainsBlockedDate(startDate, endDate) {
        let current = new Date(startDate);
        const end = new Date(endDate);

        while (current <= end) {
            const currentString = current.toISOString().split('T')[0];

            for (const range of bookedRanges) {
                if (currentString >= range.start_date && currentString <= range.end_date) {
                    return true;
                }
            }

            if (availabilityDates.includes(currentString)) {
                return true;
            }

            current.setDate(current.getDate() + 1);
        }

        return false;
    }

    function calculateBooking() {
        const startDateValue = startInput.value;
        const endDateValue = endInput.value;

        if (!startDateValue || !endDateValue) {
            resetTotals();
            return;
        }

        if (selectedRangeContainsBlockedDate(startDateValue, endDateValue)) {
            alert('The selected range includes booked or unavailable dates. Please choose another period.');

            endInput.value = '';

            if (endPicker) {
                endPicker.clear();
            }

            resetTotals();
            return;
        }

        const start = new Date(startDateValue);
        const end = new Date(endDateValue);

        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

        if (days <= 0) {
            resetTotals();
            return;
        }

        const total = days * dailyPrice;
        const deposit = total * 0.30;

        rentalDays.innerText = days;
        totalPrice.innerText = 'Rs ' + total.toLocaleString();
        depositAmount.innerText = 'Rs ' + deposit.toLocaleString();
    }

    const startPicker = flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledRanges,
        defaultDate: "{{ old('start_date') }}",
        onChange: function(selectedDates, dateStr) {
            if (endPicker) {
                endPicker.set("minDate", dateStr || "today");
            }

            calculateBooking();
        }
    });

    endPicker = flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledRanges,
        defaultDate: "{{ old('end_date') }}",
        onChange: function() {
            calculateBooking();
        }
    });

    calculateBooking();
});
</script>

@endsection