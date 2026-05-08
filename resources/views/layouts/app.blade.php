<!DOCTYPE html>
<html>
<head>
    <title>RentHub</title>

    <style>

        body{
            margin:0;
            font-family:Arial;
            background:#f5f5f5;
        }

        .navbar{
            background:#111827;
            color:white;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .logo{
            font-size:24px;
            font-weight:bold;
        }

        .menu a{
            color:white;
            text-decoration:none;
            margin-left:20px;
        }

        .container{
            padding:40px;
        }

        .logout-btn{
            background:red;
            color:white;
            border:none;
            padding:8px 14px;
            border-radius:6px;
            cursor:pointer;
        }

    </style>
</head>
<body>

<div class="navbar">

    <div class="logo">
        RentHub
    </div>

    <div class="menu">

        <a href="/cars">Browse Cars</a>

        @auth

            @if(auth()->user()->role === 'admin')

                <a href="/admin/dashboard">
                    Admin Dashboard
                </a>

                <a href="/admin/companies">
                    Companies
                </a>

            @endif

            @if(auth()->user()->role === 'rental_company')

                <a href="/company/dashboard">
                    Dashboard
                </a>

                <a href="/company/bookings">
                    Bookings
                </a>

                <a href="/vehicles/create">
                    Add Vehicle
                </a>

            @endif

            <a href="/company/vehicles">
                My Vehicles
            </a>
            <a href="/my-bookings">
                My Bookings
            </a>

            <form method="POST"
                  action="{{ route('logout') }}"
                  style="display:inline;">

                @csrf

                <button class="logout-btn">
                    Logout
                </button>

            </form>

        @else

            <a href="/login">Login</a>
            <a href="/register">Register</a>

        @endauth

    </div>

</div>

<div class="container">

    @yield('content')

</div>

</body>
</html>