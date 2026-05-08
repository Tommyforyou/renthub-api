<!DOCTYPE html>
<html>
<head>
    <title>Book Vehicle</title>

    <style>
        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        .card{
            max-width:650px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        input{
            width:100%;
            padding:12px;
            margin-top:10px;
            margin-bottom:20px;
        }

        button{
            background:black;
            color:white;
            padding:12px 20px;
            border:none;
            border-radius:8px;
        }

        .price{
            font-size:24px;
            font-weight:bold;
            color:green;
        }
    </style>
</head>
<body>

<div class="card">

    <h1>Book Vehicle</h1>
    @if ($errors->any())
        <div style="
            background:#ffebee;
            color:red;
            padding:15px;
            margin-bottom:20px;
            border-radius:8px;
        ">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach

        </div>
    @endif
    <h2>{{ $vehicle->title }}</h2>

    <p>
        {{ $vehicle->brand }} {{ $vehicle->model }}
    </p>

    <p class="price">
        Rs {{ number_format($vehicle->price_per_day, 2) }}/day
    </p>

    <h3>Unavailable Dates</h3>

    <div style="
        background:#fff3f3;
        padding:15px;
        border-radius:10px;
        margin-bottom:20px;
    ">

        @forelse($bookedDates as $date)

            <div style="margin-bottom:10px;">

                {{ $date->start_date }}
                →
                {{ $date->end_date }}

            </div>

        @empty

            <div>
                No blocked dates.
            </div>

        @endforelse

    </div>


    <form method="POST" action="{{ route('bookings.store', $vehicle->id) }}">
        @csrf

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <button type="submit">
            Confirm Booking Request
        </button>
    </form>

</div>

</body>
</html>