@extends('layouts.app')

@section('content')

<div class="browse-page">

    {{-- HERO --}}
    <div class="browse-hero">
        <div>
            <div class="hero-pill">🚗 RentHub Marketplace</div>

            <h1>Find your perfect rental car</h1>

            <p>
                Compare vehicles, view availability, save favourites, and book your next ride across Mauritius.
            </p>
        </div>

        <div class="hero-stats">
            <div>
                <strong>{{ $vehicles->count() }}</strong>
                <span>Available Cars</span>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="filter-card">
        <form method="GET">
            <div class="filter-grid">

                <div>
                    <label>Brand</label>
                    <input type="text" name="brand" value="{{ request('brand') }}" placeholder="Toyota, BMW..." class="form-input">
                </div>

                <div>
                    <label>Transmission</label>
                    <select name="transmission" class="form-input">
                        <option value="">Any</option>
                        <option value="Automatic" {{ request('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="Manual" {{ request('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <div>
                    <label>Fuel Type</label>
                    <select name="fuel_type" class="form-input">
                        <option value="">Any</option>
                        <option value="Petrol" {{ request('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="Gasoline" {{ request('fuel_type') == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                        <option value="Diesel" {{ request('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Hybrid" {{ request('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="Electric" {{ request('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                    </select>
                </div>

                <div>
                    <label>Seats</label>
                    <input type="number" name="seats" value="{{ request('seats') }}" placeholder="Min seats" class="form-input">
                </div>

                <div>
                    <label>Max Price</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rs/day" class="form-input">
                </div>

                <div>
                    <button type="submit" class="search-btn">Search</button>
                </div>

            </div>
        </form>
    </div>

    {{-- VEHICLES --}}
    @if($vehicles->count())

        <div class="vehicle-grid">

            @foreach($vehicles as $vehicle)

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
                            : 'https://placehold.co/700x450?text=No+Image');

                    $averageRating = round($vehicle->reviews->avg('rating'), 1);
                    $reviewCount = $vehicle->reviews->count();
                @endphp

                <div class="vehicle-card">

                    <div class="image-wrap">
                        <a href="{{ route('cars.show', $vehicle->id) }}">
                            <img src="{{ $imageUrl }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}">
                        </a>

                        <div class="price-pill">
                            Rs {{ number_format($vehicle->price_per_day, 0) }}
                            <span>/day</span>
                        </div>

                        @auth
                            @php
                                $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                                    ->where('vehicle_id', $vehicle->id)
                                    ->exists();
                            @endphp

                            @if($isFavorite)
                                <form method="POST" action="{{ route('favorites.destroy', $vehicle) }}" class="fav-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="fav-btn">❤️</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('favorites.store', $vehicle) }}" class="fav-form">
                                    @csrf
                                    <button class="fav-btn">🤍</button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div class="card-body">

                        <div class="card-top">
                            <div>
                                <h2>{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
                                <p>{{ $vehicle->title }}</p>
                            </div>

                            <div class="rating-pill">
                                ⭐ {{ $reviewCount > 0 ? $averageRating : 'New' }}
                            </div>
                        </div>

                        <div class="badge-row">
                            <span>📅 {{ $vehicle->year }}</span>
                            <span>🚘 {{ $vehicle->transmission }}</span>
                            <span>⛽ {{ $vehicle->fuel_type }}</span>
                            <span>👥 {{ $vehicle->seats }} seats</span>
                        </div>

                        <p class="description">
                            {{ \Illuminate\Support\Str::limit($vehicle->description, 115) }}
                        </p>

                        <div class="actions">
                            <a href="{{ route('cars.show', $vehicle->id) }}" class="dark-action">
                                View Details
                            </a>

                            <a href="{{ route('bookings.create', $vehicle->id) }}" class="light-action">
                                Book Now
                            </a>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty-card">
            <div>🚘</div>
            <h2>No vehicles found</h2>
            <p>Try changing your filters or browsing all available cars.</p>
            <a href="{{ route('cars.index') }}">Reset Filters</a>
        </div>

    @endif

</div>

<style>
body {
    background:#f3f4f6;
}

.browse-page {
    max-width:1450px;
    margin:0 auto;
    padding:28px 20px 60px;
}

.browse-hero {
    background:linear-gradient(135deg,#111827 0%,#1f2937 55%,#374151 100%);
    color:white;
    border-radius:34px;
    padding:38px;
    margin-bottom:28px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:24px;
    flex-wrap:wrap;
    box-shadow:0 18px 44px rgba(17,24,39,.18);
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

.browse-hero h1 {
    font-size:46px;
    font-weight:900;
    margin:0 0 12px;
}

.browse-hero p {
    max-width:720px;
    color:rgba(255,255,255,.82);
    line-height:1.8;
    margin:0;
}

.hero-stats {
    background:rgba(255,255,255,.12);
    border-radius:24px;
    padding:22px 28px;
    text-align:center;
}

.hero-stats strong {
    display:block;
    font-size:42px;
}

.hero-stats span {
    color:rgba(255,255,255,.78);
}

.filter-card {
    background:white;
    border-radius:28px;
    padding:24px;
    margin-bottom:30px;
    box-shadow:0 12px 32px rgba(0,0,0,.06);
}

.filter-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(170px,1fr));
    gap:16px;
    align-items:end;
}

label {
    display:block;
    margin-bottom:8px;
    font-weight:700;
}

.form-input {
    width:100%;
    padding:13px 14px;
    border:1px solid #d1d5db;
    border-radius:14px;
    box-sizing:border-box;
    background:white;
}

.search-btn {
    width:100%;
    background:#111827;
    color:white;
    border:none;
    padding:14px;
    border-radius:14px;
    font-weight:800;
    cursor:pointer;
}

.vehicle-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(330px,1fr));
    gap:26px;
}

.vehicle-card {
    background:white;
    border-radius:30px;
    overflow:hidden;
    box-shadow:0 12px 32px rgba(0,0,0,.06);
    transition:.25s;
}

.vehicle-card:hover {
    transform:translateY(-6px);
    box-shadow:0 20px 42px rgba(0,0,0,.11);
}

.image-wrap {
    position:relative;
}

.image-wrap img {
    width:100%;
    height:245px;
    object-fit:cover;
    display:block;
}

.price-pill {
    position:absolute;
    left:16px;
    bottom:16px;
    background:white;
    padding:10px 14px;
    border-radius:16px;
    font-weight:900;
    box-shadow:0 8px 18px rgba(0,0,0,.16);
}

.price-pill span {
    color:#6b7280;
    font-size:12px;
    font-weight:600;
}

.fav-form {
    position:absolute;
    top:16px;
    right:16px;
}

.fav-btn {
    width:44px;
    height:44px;
    border:none;
    border-radius:50%;
    background:white;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(0,0,0,.16);
    font-size:18px;
}

.card-body {
    padding:22px;
}

.card-top {
    display:flex;
    justify-content:space-between;
    gap:14px;
    margin-bottom:16px;
}

.card-top h2 {
    margin:0 0 5px;
    font-size:24px;
    font-weight:900;
}

.card-top p {
    margin:0;
    color:#6b7280;
}

.rating-pill {
    background:#f9fafb;
    border-radius:14px;
    padding:8px 10px;
    height:max-content;
    font-weight:800;
    white-space:nowrap;
}

.badge-row {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:18px;
}

.badge-row span {
    background:#f3f4f6;
    padding:8px 11px;
    border-radius:999px;
    font-size:13px;
    color:#374151;
    font-weight:600;
}

.description {
    color:#4b5563;
    line-height:1.7;
    min-height:58px;
    margin-bottom:20px;
}

.actions {
    display:flex;
    gap:12px;
}

.dark-action,
.light-action {
    flex:1;
    text-align:center;
    text-decoration:none;
    padding:13px;
    border-radius:14px;
    font-weight:800;
}

.dark-action {
    background:#111827;
    color:white;
}

.light-action {
    background:#f3f4f6;
    color:#111827;
}

.empty-card {
    background:white;
    border-radius:32px;
    padding:70px 30px;
    text-align:center;
    box-shadow:0 12px 32px rgba(0,0,0,.06);
}

.empty-card div {
    font-size:58px;
}

.empty-card a {
    display:inline-block;
    margin-top:18px;
    background:#111827;
    color:white;
    padding:13px 18px;
    border-radius:14px;
    text-decoration:none;
    font-weight:800;
}
</style>

@endsection