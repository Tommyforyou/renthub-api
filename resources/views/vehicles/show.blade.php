@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('content')

@php
    $galleryImages = $vehicle->images ?? collect();

    $primaryImage = $galleryImages->firstWhere('is_primary', true);

    if (!$primaryImage && $galleryImages->count()) {
        $primaryImage = $galleryImages->first();
    }

    $mainImage = $primaryImage
        ? asset('storage/' . $primaryImage->image_path)
        : ($vehicle->image
            ? asset('storage/' . $vehicle->image)
            : 'https://placehold.co/1200x700?text=No+Image');

    $avgRating = $vehicle->reviews->avg('rating');

    $displayPrice = $vehicle->daily_price ?? $vehicle->price_per_day;
@endphp

<div class="vehicle-page">

    {{-- HEADER --}}
    <div class="vehicle-header">

        <div>
            <h1>
                {{ $vehicle->brand }} {{ $vehicle->model }}
            </h1>

            <div class="vehicle-meta">
                <span>⭐ {{ $avgRating ? number_format($avgRating, 1) : 'New' }}</span>
                <span>{{ $vehicle->year }}</span>
                <span>{{ $vehicle->transmission }}</span>
                <span>{{ $vehicle->fuel_type }}</span>
            </div>
        </div>

        @auth
            @php
                $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                    ->where('vehicle_id', $vehicle->id)
                    ->exists();
            @endphp

            @if($isFavorite)
                <form method="POST" action="{{ route('favorites.destroy', $vehicle) }}">
                    @csrf
                    @method('DELETE')

                    <button class="favorite-btn">
                        ❤️ Saved
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('favorites.store', $vehicle) }}">
                    @csrf

                    <button class="favorite-btn">
                        🤍 Save
                    </button>
                </form>
            @endif
        @endauth

    </div>

    {{-- GALLERY --}}
    <div class="gallery-grid">

        <div>
            <img
                id="mainVehicleImage"
                src="{{ $mainImage }}"
                onclick="openLightbox(this.src)"
                class="main-image"
                alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
            >
        </div>

        <div class="thumbnail-grid">
            @forelse($galleryImages as $img)
                <img
                    src="{{ asset('storage/' . $img->image_path) }}"
                    onclick="changeVehicleImage(this)"
                    class="thumb-image"
                    alt="Vehicle image"
                >
            @empty
                <img
                    src="{{ $mainImage }}"
                    onclick="changeVehicleImage(this)"
                    class="thumb-image"
                    alt="Vehicle image"
                >
            @endforelse
        </div>

    </div>

    {{-- MAIN CONTENT --}}
    <div class="content-grid">

        {{-- LEFT --}}
        <div>

            {{-- FEATURES --}}
            <div class="card">
                <h2 class="section-title">Vehicle Features</h2>

                <div class="feature-grid">
                    <div class="feature-box">
                        🚘
                        <div>{{ $vehicle->transmission }}</div>
                    </div>

                    <div class="feature-box">
                        ⛽
                        <div>{{ $vehicle->fuel_type }}</div>
                    </div>

                    <div class="feature-box">
                        👥
                        <div>{{ $vehicle->seats }} Seats</div>
                    </div>

                    <div class="feature-box">
                        📅
                        <div>{{ $vehicle->year }}</div>
                    </div>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div class="card">
                <h2 class="section-title">Description</h2>

                <p class="description-text">
                    {{ $vehicle->description ?: 'No description available for this vehicle.' }}
                </p>
            </div>

            {{-- COMPANY --}}
            @if($vehicle->company)
                <div class="card">
                    <h2 class="section-title">Rental Company</h2>

                    <div class="company-card-row">
                        <div>
                            <div class="company-name">
                                {{ $vehicle->company->company_name }}
                            </div>

                            <div class="muted-text">
                                Trusted vehicle rental provider.
                            </div>
                        </div>

                        <a href="{{ url('/cars') }}" class="dark-btn">
                           Browse More Cars
                        </a>
                    </div>
                </div>
            @endif

            {{-- REVIEWS --}}
            <div class="card">
                <div class="review-header">
                    <h2 class="section-title no-margin">Reviews</h2>

                    <div class="review-score">
                        ⭐ {{ $avgRating ? number_format($avgRating, 1) : 'New' }}
                    </div>
                </div>

                @if($vehicle->reviews->count())
                    <div class="review-list">
                        @foreach($vehicle->reviews as $review)
                            <div class="review-card">
                                <div class="review-card-header">
                                    <div class="reviewer-name">
                                        {{ optional($review->customer)->name ?? 'Customer' }}
                                    </div>

                                    <div>
                                        ⭐ {{ $review->rating }}
                                    </div>
                                </div>

                                <div class="review-comment">
                                    {{ $review->comment }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="muted-text">
                        No reviews yet.
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="booking-sidebar">

            <div class="card booking-card">

                <div class="price-header">
                    <div>
                        <div class="price-main">
                            Rs {{ number_format($displayPrice, 0) }}
                        </div>

                        <div class="muted-text">
                            per day
                        </div>
                    </div>

                    @if($vehicle->available)
                        <div class="badge-success">Available</div>
                    @else
                        <div class="badge-danger">Unavailable</div>
                    @endif
                </div>

                <form id="bookingCalculator">

                    <div class="form-group">
                        <label>Pickup Date</label>
                        <input type="text" id="start_date" name="start_date" placeholder="Select pickup date">
                    </div>

                    <div class="form-group">
                        <label>Return Date</label>
                        <input type="text" id="end_date" name="end_date" placeholder="Select return date">
                    </div>

                    <div id="priceSummary" class="price-summary">
                        <div class="summary-title">
                            Booking Summary
                        </div>

                        <div class="summary-row">
                            <span>Rental Days</span>
                            <strong id="summaryDays">0</strong>
                        </div>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong>Rs <span id="summarySubtotal">0.00</span></strong>
                        </div>

                        <div class="summary-row">
                            <span>Discount</span>
                            <strong class="discount-text">
                                - Rs <span id="summaryDiscount">0.00</span>
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>VAT 15%</span>
                            <strong>Rs <span id="summaryVat">0.00</span></strong>
                        </div>

                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <strong>Rs <span id="summaryDelivery">0.00</span></strong>
                        </div>

                        <div class="summary-row">
                            <span>Security Deposit</span>
                            <strong>Rs <span id="summaryDeposit">0.00</span></strong>
                        </div>

                        <hr>

                        <div class="summary-row total-row">
                            <span>Total</span>
                            <strong>Rs <span id="summaryTotal">0.00</span></strong>
                        </div>
                    </div>

                    <div id="pricingError" class="pricing-error"></div>

                    <a
                        href="{{ route('bookings.create', $vehicle->id) }}"
                        class="book-btn"
                    >
                        Continue Booking
                    </a>

                </form>

                <div class="booking-note">
                    Secure booking request with instant availability validation.
                </div>

            </div>

        </div>

    </div>

</div>

{{-- LIGHTBOX --}}
<div id="lightbox" class="lightbox">
    <span onclick="closeLightbox()" class="lightbox-close">
        &times;
    </span>

    <img id="lightboxImage" src="" class="lightbox-image">
</div>

<style>
body {
    background:#f3f4f6;
}

.vehicle-page {
    max-width:1400px;
    margin:0 auto;
    padding:30px 20px 60px;
}

.vehicle-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:24px;
}

