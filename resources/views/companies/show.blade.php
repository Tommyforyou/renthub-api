@extends('layouts.app')

@section('content')

<h1>{{ $company->company_name }}</h1>

<div class="card">
    <h2>Company Information</h2>

    <p><strong>Email:</strong> {{ $company->email }}</p>
    <p><strong>Phone:</strong> {{ $company->phone }}</p>
    <p><strong>Address:</strong> {{ $company->address }}</p>
    <p><strong>Status:</strong> {{ $company->status }}</p>
</div>

<br>

<h2>Vehicles from {{ $company->company_name }}</h2>

<div class="grid">

@forelse($company->vehicles as $vehicle)

    <div class="card">

        @if($vehicle->image)
            <img src="{{ asset('storage/' . $vehicle->image) }}"
                 style="width:100%; height:220px; object-fit:cover; border-radius:10px;">
        @endif

        <h2>{{ $vehicle->title }}</h2>

        <p>{{ $vehicle->brand }} {{ $vehicle->model }}</p>

        <p>Rs {{ number_format($vehicle->price_per_day, 2) }}/day</p>

        @php
            $averageRating = round($vehicle->reviews->avg('rating'), 1);
            $reviewCount = $vehicle->reviews->count();
        @endphp

        @if($reviewCount > 0)
            <p>⭐ {{ $averageRating }}/5 ({{ $reviewCount }} reviews)</p>
        @else
            <p>No reviews yet</p>
        @endif

        <a href="{{ route('cars.show', $vehicle->id) }}"
           style="background:#111827; color:white; padding:10px 14px; border-radius:8px; text-decoration:none;">
            View Vehicle
        </a>

    </div>

@empty

    <div class="card">
        <p>No vehicles listed yet.</p>
    </div>

@endforelse

</div>

@endsection