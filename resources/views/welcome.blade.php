@extends('layouts.app')

@section('content')

<div class="landing-page">

    {{-- HERO --}}
    <section class="hero-section">

        <div class="hero-overlay"></div>

        <div class="hero-content">

            <div class="hero-left">

                <div class="hero-pill">
                    🚘 Mauritius Premium Car Rental Marketplace
                </div>

                <h1>
                    Find your perfect rental car with RentHub
                </h1>

                <p>
                    Browse premium vehicles, compare prices,
                    manage bookings, and rent securely across Mauritius.
                    Modern mobility for travellers and local drivers.
                </p>

                <div class="hero-actions">

                    <a href="{{ route('cars.index') }}"
                       class="hero-btn-dark">
                        Browse Cars
                    </a>

                    @guest
                        <a href="{{ route('register') }}"
                           class="hero-btn-light">
                            Become a Rental Company
                        </a>
                    @endguest

                </div>

                {{-- SEARCH --}}
                <div class="hero-search">

                    <form action="{{ route('cars.index') }}" method="GET">

                        <div class="search-grid">

                            <div>
                                <label>Brand</label>
                                <input type="text"
                                       name="brand"
                                       placeholder="Toyota, BMW...">
                            </div>

                            <div>
                                <label>Transmission</label>

                                <select name="transmission">
                                    <option value="">Any</option>
                                    <option>Automatic</option>
                                    <option>Manual</option>
                                </select>
                            </div>

                            <div>
                                <label>Fuel Type</label>

                                <select name="fuel_type">
                                    <option value="">Any</option>
                                    <option>Petrol</option>
                                    <option>Diesel</option>
                                    <option>Hybrid</option>
                                    <option>Electric</option>
                                </select>
                            </div>

                            <div>
                                <button type="submit">
                                    Search Cars
                                </button>
                            </div>

                        </div>

                    </form>

                </div>

            </div>

            {{-- HERO IMAGE --}}
            <div class="hero-right">

                <img
                    src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1400&auto=format&fit=crop"
                    alt="Luxury Car"
                >

            </div>

        </div>

    </section>

    {{-- STATS --}}
    <section class="stats-section">

        <div class="stat-card">
            <h2>100+</h2>
            <p>Vehicles Available</p>
        </div>

        <div class="stat-card">
            <h2>24/7</h2>
            <p>Booking Availability</p>
        </div>

        <div class="stat-card">
            <h2>Secure</h2>
            <p>Booking Protection</p>
        </div>

        <div class="stat-card">
            <h2>Premium</h2>
            <p>Rental Experience</p>
        </div>

    </section>

    {{-- FEATURES --}}
    <section class="features-section">

        <div class="section-header">

            <div class="section-pill">
                Why RentHub
            </div>

            <h2>
                Modern car rental experience
            </h2>

            <p>
                Built for customers and rental companies with a premium booking experience.
            </p>

        </div>

        <div class="features-grid">

            <div class="feature-card">

                <div class="feature-icon">
                    🚘
                </div>

                <h3>
                    Premium Vehicles
                </h3>

                <p>
                    Browse a large fleet of modern vehicles from trusted rental companies.
                </p>

            </div>

            <div class="feature-card">

                <div class="feature-icon">
                    📅
                </div>

                <h3>
                    Smart Booking System
                </h3>

                <p>
                    Real-time availability validation with blocked booking protection.
                </p>

            </div>

            <div class="feature-card">

                <div class="feature-icon">
                    ❤️
                </div>

                <h3>
                    Save Favorites
                </h3>

                <p>
                    Save your preferred vehicles and compare options easily.
                </p>

            </div>

            <div class="feature-card">

                <div class="feature-icon">
                    🏢
                </div>

                <h3>
                    Trusted Companies
                </h3>

                <p>
                    View company profiles, fleets, and verified rental providers.
                </p>

            </div>

        </div>

    </section>

    {{-- HOW IT WORKS --}}
    <section class="how-section">

        <div class="section-header">

            <div class="section-pill">
                Simple Process
            </div>

            <h2>
                How RentHub Works
            </h2>

        </div>

        <div class="how-grid">

            <div class="how-card">
                <span>1</span>
                <h3>Browse Vehicles</h3>
                <p>Explore vehicles, compare pricing, and view availability.</p>
            </div>

            <div class="how-card">
                <span>2</span>
                <h3>Book Securely</h3>
                <p>Choose dates and submit secure booking requests instantly.</p>
            </div>

            <div class="how-card">
                <span>3</span>
                <h3>Drive with Confidence</h3>
                <p>Enjoy a premium rental experience with trusted companies.</p>
            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="cta-section">

        <div class="cta-card">

            <div>

                <div class="section-pill">
                    Grow Your Business
                </div>

                <h2>
                    List your vehicles on RentHub
                </h2>

                <p>
                    Join the marketplace and manage bookings,
                    fleet operations, and revenue professionally.
                </p>

            </div>

            @guest
                <a href="{{ route('register') }}"
                   class="hero-btn-dark">
                    Register Company
                </a>
            @endguest

        </div>

    </section>