.vehicle-header h1 {
    font-size:42px;
    font-weight:800;
    margin-bottom:8px;
}

.vehicle-meta {
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
    color:#6b7280;
    font-size:15px;
}

.gallery-grid {
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:16px;
    margin-bottom:36px;
}

.main-image {
    width:100%;
    height:520px;
    object-fit:cover;
    border-radius:26px;
    cursor:pointer;
    background:#f3f4f6;
}

.thumbnail-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
    max-height:520px;
    overflow:auto;
}

.thumb-image {
    width:100%;
    height:250px;
    object-fit:cover;
    border-radius:18px;
    cursor:pointer;
    transition:0.2s;
    background:#f3f4f6;
}

.thumb-image:hover {
    opacity:0.88;
}

.content-grid {
    display:grid;
    grid-template-columns:1fr 390px;
    gap:32px;
    align-items:start;
}

.card {
    background:white;
    padding:28px;
    border-radius:26px;
    box-shadow:0 8px 24px rgba(0,0,0,0.06);
    margin-bottom:26px;
}

.section-title {
    font-size:28px;
    margin-bottom:22px;
}

.no-margin {
    margin:0;
}

.feature-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(180px,1fr));
    gap:18px;
}

.feature-box {
    background:#f9fafb;
    padding:24px;
    border-radius:20px;
    text-align:center;
    font-size:20px;
    font-weight:700;
    display:flex;
    flex-direction:column;
    gap:12px;
}

