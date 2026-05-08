@extends('layouts.app')

@section('content')
    <title>Admin Dashboard</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(240px,1fr));
            gap:20px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .value{
            font-size:32px;
            font-weight:bold;
            margin-top:10px;
        }

    </style>


<h1>Platform Admin Dashboard</h1>

<div class="grid">

    <div class="card">
        <div>Total Platform Revenue</div>
        <div class="value">
            Rs {{ number_format($totalRevenue, 2) }}
        </div>
    </div>

    <div class="card">
        <div>Total Commission</div>
        <div class="value">
            Rs {{ number_format($totalCommission, 2) }}
        </div>
    </div>

    <div class="card">
        <div>Total Bookings</div>
        <div class="value">
            {{ $totalBookings }}
        </div>
    </div>

    <div class="card">
        <div>Confirmed Bookings</div>
        <div class="value">
            {{ $confirmedBookings }}
        </div>
    </div>

    <div class="card">
        <div>Total Customers</div>
        <div class="value">
            {{ $totalCustomers }}
        </div>
    </div>

    <div class="card">
        <div>Total Vehicles</div>
        <div class="value">
            {{ $totalVehicles }}
        </div>
    </div>

    <div class="card">
        <div>Total Companies</div>
        <div class="value">
            {{ $totalCompanies }}
        </div>
    </div>

</div>

@endsection