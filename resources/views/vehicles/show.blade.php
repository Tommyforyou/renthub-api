@extends('layouts.app')

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

@endphp

<div style="
    max-width:1400px;
    margin:0 auto;
    padding:30px 20px 60px;
">

    {{-- HEADER --}}
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:start;
        gap:20px;
        flex-wrap:wrap;
        margin-bottom:24px;
    ">

        <div>

            <h1 style="
                font-size:42px;
                font-weight:800;
                margin-bottom:8px;
            ">
                {{ $vehicle->brand }} {{ $vehicle->model }}
            </h1>

            <div style="
                display:flex;
                align-items:center;
                gap:14px;
                flex-wrap:wrap;
                color:#6b7280;
                font-size:15px;
            ">

                <div>
                    ⭐ {{ $avgRating ? number_format($avgRating, 1) : 'New' }}
                </div>

                <div>
                    {{ $vehicle->year }}
                </div>

                <div>
                    {{ $vehicle->transmission }}
                </div>

                <div>
                    {{ $vehicle->fuel_type }}
                </div>

            </div>

        </div>

        {{-- FAVORITE --}}
        @auth

            @php
                $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                    ->where('vehicle_id', $vehicle->id)
                    ->exists();
            @endphp

            @if($isFavorite)

                <form method="POST"
                      action="{{ route('favorites.destroy', $vehicle) }}">

                    @csrf
                    @method('DELETE')

                    <button class="favorite-btn">
                        ❤️ Saved
                    </button>

                </form>

            @else

                <form method="POST"
                      action="{{ route('favorites.store', $vehicle) }}">

                    @csrf

                    <button class="favorite-btn">
                        🤍 Save
                    </button>

                </form>

            @endif

        @endauth

    </div>

    {{-- GALLERY --}}
    <div style="
        display:grid;
        grid-template-columns:2fr 1fr;
        gap:16px;
        margin-bottom:36px;
    " class="gallery-grid">

        {{-- MAIN IMAGE --}}
        <div>

            <img
                id="mainVehicleImage"
                src="{{ $mainImage }}"
                onclick="openLightbox(this.src)"
                style="
                    width:100%;
                    height:520px;
                    object-fit:cover;
                    border-radius:26px;
                    cursor:pointer;
                    background:#f3f4f6;
                "
            >

        </div>

        {{-- THUMBNAILS --}}
        <div style="
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:14px;
            max-height:520px;
            overflow:auto;
        ">

            @foreach($galleryImages as $img)

                <img
                    src="{{ asset('storage/' . $img->image_path) }}"
                    onclick="changeVehicleImage(this)"
                    style="
                        width:100%;
                        height:250px;
                        object-fit:cover;
                        border-radius:18px;
                        cursor:pointer;
                        transition:0.2s;
                        background:#f3f4f6;
                    "
                    onmouseover="this.style.opacity='0.88'"
                    onmouseout="this.style.opacity='1'"
                >

            @endforeach

        </div>

    </div>

    {{-- MAIN CONTENT --}}
    <div style="
        display:grid;
        grid-template-columns:1fr 380px;
        gap:32px;
        align-items:start;
    " class="content-grid">

        {{-- LEFT --}}
        <div>

            {{-- FEATURES --}}
            <div class="card">

                <h2 class="section-title">
                    Vehicle Features
                </h2>

                <div style="
                    display:grid;
                    grid-template-columns:repeat(auto-fit, minmax(180px,1fr));
                    gap:18px;
                ">

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

                <h2 class="section-title">
                    Description
                </h2>

                <p style="
                    color:#4b5563;
                    line-height:1.9;
                    font-size:16px;
                ">
                    {{ $vehicle->description }}
                </p>

            </div>

            {{-- COMPANY --}}
            @if($vehicle->company)

                <div class="card">

                    <h2 class="section-title">
                        Rental Company
                    </h2>

                    <div style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        gap:20px;
                        flex-wrap:wrap;
                    ">

                        <div>

                            <div style="
                                font-size:22px;
                                font-weight:700;
                                margin-bottom:6px;
                            ">
                                {{ $vehicle->company->company_name }}
                            </div>

                            <div style="
                                color:#6b7280;
                            ">
                                Trusted vehicle rental provider.
                            </div>

                        </div>

                        <a href="{{ route('company.public', $vehicle->company->id) }}"
                           class="dark-btn">
                            View Company
                        </a>

                    </div>

                </div>

            @endif

            {{-- REVIEWS --}}
            <div class="card">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    margin-bottom:22px;
                    gap:20px;
                    flex-wrap:wrap;
                ">

                    <h2 class="section-title" style="margin:0;">
                        Reviews
                    </h2>

                    <div style="
                        font-size:20px;
                        font-weight:800;
                    ">
                        ⭐ {{ $avgRating ? number_format($avgRating, 1) : 'New' }}
                    </div>

                </div>

                @if($vehicle->reviews->count())

                    <div style="
                        display:flex;
                        flex-direction:column;
                        gap:18px;
                    ">

                        @foreach($vehicle->reviews as $review)

                            <div style="
                                border:1px solid #f3f4f6;
                                border-radius:18px;
                                padding:18px;
                            ">

                                <div style="
                                    display:flex;
                                    justify-content:space-between;
                                    align-items:center;
                                    margin-bottom:10px;
                                ">

                                    <div style="font-weight:700;">
                                        {{ $review->customer->name }}
                                    </div>

                                    <div>
                                        ⭐ {{ $review->rating }}
                                    </div>

                                </div>

                                <div style="
                                    color:#4b5563;
                                    line-height:1.7;
                                ">
                                    {{ $review->comment }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div style="
                        color:#6b7280;
                    ">
                        No reviews yet.
                    </div>

                @endif

            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div style="
            position:sticky;
            top:20px;
        ">

            <div class="card">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    margin-bottom:24px;
                ">

                    <div>

                        <div style="
                            font-size:36px;
                            font-weight:800;
                        ">
                            Rs {{ number_format($vehicle->price_per_day, 0) }}
                        </div>

                        <div style="
                            color:#6b7280;
                        ">
                            per day
                        </div>

                    </div>

                    <div class="badge-success">
                        Available
                    </div>

                </div>

                <a href="{{ route('bookings.create', $vehicle->id) }}"
                   class="book-btn">

                    Book This Vehicle

                </a>

                <div style="
                    margin-top:18px;
                    color:#6b7280;
                    text-align:center;
                    font-size:14px;
                ">
                    Secure booking request with instant availability validation.
                </div>

            </div>

        </div>

    </div>

</div>

{{-- LIGHTBOX --}}
<div id="lightbox" style="
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.94);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
">

    <span
        onclick="closeLightbox()"
        style="
            position:absolute;
            top:24px;
            right:30px;
            color:white;
            font-size:42px;
            cursor:pointer;
        "
    >
        &times;
    </span>

    <img
        id="lightboxImage"
        src=""
        style="
            max-width:92%;
            max-height:92%;
            border-radius:18px;
        "
    >

</div>

<style>

body {
    background:#f3f4f6;
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

.favorite-btn {
    border:none;
    background:white;
    padding:14px 18px;
    border-radius:999px;
    cursor:pointer;
    font-weight:700;
    box-shadow:0 4px 14px rgba(0,0,0,0.08);
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
}

.dark-btn {
    background:#111827;
    color:white;
    padding:13px 18px;
    border-radius:14px;
    text-decoration:none;
    font-weight:700;
}

.badge-success {
    background:#dcfce7;
    color:#166534;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

@media (max-width: 1000px) {

    .gallery-grid,
    .content-grid {
        grid-template-columns:1fr !important;
    }

}

</style>

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

</script>

@endsection