.description-text {
    color:#4b5563;
    line-height:1.9;
    font-size:16px;
}

.favorite-btn {
    border:none;
    background:white;
    padding:14px 18px;
    border-radius:999px;
    cursor:pointer;
    font-weight:700;
    box-shadow:0 4px 14px rgba(0,0,0,0.08);
}

.dark-btn {
    background:#111827;
    color:white;
    padding:13px 18px;
    border-radius:14px;
    text-decoration:none;
    font-weight:700;
}

.company-card-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.company-name {
    font-size:22px;
    font-weight:700;
    margin-bottom:6px;
}

.muted-text {
    color:#6b7280;
}

.review-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
    gap:20px;
    flex-wrap:wrap;
}

.review-score {
    font-size:20px;
    font-weight:800;
}

.review-list {
    display:flex;
    flex-direction:column;
    gap:18px;
}

.review-card {
    border:1px solid #f3f4f6;
    border-radius:18px;
    padding:18px;
}

.review-card-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}

.reviewer-name {
    font-weight:700;
}

.review-comment {
    color:#4b5563;
    line-height:1.7;
}

.booking-sidebar {
    position:sticky;
    top:20px;
}

.booking-card {
    border:1px solid #eef2f7;
}

.price-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}

.price-main {
    font-size:36px;
    font-weight:800;
}