</div>

<style>

body {
    background:#f3f4f6;
}

/* PAGE */

.landing-page {
    overflow:hidden;
}

/* HERO */

.hero-section {
    position:relative;
    padding:50px 20px 80px;
}

.hero-content {
    max-width:1450px;
    margin:0 auto;
    display:grid;
    grid-template-columns:1.1fr 1fr;
    gap:40px;
    align-items:center;
}

.hero-left h1 {
    font-size:68px;
    line-height:1.05;
    margin:0 0 20px;
    font-weight:900;
    color:#111827;
}

.hero-left p {
    font-size:18px;
    line-height:1.9;
    color:#4b5563;
    max-width:720px;
    margin-bottom:28px;
}

.hero-pill,
.section-pill {
    display:inline-flex;
    background:#111827;
    color:white;
    padding:10px 16px;
    border-radius:999px;
    font-size:14px;
    font-weight:700;
    margin-bottom:20px;
}

.hero-actions {
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    margin-bottom:34px;
}

.hero-btn-dark,
.hero-btn-light {
    text-decoration:none;
    padding:15px 22px;
    border-radius:16px;
    font-weight:800;
}

.hero-btn-dark {
    background:#111827;
    color:white;
}

.hero-btn-light {
    background:white;
    color:#111827;
    border:1px solid #e5e7eb;
}

/* SEARCH */

.hero-search {
    background:white;
    border-radius:30px;
    padding:26px;
    box-shadow:0 18px 44px rgba(0,0,0,.08);
}

.search-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
    align-items:end;
}

.search-grid label {
    display:block;
    margin-bottom:8px;
    font-weight:700;
    color:#111827;
}

.search-grid input,
.search-grid select {
    width:100%;
    padding:14px;
    border:1px solid #d1d5db;
    border-radius:14px;
    box-sizing:border-box;
    background:white;
}

.search-grid button {
    width:100%;
    background:#111827;
    color:white;
    border:none;
    padding:15px;
    border-radius:14px;
    font-weight:800;
    cursor:pointer;
}

/* HERO IMAGE */

.hero-right img {
    width:100%;
    border-radius:36px;
    box-shadow:0 24px 54px rgba(0,0,0,.15);
    object-fit:cover;
}

/* STATS */

.stats-section {
    max-width:1450px;
    margin:0 auto 80px;
    padding:0 20px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:24px;
}

.stat-card {
    background:white;
    border-radius:28px;
    padding:34px;
    text-align:center;
    box-shadow:0 12px 32px rgba(0,0,0,.06);
}

.stat-card h2 {
    font-size:52px;
    margin:0 0 12px;
    font-weight:900;
}

.stat-card p {
    color:#6b7280;
    margin:0;
}

/* FEATURES */

.features-section,
.how-section,
.cta-section {
    max-width:1450px;
    margin:0 auto 90px;
    padding:0 20px;
}

.section-header {
    text-align:center;
    margin-bottom:50px;
}

.section-header h2 {
    font-size:52px;
    margin:0 0 16px;
    font-weight:900;
}

.section-header p {
    color:#6b7280;
    max-width:700px;
    margin:0 auto;
    line-height:1.8;
}

.features-grid,
.how-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:24px;
}

.feature-card,
.how-card {
    background:white;
    border-radius:30px;
    padding:34px;
    box-shadow:0 12px 32px rgba(0,0,0,.06);
}

.feature-icon {
    width:70px;
    height:70px;
    border-radius:22px;
    background:#111827;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:34px;
    margin-bottom:24px;
}

.feature-card h3,
.how-card h3 {
    font-size:28px;
    margin-bottom:14px;
}

.feature-card p,
.how-card p {
    color:#6b7280;
    line-height:1.8;
}

.how-card span {
    width:60px;
    height:60px;
    border-radius:18px;
    background:#111827;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    font-weight:900;
    margin-bottom:24px;
}

/* CTA */

.cta-card {
    background:linear-gradient(135deg,#111827 0%,#1f2937 100%);
    border-radius:36px;
    padding:50px;
    color:white;
    display:flex;
    justify-content:space-between;
    gap:30px;
    align-items:center;
    flex-wrap:wrap;
}

.cta-card h2 {
    font-size:48px;
    margin:0 0 16px;
    font-weight:900;
}

.cta-card p {
    color:rgba(255,255,255,.82);
    max-width:700px;
    line-height:1.8;
}

/* RESPONSIVE */

@media (max-width:1100px) {

    .hero-content {
        grid-template-columns:1fr;
    }

    .hero-left h1 {
        font-size:54px;
    }

}

@media (max-width:700px) {

    .hero-left h1,
    .section-header h2,
    .cta-card h2 {
        font-size:40px;
    }

}

</style>

@endsection