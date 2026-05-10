@extends('layouts.app')

@section('content')

<h1>Notifications</h1>

@forelse($notifications as $notification)

    <div style="
        background:{{ $notification->read_at ? '#ffffff' : '#e0f2fe' }};
        padding:20px;
        border-radius:12px;
        margin-bottom:15px;
        box-shadow:0 2px 8px rgba(0,0,0,0.08);
    ">

        <strong>{{ $notification->data['message'] }}</strong>

        <p>
            Vehicle: {{ $notification->data['vehicle'] ?? 'N/A' }}
        </p>

        <p>
            Status: {{ $notification->data['status'] ?? 'N/A' }}
        </p>

        @if(isset($notification->data['url']))
            <a href="{{ $notification->data['url'] }}">
                View Invoice
            </a>
        @endif

        @if(!$notification->read_at)

            <form method="POST"
                  action="{{ route('notifications.read', $notification->id) }}"
                  style="margin-top:10px;">
                @csrf

                <button style="
                    background:black;
                    color:white;
                    border:none;
                    padding:8px 12px;
                    border-radius:6px;
                ">
                    Mark as Read
                </button>
            </form>

        @endif

    </div>

@empty

    <p>No notifications yet.</p>

@endforelse

@endsection