.badge-success {
    background:#dcfce7;
    color:#166534;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

.badge-danger {
    background:#fee2e2;
    color:#991b1b;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

.form-group {
    margin-bottom:18px;
}

.form-group label {
    font-weight:700;
    margin-bottom:8px;
    display:block;
}

.form-group input {
    width:100%;
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    font-size:15px;
    outline:none;
}

.form-group input:focus {
    border-color:#111827;
}

.price-summary {
    display:none;
    background:#f9fafb;
    border-radius:18px;
    padding:20px;
    margin-bottom:22px;
    border:1px solid #f3f4f6;
}

.summary-title {
    font-size:18px;
    font-weight:800;
    margin-bottom:18px;
}

.summary-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
    font-size:15px;
}

.discount-text {
    color:#16a34a;
}

.total-row {
    font-size:20px;
    font-weight:800;
    margin-bottom:0;
}

.pricing-error {
    display:none;
    background:#fee2e2;
    color:#991b1b;
    border-radius:14px;
    padding:12px 14px;
    margin-bottom:16px;
    font-size:14px;
    font-weight:600;
}

.book-btn {
    width:100%;
    display:block;
    background:#111827;
    color:white;
    text-align:center;
    padding:18px;
    border-radius:16px;
    text-decoration:none;
    font-size:18px;
    font-weight:700;
    transition:0.2s;
}

.book-btn:hover {
    background:#000;
    color:white;
}

.booking-note {
    margin-top:18px;
    color:#6b7280;
    text-align:center;
    font-size:14px;
}

.lightbox {
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.94);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.lightbox-close {
    position:absolute;
    top:24px;
    right:30px;
    color:white;
    font-size:42px;
    cursor:pointer;
}

.lightbox-image {
    max-width:92%;
    max-height:92%;
    border-radius:18px;
}

.rhub-blocked-date,
.flatpickr-day.flatpickr-disabled,
.flatpickr-day.disabled {
    background:#fee2e2 !important;
    color:#991b1b !important;
    border-color:#fecaca !important;
    text-decoration:line-through !important;
    cursor:not-allowed !important;
    opacity:1 !important;
}


@media (max-width: 1000px) {
    .gallery-grid,
    .content-grid {
        grid-template-columns:1fr !important;
    }

    .booking-sidebar {
        position:static;
    }

    .main-image {
        height:380px;
    }

    .thumbnail-grid {
        max-height:none;
    }
}

@media (max-width: 600px) {
    .vehicle-page {
        padding:20px 14px 50px;
    }

    .vehicle-header h1 {
        font-size:32px;
    }

    .card {
        padding:22px;
        border-radius:22px;
    }

    .main-image {
        height:300px;
    }

    .thumb-image {
        height:150px;
    }

    .price-main {
        font-size:30px;
    }
}
</style>


 <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>

    function changeVehicleImage(element) {
        document.getElementById('mainVehicleImage').src = element.src;
    }

    function openLightbox(src) {
        document.getElementById('lightbox').style.display = 'flex';
        document.getElementById('lightboxImage').src = src;
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }

    
   

    document.addEventListener('DOMContentLoaded', async () => {

        const summaryBox = document.getElementById('priceSummary');
        const errorBox = document.getElementById('pricingError');

        let startDate = null;
        let endDate = null;

        function money(value) {
            return Number(value || 0).toLocaleString('en-US', {
                minimumFractionDigits:2,
                maximumFractionDigits:2
            });
        }

        function hideSummary() {
            summaryBox.style.display = 'none';
        }

        function showError(message) {
            errorBox.innerText = message;
            errorBox.style.display = 'block';
        }

        function hideError() {
            errorBox.innerText = '';
            errorBox.style.display = 'none';
        }

        /*
        |--------------------------------------------------------------------------
        | Load unavailable dates
        |--------------------------------------------------------------------------
        */

        const unavailableDates = await fetch(
            "{{ route('vehicles.unavailable-dates', $vehicle->id) }}"
        ).then(res => res.json());
        console.log('Unavailable dates:', unavailableDates);

        function isUnavailable(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const formatted = `${y}-${m}-${d}`;

        return unavailableDates.includes(formatted);
    }

        /*
        |--------------------------------------------------------------------------
        | Pickup calendar
        |--------------------------------------------------------------------------
        */

        flatpickr("#start_date", {
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return isUnavailable(date);
                }
            ],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                if (isUnavailable(dayElem.dateObj)) {
                    dayElem.classList.add('rhub-blocked-date');
                    dayElem.title = 'Unavailable';
                }
            },
            onChange: function(selectedDates, dateStr) {
                startDate = dateStr;
                calculatePrice();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Return calendar
        |--------------------------------------------------------------------------
        */

        flatpickr("#end_date", {
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return isUnavailable(date);
                }
            ],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                if (isUnavailable(dayElem.dateObj)) {
                    dayElem.classList.add('rhub-blocked-date');
                    dayElem.title = 'Unavailable';
                }
            },
            onChange: function(selectedDates, dateStr) {
                endDate = dateStr;
                calculatePrice();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Live pricing
        |--------------------------------------------------------------------------
        */
        async function calculatePrice() {
                if (!startDate || !endDate) return;

                const response = await fetch(
                    "{{ route('vehicles.calculate-price', $vehicle->id) }}",
                    {
                        method:'POST',
                        headers:{
                            'Content-Type':'application/json',
                            'X-CSRF-TOKEN':'{{ csrf_token() }}',
                            'Accept':'application/json',
                        },
                        body:JSON.stringify({
                            start_date:startDate,
                            end_date:endDate,
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.success) {
                    document.getElementById('priceSummary').style.display = 'none';
                    document.getElementById('pricingError').innerText =
                        data.message || 'Vehicle unavailable for selected dates.';
                    document.getElementById('pricingError').style.display = 'block';
                    return;
                }

                const pricing = data.pricing;

                document.getElementById('summaryDays').innerText = pricing.days;
                document.getElementById('summarySubtotal').innerText = Number(pricing.subtotal).toLocaleString();
                document.getElementById('summaryDiscount').innerText = Number(pricing.discount).toLocaleString();
                document.getElementById('summaryVat').innerText = Number(pricing.vat).toLocaleString();
                document.getElementById('summaryDelivery').innerText = Number(pricing.delivery_fee).toLocaleString();
                document.getElementById('summaryDeposit').innerText = Number(pricing.deposit).toLocaleString();
                document.getElementById('summaryTotal').innerText = Number(pricing.grand_total).toLocaleString();

                document.getElementById('priceSummary').style.display = 'block';
                document.getElementById('pricingError').style.display = 'none';
        }
       

    });

</script>

@endsection