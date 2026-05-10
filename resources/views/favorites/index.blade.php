@extends('layouts.app')

@section('content')

<h1>My Favorite Vehicles</h1>

@if($favorites->count() === 0)

    <div class="card">
        <p>No favorite vehicles yet.</p>
    </div>

@else

    <div class="grid">

        @foreach($favorites as $favorite)

            @php
                $vehicle = $favorite->vehicle;
            @endphp

            <div class="card">

                @if($vehicle->image)
                    <img
                        src="{{ asset('storage/' . $vehicle->image) }}"
                        style="width:100%; height:220px; object-fit:cover; border-radius:10px;"
                    >
                @endif

                <h2>
                    {{ $vehicle->title }}
                </h2>

                <p>
                    {{ $vehicle->brand }}
                    {{ $vehicle->model }}
                </p>

                <p>
                    Rs {{ number_format($vehicle->price_per_day, 2) }}/day
                </p>

                <div style="margin-top:15px;">

                    <a href="{{ route('cars.show', $vehicle) }}"
                       style="background:#111827; color:white; padding:10px 14px; border-radius:8px; text-decoration:none;">
                        View
                    </a>

                    <form
                        method="POST"
                        action="{{ route('favorites.destroy', $vehicle) }}"
                        style="display:inline-block;"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            style="background:#dc2626; color:white; border:none; padding:10px 14px; border-radius:8px; cursor:pointer;"
                        >
                            Remove
                        </button>
                    </form>

                </div>

            </div>

        @endforeach

    </div>

@endif

@endsection