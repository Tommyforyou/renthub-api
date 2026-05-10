<!DOCTYPE html>
<html>
<head>
    <title>RentHub</title>

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f5f5f5;
        }

        .layout{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:250px;
            background:#111827;
            color:white;
            padding:25px;
        }

        .logo{
            font-size:24px;
            font-weight:bold;
            margin-bottom:35px;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            margin-bottom:14px;
            padding:10px;
            border-radius:8px;
        }

        .sidebar a:hover{
            background:#374151;
        }

        .main{
            flex:1;
            padding:40px;
        }

        .topbar {
            margin-bottom:28px;
        }

        .premium-topbar {
            background:linear-gradient(135deg,#111827 0%, #1f2937 55%, #374151 100%);
            padding:14px 18px;
            border-radius:22px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:18px;
            flex-wrap:wrap;
            color:white;
            box-shadow:0 10px 28px rgba(17,24,39,0.18);
        }

        .topbar-left {
            display:flex;
            align-items:center;
            gap:16px;
        }

        .user-avatar {
            width:46px;
            height:46px;
            border-radius:18px;
            background:rgba(255,255,255,0.14);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
            font-weight:800;
        }

        .topbar-company {
            font-size:18px;
            font-weight:800;
            margin-bottom:6px;
        }

        .topbar-meta {
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
            color:rgba(255,255,255,0.78);
            font-size:13px;
        }

        .role-pill {
            background:#22c55e;
            color:white;
            padding:6px 10px;
            border-radius:999px;
            font-weight:700;
        }

        .topbar-actions {
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
        }

        .topbar-link {
            color:white;
            text-decoration:none;
            background:rgba(255,255,255,0.12);
            padding:9px 13px;
            border-radius:14px;
            font-weight:700;
        }

        .topbar-link:hover {
            background:rgba(255,255,255,0.20);
        }

        .logout-btn {
            background:#ef4444;
            color:white;
            border:none;
            padding:9px 14px;
            border-radius:14px;
            cursor:pointer;
            font-weight:700;
        }

        .success{
            background:#dcfce7;
            color:#166534;
            padding:15px;
            border-radius:8px;
            margin-bottom:20px;
            border:1px solid #86efac;
        }

        .error{
            background:#fee2e2;
            color:#991b1b;
            padding:15px;
            border-radius:8px;
            margin-bottom:20px;
            border:1px solid #fca5a5;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));
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
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="logo">
            RentHub
        </div>

        <a href="{{ url('/cars') }}">Browse Cars</a>

        @auth

            <a href="{{ url('/notifications') }}">
                Notifications

                @if(auth()->user()->unreadNotifications->count() > 0)
                    ({{ auth()->user()->unreadNotifications->count() }})
                @endif
            </a>

            @if(auth()->user()->role === 'admin')

                <a href="{{ url('/admin/dashboard') }}">Admin Dashboard</a>
                <a href="{{ url('/admin/companies') }}">Company Approvals</a>

            @endif

            @if(auth()->user()->role === 'rental_company')

                <a href="{{ url('/company/dashboard') }}">Company Dashboard</a>
                <a href="{{ url('/company/vehicles') }}">My Vehicles</a>
                <a href="{{ url('/vehicles/create') }}">Add Vehicle</a>
                <a href="{{ url('/company/bookings') }}">Company Bookings</a>
                <a href="{{ url('/company/calendar') }}">Fleet Calendar</a>

            @endif

            <a href="{{ url('/my-bookings') }}">My Bookings</a>
            <a href="{{ url('/favorites') }}">My Favorites</a>

        @else

            <a href="{{ url('/login') }}">Login</a>
            <a href="{{ url('/register') }}">Register</a>

        @endauth

    </aside>

    <main class="main">

        <div class="topbar premium-topbar">

            <div class="topbar-left">

                @auth
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <div class="topbar-company">
                            {{ auth()->user()->rentalCompany->company_name ?? auth()->user()->name }}
                        </div>

                        <div class="topbar-meta">
                            <span>👤 {{ auth()->user()->name }}</span>
                            <span class="role-pill">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'customer')) }}
                            </span>
                        </div>
                    </div>
                @else
                    <div>
                        <div class="topbar-company">Welcome to RentHub</div>
                        <div class="topbar-meta">Find and rent vehicles with confidence.</div>
                    </div>
                @endauth

            </div>

            @auth
                <div class="topbar-actions">

                    @if(auth()->user()->role === 'rental_company')
                        <a href="{{ route('company.dashboard') }}" class="topbar-link">Dashboard</a>
                        <a href="{{ route('company.bookings') }}" class="topbar-link">Bookings</a>
                        <a href="{{ route('company.calendar') }}" class="topbar-link">Calendar</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf

                        <button class="logout-btn" type="submit">
                            Logout
                        </button>
                    </form>

                </div>
            @endauth

        </div>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')

    </main>

</div>
<footer class="renthub-footer">

    <div class="footer-inner">

        <div class="footer-brand">
            <div class="footer-logo">R</div>

            <div>
                <div class="footer-title">RentHub</div>
                <div class="footer-subtitle">
                    Premium car rental marketplace for Mauritius.
                </div>
            </div>
        </div>

        <div class="footer-links">
            <a href="{{ route('cars.index') }}">Browse Cars</a>

            @guest
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('login') }}">Login</a>
            @endguest

            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @endauth
        </div>

        <div class="footer-credit">
            <div>© {{ date('Y') }} RentHub. All rights reserved.</div>
            <div>
                Developed by
                <strong>IOSA Technologies Ltd</strong>
            </div>
        </div>

    </div>

    </footer>

    <style>
    .renthub-footer {
        margin-top:70px;
        background:linear-gradient(135deg,#111827 0%,#1f2937 60%,#374151 100%);
        color:white;
        padding:34px 20px;
        border-top:1px solid rgba(255,255,255,0.08);
    }

    .footer-inner {
        max-width:1450px;
        margin:0 auto;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:28px;
        flex-wrap:wrap;
    }

    .footer-brand {
        display:flex;
        align-items:center;
        gap:14px;
    }

    .footer-logo {
        width:52px;
        height:52px;
        border-radius:18px;
        background:white;
        color:#111827;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:24px;
        font-weight:900;
    }

    .footer-title {
        font-size:22px;
        font-weight:900;
    }

    .footer-subtitle {
        color:rgba(255,255,255,0.72);
        font-size:14px;
        margin-top:4px;
    }

    .footer-links {
        display:flex;
        gap:12px;
        flex-wrap:wrap;
    }

    .footer-links a {
        color:white;
        text-decoration:none;
        background:rgba(255,255,255,0.10);
        padding:10px 14px;
        border-radius:14px;
        font-weight:700;
        font-size:14px;
    }

    .footer-links a:hover {
        background:rgba(255,255,255,0.18);
    }

    .footer-credit {
        text-align:right;
        color:rgba(255,255,255,0.72);
        font-size:14px;
        line-height:1.7;
    }

    .footer-credit strong {
        color:white;
    }

    @media (max-width:800px) {
        .footer-inner {
            align-items:flex-start;
            flex-direction:column;
        }

        .footer-credit {
            text-align:left;
        }
    }
</style>
</body>
</html>