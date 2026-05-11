@extends('layouts.app')

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | Notifications Page Shell
    |--------------------------------------------------------------------------
    */

    .notifications-page {
        background: #ffffff;
        border-radius: 28px;
        padding: 30px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        border: 1px solid #e5e7eb;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .notifications-title-wrap {
        display: flex;
        gap: 18px;
        align-items: center;
    }

    .notifications-icon-main {
        width: 70px;
        height: 70px;
        border-radius: 22px;
        background: #ede9fe;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .notifications-title {
        font-size: 30px;
        font-weight: 900;
        margin: 0 0 8px;
        color: #0f172a;
    }

    .notifications-subtitle {
        color: #64748b;
        margin: 0;
        font-size: 15px;
    }

    /*
    |--------------------------------------------------------------------------
    | Buttons
    |--------------------------------------------------------------------------
    */

    .notif-btn {
        border: none;
        border-radius: 16px;
        padding: 13px 20px;
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .notif-btn-primary {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: white;
        box-shadow: 0 14px 28px rgba(124, 58, 237, 0.24);
    }

    .notif-btn-outline {
        background: white;
        color: #6d28d9;
        border: 1px solid #7c3aed;
    }

    .notif-btn-outline:hover {
        background: #f5f3ff;
    }

    /*
    |--------------------------------------------------------------------------
    | Layout Grid
    |--------------------------------------------------------------------------
    */

    .notifications-layout {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 24px;
        align-items: start;
    }

    /*
    |--------------------------------------------------------------------------
    | Left Filter Panel
    |--------------------------------------------------------------------------
    */

    .notification-filter-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 16px;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
    }

    .filter-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 12px;
        border-radius: 16px;
        font-weight: 900;
        color: #334155;
        margin-bottom: 8px;
    }

    .filter-item.active {
        background: #f3e8ff;
        color: #6d28d9;
    }

    .filter-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-count {
        background: #f1f5f9;
        color: #475569;
        min-width: 28px;
        height: 28px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 900;
    }

    .filter-item.active .filter-count {
        background: #ede9fe;
        color: #6d28d9;
    }

    .filter-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 14px 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Notification List Panel
    |--------------------------------------------------------------------------
    */

    .notification-list-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
    }

    .notification-list-top {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 22px;
    }

    .sort-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-weight: 700;
        font-size: 14px;
    }

    .sort-pill {
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        border-radius: 14px;
        color: #0f172a;
        background: #ffffff;
        font-weight: 800;
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Card
    |--------------------------------------------------------------------------
    */

    .notification-card {
        background: #ffffff;
        border-radius: 22px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.07);
        padding: 26px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
    }

    .notification-card.unread {
        border-left: 5px solid #7c3aed;
    }

    .notification-card-grid {
        display: grid;
        grid-template-columns: 84px 1fr;
        gap: 24px;
    }

    .notification-card-icon {
        width: 76px;
        height: 76px;
        border-radius: 28px;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        font-weight: 900;
    }

    .notification-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 10px;
    }

    .notification-heading-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .notification-type-badge {
        background: #dcfce7;
        color: #15803d;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .notification-new-badge {
        background: #ede9fe;
        color: #6d28d9;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
    }

    .notification-heading {
        font-size: 20px;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
    }

    .notification-message {
        color: #64748b;
        font-size: 15px;
        line-height: 1.6;
        margin: 12px 0 20px;
    }

    .vehicle-box {
        background: #f5f3ff;
        color: #0f172a;
        border: 1px solid #ede9fe;
        border-radius: 14px;
        padding: 14px 16px;
        font-weight: 900;
        margin-bottom: 18px;
    }

    .vehicle-box span {
        color: #6d28d9;
    }

    .notification-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .notification-time {
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
    }

    .notification-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .caught-up {
        margin-top: 28px;
        text-align: center;
        padding: 42px 20px;
        border-radius: 22px;
        background: #ffffff;
    }

    .caught-up-icon {
        width: 110px;
        height: 110px;
        border-radius: 999px;
        background: #f3e8ff;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        font-size: 46px;
    }

    .caught-up-title {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .caught-up-text {
        color: #64748b;
        margin: 0;
    }

    .empty-notifications-box {
        border: 2px dashed #cbd5e1;
        border-radius: 22px;
        padding: 46px 20px;
        text-align: center;
        background: #f8fafc;
        color: #64748b;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive Design
    |--------------------------------------------------------------------------
    */

    @media(max-width: 950px) {
        .notifications-layout {
            grid-template-columns: 1fr;
        }

        .notification-filter-panel {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .filter-divider {
            display: none;
        }
    }

    @media(max-width: 650px) {
        .notifications-page {
            padding: 20px;
        }

        .notification-card-grid {
            grid-template-columns: 1fr;
        }

        .notification-footer {
            align-items: flex-start;
        }

        .notification-actions {
            width: 100%;
        }

        .notif-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="notifications-page">

    {{--
    |--------------------------------------------------------------------------
    | Page Header
    |--------------------------------------------------------------------------
    --}}

    <div class="notifications-header">
        <div class="notifications-title-wrap">
            <div class="notifications-icon-main">
                🔔
            </div>

            <div>
                <h1 class="notifications-title">Notifications</h1>

                <p class="notifications-subtitle">
                    Booking updates, invoices, payments and platform activity.
                </p>
            </div>
        </div>

        @if($notifications->count() > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf

                <button class="notif-btn notif-btn-primary">
                    ✓ Mark all as read
                </button>
            </form>
        @endif
    </div>

    {{--
    |--------------------------------------------------------------------------
    | Notification Counters
    |--------------------------------------------------------------------------
    --}}

    @php
        $totalCount = $notifications->count();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $readCount = $totalCount - $unreadCount;

        $bookingCount = $notifications->filter(function ($notification) {
            $type = $notification->data['type'] ?? '';
            $status = $notification->data['status'] ?? '';

            return str_contains($type, 'booking')
                || in_array($status, ['pending', 'approved', 'confirmed', 'rejected', 'cancelled', 'completed']);
        })->count();

        $paymentCount = $notifications->filter(function ($notification) {
            $type = $notification->data['type'] ?? '';
            $status = $notification->data['status'] ?? '';

            return str_contains($type, 'payment')
                || in_array($status, ['paid', 'payment_received']);
        })->count();

        $invoiceCount = $notifications->filter(function ($notification) {
            return isset($notification->data['url']);
        })->count();

        $systemCount = max(0, $totalCount - $bookingCount - $paymentCount);
    @endphp

    <div class="notifications-layout">

        {{--
        |--------------------------------------------------------------------------
        | Left Filter Panel
        |--------------------------------------------------------------------------
        | These are visual counters for now. Filtering can be added in the next step.
        --}}

        <aside class="notification-filter-panel">

            <div class="filter-item active">
                <div class="filter-left">
                    <span>▦</span>
                    <span>All Notifications</span>
                </div>
                <span class="filter-count">{{ $totalCount }}</span>
            </div>

            <div class="filter-item">
                <div class="filter-left">
                    <span>📅</span>
                    <span>Bookings</span>
                </div>
                <span class="filter-count">{{ $bookingCount }}</span>
            </div>

            <div class="filter-item">
                <div class="filter-left">
                    <span>💳</span>
                    <span>Payments</span>
                </div>
                <span class="filter-count">{{ $paymentCount }}</span>
            </div>

            <div class="filter-item">
                <div class="filter-left">
                    <span>📄</span>
                    <span>Invoices</span>
                </div>
                <span class="filter-count">{{ $invoiceCount }}</span>
            </div>

            <div class="filter-item">
                <div class="filter-left">
                    <span>⚙</span>
                    <span>System</span>
                </div>
                <span class="filter-count">{{ $systemCount }}</span>
            </div>

            <div class="filter-divider"></div>

            <div class="filter-item">
                <div class="filter-left">
                    <span style="color:#7c3aed;">●</span>
                    <span>Unread</span>
                </div>
                <span class="filter-count">{{ $unreadCount }}</span>
            </div>

            <div class="filter-item">
                <div class="filter-left">
                    <span style="color:#cbd5e1;">●</span>
                    <span>Read</span>
                </div>
                <span class="filter-count">{{ $readCount }}</span>
            </div>

        </aside>

        {{--
        |--------------------------------------------------------------------------
        | Main Notification List
        |--------------------------------------------------------------------------
        --}}

        <section class="notification-list-panel">

            <div class="notification-list-top">
                <div class="sort-box">
                    Sort by:
                    <span class="sort-pill">Newest first</span>
                </div>
            </div>

            @forelse($notifications as $notification)

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | Safe Notification Data
                    |--------------------------------------------------------------------------
                    */

                    $title = $notification->data['title'] ?? null;
                    $message = $notification->data['message'] ?? 'You have a new notification.';
                    $vehicle = $notification->data['vehicle'] ?? null;
                    $status = $notification->data['status'] ?? null;
                    $url = $notification->data['url'] ?? null;
                    $type = $notification->data['type'] ?? 'booking';
                    $isUnread = is_null($notification->read_at);

                    /*
                    |--------------------------------------------------------------------------
                    | Notification Display Mapping
                    |--------------------------------------------------------------------------
                    */

                    $icon = match($status ?? $type) {
                        'confirmed', 'approved', 'booking_confirmed' => '✓',
                        'rejected', 'booking_rejected' => '×',
                        'cancelled', 'booking_cancelled' => '!',
                        'pending', 'new_booking' => '⏳',
                        'paid', 'payment_received' => '💳',
                        'completed' => '🚘',
                        default => '🔔',
                    };

                    $category = match(true) {
                        str_contains($type, 'payment') || in_array($status, ['paid', 'payment_received']) => 'Payment',
                        isset($url) => 'Invoice',
                        str_contains($type, 'booking') || in_array($status, ['pending', 'approved', 'confirmed', 'rejected', 'cancelled', 'completed']) => 'Booking',
                        default => 'System',
                    };

                    $heading = $title ?? match($status) {
                        'confirmed', 'approved' => 'Your booking has been approved.',
                        'rejected' => 'Your booking was rejected.',
                        'cancelled' => 'Your booking was cancelled.',
                        'completed' => 'Rental completed.',
                        'paid' => 'Payment received.',
                        default => 'Notification update',
                    };
                @endphp

                <div class="notification-card {{ $isUnread ? 'unread' : '' }}">

                    <div class="notification-card-grid">

                        {{--
                        |--------------------------------------------------------------------------
                        | Notification Icon
                        |--------------------------------------------------------------------------
                        --}}

                        <div class="notification-card-icon">
                            {{ $icon }}
                        </div>

                        {{--
                        |--------------------------------------------------------------------------
                        | Notification Body
                        |--------------------------------------------------------------------------
                        --}}

                        <div>

                            <div class="notification-card-top">

                                <div>
                                    <div class="notification-heading-row">
                                        <span class="notification-type-badge">
                                            {{ $category }}
                                        </span>

                                        <h2 class="notification-heading">
                                            {{ $heading }}
                                        </h2>
                                    </div>

                                    <p class="notification-message">
                                        {{ $message }}
                                    </p>
                                </div>

                                @if($isUnread)
                                    <span class="notification-new-badge">
                                        New
                                    </span>
                                @endif

                            </div>

                            @if($vehicle)
                                <div class="vehicle-box">
                                    🚘 Vehicle:
                                    <span>{{ $vehicle }}</span>
                                </div>
                            @endif

                            <div class="notification-footer">

                                <div class="notification-time">
                                    🕒
                                    {{ $status ? ucfirst($status) : 'Updated' }}
                                    {{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}
                                </div>

                                <div class="notification-actions">

                                    @if($url)
                                        <a href="{{ $url }}" class="notif-btn notif-btn-outline">
                                            📄 View Invoice
                                        </a>
                                    @endif

                                    @if($isUnread)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf

                                            <button class="notif-btn notif-btn-outline">
                                                ✓ Mark as Read
                                            </button>
                                        </form>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                {{--
                |--------------------------------------------------------------------------
                | Empty Notification State
                |--------------------------------------------------------------------------
                --}}

                <div class="empty-notifications-box">
                    <div style="font-size:48px;margin-bottom:14px;">🔔</div>

                    <h2 style="margin:0 0 8px;color:#0f172a;font-weight:900;">
                        No notifications yet
                    </h2>

                    <p style="margin:0;">
                        Your booking, invoice and payment updates will appear here.
                    </p>
                </div>

            @endforelse

            @if($notifications->count() > 0)
                <div class="caught-up">
                    <div class="caught-up-icon">
                        🔔
                    </div>

                    <div class="caught-up-title">
                        You're all caught up!
                    </div>

                    <p class="caught-up-text">
                        No more notifications to show right now.
                    </p>
                </div>
            @endif

        </section>

    </div>

</div>

@endsection