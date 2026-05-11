<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RentHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #111827;
        }

        a { transition: .2s; }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #111827;
            color: white;
            padding: 26px 22px;
            flex-shrink: 0;
        }

        .logo {
            font-size: 26px;
            font-weight: 900;
            margin-bottom: 34px;
            letter-spacing: -0.5px;
        }

        .sidebar-section {
            margin-bottom: 22px;
        }

        .sidebar-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 10px;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .sidebar a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            text-decoration: none;
            margin-bottom: 9px;
            padding: 11px 12px;
            border-radius: 12px;
            font-weight: 700;
        }

        .sidebar a:hover {
            background: #374151;
        }

        .side-badge {
            background: #ef4444;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 999px;
            font-weight: 900;
        }

        .main {
            flex: 1;
            padding: 32px;
            min-width: 0;
        }

        .premium-topbar {
            background: linear-gradient(135deg,#111827 0%, #1f2937 55%, #374151 100%);
            padding: 15px 18px;
            border-radius: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            color: white;
            box-shadow: 0 12px 32px rgba(17,24,39,0.20);
            margin-bottom: 28px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 18px;
            background: rgba(255,255,255,0.14);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
        }

        .topbar-company {
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 6px;
        }

        .topbar-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            color: rgba(255,255,255,0.78);
            font-size: 13px;
        }

        .role-pill {
            background: #22c55e;
            color: white;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 800;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topbar-link {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.12);
            padding: 9px 13px;
            border-radius: 14px;
            font-weight: 800;
        }

        .topbar-link:hover {
            background: rgba(255,255,255,0.20);
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 800;
        }

        .notify-wrap {
            position: relative;
        }

        .notify-wrap summary {
            list-style: none;
            cursor: pointer;
        }

        .notify-wrap summary::-webkit-details-marker {
            display: none;
        }

        .notify-btn {
            width: 43px;
            height: 43px;
            border-radius: 15px;
            border: none;
            background: rgba(255,255,255,0.12);
            color: white;
            font-size: 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notify-btn:hover {
            background: rgba(255,255,255,0.20);
        }

        .notify-count {
            position: absolute;
            top: -7px;
            right: -7px;
            background: #ef4444;
            color: white;
            min-width: 21px;
            height: 21px;
            padding: 0 6px;
            border-radius: 999px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            border: 2px solid #111827;
        }

        .notify-menu {
            position: absolute;
            right: 0;
            top: 52px;
            width: 380px;
            max-height: 500px;
            overflow-y: auto;
            background: white;
            color: #111827;
            border-radius: 20px;
            box-shadow: 0 24px 50px rgba(15,23,42,.25);
            border: 1px solid #e5e7eb;
            z-index: 50;
            padding: 12px;
        }

        .notify-head {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 900;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .notify-head a {
            color: #2563eb;
            text-decoration: none;
            font-size: 12px;
            font-weight: 900;
        }

        .notify-item {
            display: block;
            text-decoration: none;
            color: #111827;
            padding: 13px;
            border-radius: 14px;
            margin-top: 8px;
            background: #f8fafc;
            border: 1px solid transparent;
        }

        .notify-item:hover {
            background: #eef2ff;
        }

        .notify-item.unread {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .notify-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .notify-icon {
            font-size: 20px;
            width: 26px;
            flex-shrink: 0;
        }

        .notify-body {
            flex: 1;
        }

        .notify-title {
            font-weight: 900;
            margin-bottom: 4px;
        }

        .notify-msg {
            color: #64748b;
            font-size: 13px;
            line-height: 1.4;
        }

        .notify-time {
            color: #94a3b8;
            font-size: 12px;
            margin-top: 6px;
        }

        .notify-dot {
            width: 9px;
            height: 9px;
            background: #ef4444;
            border-radius: 999px;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .notify-empty {
            padding: 22px;
            text-align: center;
            color: #64748b;
            font-weight: 700;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 14px;
            margin-bottom: 20px;
            border: 1px solid #86efac;
            font-weight: 800;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 14px;
            margin-bottom: 20px;
            border: 1px solid #fca5a5;
            font-weight: 800;
        }

        .renthub-footer {
            margin-top: 70px;
            background: linear-gradient(135deg,#111827 0%,#1f2937 60%,#374151 100%);
            color: white;
            padding: 34px 20px;
            border-radius: 26px;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 28px;
            flex-wrap: wrap;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .footer-logo {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: white;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
        }

        .footer-title {
            font-size: 22px;
            font-weight: 900;
        }

        .footer-subtitle {
            color: rgba(255,255,255,0.72);
            font-size: 14px;
            margin-top: 4px;
        }

        .footer-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.10);
            padding: 10px 14px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 14px;
        }

        .footer-links a:hover {
            background: rgba(255,255,255,0.20);
        }

        .footer-credit {
            text-align: right;
            color: rgba(255,255,255,0.72);
            font-size: 14px;
            line-height: 1.7;
        }

        .footer-credit strong {
            color: white;
        }

        @media(max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .main {
                padding: 20px;
            }

            .notify-menu {
                right: -90px;
                width: 330px;
            }

            .footer-credit {
                text-align: left;
            }
        }
    </style>
</head>

<body>

@auth
    @php
        $user = auth()->user();

        $unreadCount = $user->unreadNotifications()->count();

        $recentNotifications = $user
            ->notifications()
            ->latest()
            ->take(5)
            ->get();
    @endphp
@endauth

<div class="layout">

    <aside class="sidebar">

        <div class="logo">RentHub</div>

        <div class="sidebar-section">
            <div class="sidebar-title">Marketplace</div>

            @if(Route::has('cars.index'))
                <a href="{{ route('cars.index') }}">Browse Cars</a>
            @else
                <a href="{{ url('/cars') }}">Browse Cars</a>
            @endif
        </div>

        @auth

            @if($user->role === 'admin')
                <div class="sidebar-section">
                    <div class="sidebar-title">Admin</div>

                    <a href="{{ url('/admin/dashboard') }}">Admin Dashboard</a>
                    <a href="{{ url('/admin/companies') }}">Company Approvals</a>
                </div>
            @endif

            @if($user->role === 'rental_company')
                <div class="sidebar-section">
                    <div class="sidebar-title">Company</div>

                    <a href="{{ url('/company/dashboard') }}">Company Dashboard</a>
                    <a href="{{ url('/company/vehicles') }}">My Vehicles</a>
                    <a href="{{ url('/vehicles/create') }}">Add Vehicle</a>
                    <a href="{{ url('/company/bookings') }}">Company Bookings</a>
                    <a href="{{ url('/company/calendar') }}">Fleet Calendar</a>
                </div>
            @endif

            <div class="sidebar-section">

                <div class="sidebar-title">
                    Customer
                </div>

                {{-- My Bookings --}}
                <a href="{{ route('bookings.my') }}">
                    🚘 My Bookings
                </a>

                {{-- Payment History --}}
                <a href="{{ route('payments.index') }}">
                    💳 Payments
                </a>

                {{-- Favorites --}}
                <a href="{{ route('favorites.index') }}">
                    ❤️ My Favorites
                </a>

                {{-- Notifications --}}
                <a href="{{ route('notifications.index') }}">
                    🔔 Notifications

                    @if(auth()->user()->unreadNotifications->count() > 0)

                        ({{ auth()->user()->unreadNotifications->count() }})

                    @endif
                </a>

            </div>

        @else

            <div class="sidebar-section">
                <div class="sidebar-title">Account</div>

                <a href="{{ url('/login') }}">Login</a>
                <a href="{{ url('/register') }}">Register</a>
            </div>

        @endauth

    </aside>

    <main class="main">

        <div class="premium-topbar">

            <div class="topbar-left">

                @auth
                    <div class="user-avatar">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div>
                        <div class="topbar-company">
                            {{ $user->rentalCompany->company_name ?? $user->name }}
                        </div>

                        <div class="topbar-meta">
                            <span>👤 {{ $user->name }}</span>

                            <span class="role-pill">
                                {{ ucfirst(str_replace('_', ' ', $user->role ?? 'customer')) }}
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

                    <details class="notify-wrap">
                        <summary>
                            <div class="notify-btn">
                                🔔

                                @if($unreadCount > 0)
                                    <span class="notify-count">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </summary>

                        <div class="notify-menu">

                            <div class="notify-head">
                                <span>Notifications</span>

                                @if(Route::has('notifications.index'))
                                    <a href="{{ route('notifications.index') }}">View all</a>
                                @else
                                    <a href="{{ url('/notifications') }}">View all</a>
                                @endif
                            </div>

                            @forelse($recentNotifications as $notification)

                                @php
                                    $message = $notification->data['message'] ?? 'New notification';
                                    $vehicle = $notification->data['vehicle'] ?? null;
                                    $status = $notification->data['status'] ?? null;
                                    $isUnread = is_null($notification->read_at);

                                    $icon = match($status) {
                                        'confirmed' => '✅',
                                        'cancelled' => '❌',
                                        'pending' => '⏳',
                                        'completed' => '🚘',
                                        'rejected' => '❌',
                                        default => '🔔',
                                    };
                                @endphp

                                @if(Route::has('notifications.index'))
                                    <a href="{{ route('notifications.index') }}"
                                       class="notify-item {{ $isUnread ? 'unread' : '' }}">
                                @else
                                    <a href="{{ url('/notifications') }}"
                                       class="notify-item {{ $isUnread ? 'unread' : '' }}">
                                @endif

                                    <div class="notify-row">

                                        <div class="notify-icon">
                                            {{ $icon }}
                                        </div>

                                        <div class="notify-body">

                                            <div class="notify-title">
                                                {{ $message }}
                                            </div>

                                            @if($vehicle)
                                                <div class="notify-msg">
                                                    Vehicle: {{ $vehicle }}
                                                </div>
                                            @endif

                                            @if($status)
                                                <div class="notify-msg">
                                                    Status: {{ ucfirst($status) }}
                                                </div>
                                            @endif

                                            <div class="notify-time">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>

                                        </div>

                                        @if($isUnread)
                                            <span class="notify-dot"></span>
                                        @endif

                                    </div>

                                </a>

                            @empty

                                <div class="notify-empty">
                                    No notifications yet
                                </div>

                            @endforelse

                        </div>
                    </details>

                    @if($user->role === 'rental_company')

                        @if(Route::has('company.dashboard'))
                            <a href="{{ route('company.dashboard') }}" class="topbar-link">Dashboard</a>
                        @else
                            <a href="{{ url('/company/dashboard') }}" class="topbar-link">Dashboard</a>
                        @endif

                        @if(Route::has('company.bookings'))
                            <a href="{{ route('company.bookings') }}" class="topbar-link">Bookings</a>
                        @else
                            <a href="{{ url('/company/bookings') }}" class="topbar-link">Bookings</a>
                        @endif

                        @if(Route::has('company.calendar'))
                            <a href="{{ route('company.calendar') }}" class="topbar-link">Calendar</a>
                        @else
                            <a href="{{ url('/company/calendar') }}" class="topbar-link">Calendar</a>
                        @endif

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

        @if(session('error'))
            <div class="error">
                {{ session('error') }}
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

                    @if(Route::has('cars.index'))
                        <a href="{{ route('cars.index') }}">Browse Cars</a>
                    @else
                        <a href="{{ url('/cars') }}">Browse Cars</a>
                    @endif

                    @guest
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @else
                            <a href="{{ url('/register') }}">Register</a>
                        @endif

                        @if(Route::has('login'))
                            <a href="{{ route('login') }}">Login</a>
                        @else
                            <a href="{{ url('/login') }}">Login</a>
                        @endif
                    @endguest

                    @auth
                        @if(Route::has('dashboard'))
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @endif
                    @endauth

                </div>

                <div class="footer-credit">
                    <div>© {{ date('Y') }} RentHub. All rights reserved.</div>

                    <div>
                        Developed by <strong>IOSA Technologies Ltd</strong>
                    </div>
                </div>

            </div>

        </footer>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</body>
</html>