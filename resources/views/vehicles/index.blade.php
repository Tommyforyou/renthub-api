@extends('layouts.app')

@section('content')

    <title>Available Cars</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(320px,1fr));
            gap:20px;
        }

        .card{
            background:white;
            border-radius:12px;
            padding:20px;
        }

        .price{
            font-size:24px;
            font-weight:bold;
            color:green;
        }

        .btn{
            display:inline-block;
            margin-top:15px;
            background:black;
            color:white;
            padding:10px 16px;
            text-decoration:none;
            border-radius:8px;
        }

    </style>


<h1>Available Rental Cars</h1>
<form method="GET"
      style="
        background:white;
        padding:20px;
        border-radius:12px;
        margin-bottom:30px;
      ">

    <input type="text"
           name="brand"
           placeholder="Brand"
           value="{{ request('brand') }}"
           style="padding:10px; margin-right:10px;">

    <select name="transmission"
            style="padding:10px; margin-right:10px;">

        <option value="">Transmission</option>

        <option value="Automatic"
            {{ request('transmission') == 'Automatic' ? 'selected' : '' }}>
            Automatic
        </option>

        <option value="Manual"
            {{ request('transmission') == 'Manual' ? 'selected' : '' }}>
            Manual
        </option>

    </select>

    <select name="fuel_type"
            style="padding:10px; margin-right:10px;">

        <option value="">Fuel Type</option>

        <option value="Gasoline"
            {{ request('fuel_type') == 'Gasoline' ? 'selected' : '' }}>
            Gasoline
        </option>

        <option value="Diesel"
            {{ request('fuel_type') == 'Diesel' ? 'selected' : '' }}>
            Diesel
        </option>

        <option value="Hybrid"
            {{ request('fuel_type') == 'Hybrid' ? 'selected' : '' }}>
            Hybrid
        </option>

        <option value="Electric"
            {{ request('fuel_type') == 'Electric' ? 'selected' : '' }}>
            Electric
        </option>

    </select>

    <input type="number"
           name="seats"
           placeholder="Minimum Seats"
           value="{{ request('seats') }}"
           style="padding:10px; margin-right:10px; width:160px;">

    <input type="number"
           name="max_price"
           placeholder="Max Price"
           value="{{ request('max_price') }}"
           style="padding:10px; margin-right:10px; width:140px;">

    <button style="
        background:black;
        color:white;
        padding:10px 18px;
        border:none;
        border-radius:6px;
    ">
        Search
    </button>

</form>

<div class="grid">

@foreach($vehicles as $vehicle)

<div class="card">

    @if($vehicle->image)

        <img
        src="{{ asset('storage/' . $vehicle->image) }}"
        style="
            width:100%;
            height:220px;
            object-fit:cover;
            border-radius:12px;
            margin-bottom:15px;
        "
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
        Year: {{ $vehicle->year }}
    </p>

    <p>
        Transmission: {{ $vehicle->transmission }}
    </p>

    <p>
        Fuel: {{ $vehicle->fuel_type }}
    </p>

    <p>
        Seats: {{ $vehicle->seats }}
    </p>

    <p class="price">
        Rs {{ number_format($vehicle->price_per_day, 2) }}/day
    </p>

    <p>
        {{ $vehicle->description }}
    </p>

    <a href="{{ route('bookings.create', $vehicle->id) }}" class="btn">
        Book Now
    </a>
</div>

@endforeach

</div>

@endsection