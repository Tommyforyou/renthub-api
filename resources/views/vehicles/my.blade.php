@extends('layouts.app')

@section('content')

<h1>My Vehicles</h1>

<a href="{{ route('vehicles.create') }}"
   style="display:inline-block; margin-bottom:20px; background:black; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
    Add Vehicle
</a>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px,1fr)); gap:20px;">

@foreach($vehicles as $vehicle)

    <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        @if($vehicle->image)
            <img src="{{ asset('storage/' . $vehicle->image) }}"
                 style="width:100%; height:200px; object-fit:cover; border-radius:10px; margin-bottom:15px;">
        @endif

        <h2>{{ $vehicle->title }}</h2>

        <p>{{ $vehicle->brand }} {{ $vehicle->model }}</p>

        <p>Rs {{ number_format($vehicle->price_per_day, 2) }}/day</p>

        <p>Status: {{ $vehicle->available ? 'Available' : 'Unavailable' }}</p>

        <br>

        <a href="{{ route('vehicles.edit', $vehicle->id) }}"
        style="
                display:inline-block;
                background:orange;
                color:white;
                padding:8px 12px;
                border-radius:6px;
                text-decoration:none;
        ">
            Edit
        </a>
        <a
            href="{{ route('company.vehicles.availability', $vehicle->id) }}"
            style="
                display:inline-block;
                background:#111827;
                color:white;
                padding:8px 12px;
                border-radius:6px;
                text-decoration:none;
                margin-left:8px;
            "
        >
            Availability
        </a>
        <form method="POST"
            action="{{ route('vehicles.destroy', $vehicle->id) }}"
            style="display:inline;"
            onsubmit="return confirm('Delete vehicle?');">

            @csrf
            @method('DELETE')

            <button style="
                background:red;
                color:white;
                border:none;
                padding:8px 12px;
                border-radius:6px;
            ">
                Delete
            </button>

        </form>

    </div>

@endforeach

</div>

@endsection