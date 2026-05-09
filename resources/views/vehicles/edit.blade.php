@extends('layouts.app')

@section('content')

<h1>Edit Vehicle</h1>

<form method="POST"
      action="{{ route('vehicles.update', $vehicle->id) }}"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <input type="text"
           name="title"
           value="{{ $vehicle->title }}"
           placeholder="Title">

    <br><br>

    <input type="text"
           name="brand"
           value="{{ $vehicle->brand }}"
           placeholder="Brand">

    <br><br>

    <input type="text"
           name="model"
           value="{{ $vehicle->model }}"
           placeholder="Model">

    <br><br>

    <input type="number"
           name="year"
           value="{{ $vehicle->year }}"
           placeholder="Year">

    <br><br>

    <input type="text"
           name="transmission"
           value="{{ $vehicle->transmission }}"
           placeholder="Transmission">

    <br><br>

    <input type="text"
           name="fuel_type"
           value="{{ $vehicle->fuel_type }}"
           placeholder="Fuel Type">

    <br><br>

    <input type="number"
           name="seats"
           value="{{ $vehicle->seats }}"
           placeholder="Seats">

    <br><br>

    <input type="number"
           step="0.01"
           name="price_per_day"
           value="{{ $vehicle->price_per_day }}"
           placeholder="Price">

    <br><br>

    <textarea name="description"
              placeholder="Description">{{ $vehicle->description }}</textarea>

    <br><br>

    <label>Replace Image</label><br>
    <input type="file" name="image"><br><br>

    <label>
        <input type="checkbox"
               name="available"
               {{ $vehicle->available ? 'checked' : '' }}>

        Available
    </label>

    <br><br>

    <button style="
        background:black;
        color:white;
        padding:10px 16px;
        border:none;
        border-radius:8px;
    ">
        Update Vehicle
    </button>

</form>

@endsection