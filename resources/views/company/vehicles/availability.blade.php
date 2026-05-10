@extends('layouts.app')

@section('content')

<div class="availability-page">

    <div class="availability-header">
        <div>
            <h1>Availability Manager</h1>
            <p>
                Manage blocked dates, maintenance periods and private-use dates for
                <strong>{{ $vehicle->brand }} {{ $vehicle->model }}</strong>.
            </p>
        </div>

        <a href="{{ url('/company/vehicles') }}" class="back-btn">
            ← Back to My Vehicles
        </a>
    </div>

    <div class="availability-grid">

        {{-- CREATE BLOCK --}}
        <div class="availability-card">

            <h2>Block Vehicle Dates</h2>

            <form method="POST" action="{{ route('company.vehicles.availability.store', $vehicle) }}">
                @csrf

                <div class="form-group">
                    <label>Blocked From</label>
                    <input type="date" name="blocked_from" required>
                </div>

                <div class="form-group">
                    <label>Blocked Until</label>
                    <input type="date" name="blocked_until" required>
                </div>

                <div class="form-group">
                    <label>Block Type</label>
                    <select name="type" required>
                        <option value="maintenance">Maintenance</option>
                        <option value="private_use">Private Use</option>
                        <option value="manual">Manual Block</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Reason / Note</label>
                    <textarea name="reason" rows="4" placeholder="Example: Annual servicing, cleaning, private booking..."></textarea>
                </div>

                <button type="submit" class="primary-btn">
                    Save Availability Block
                </button>
            </form>

        </div>

        {{-- EXISTING BLOCKS --}}
        <div class="availability-card">

            <div class="card-header-row">
                <div>
                    <h2>Existing Blocked Periods</h2>
                    <p>{{ $blocks->count() }} blocked period(s)</p>
                </div>
            </div>

            @if($blocks->count())

                <div class="block-list">

                    @foreach($blocks as $block)

                        <div class="block-item">

                            <div class="block-main">

                                <div class="block-badge">
                                    {{ ucfirst(str_replace('_', ' ', $block->type)) }}
                                </div>

                                <div class="block-date">
                                    {{ $block->blocked_from->format('d M Y') }}
                                    <span>→</span>
                                    {{ $block->blocked_until->format('d M Y') }}
                                </div>

                                @if($block->reason)
                                    <div class="block-reason">
                                        {{ $block->reason }}
                                    </div>
                                @else
                                    <div class="block-reason muted">
                                        No reason provided.
                                    </div>
                                @endif

                            </div>

                            <form
                                method="POST"
                                action="{{ route('company.vehicles.availability.destroy', $block) }}"
                                onsubmit="return confirm('Remove this availability block?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="danger-btn">
                                    Delete
                                </button>
                            </form>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h3>No blocked dates yet</h3>
                    <p>This vehicle is currently available unless it has confirmed bookings.</p>
                </div>

            @endif

        </div>

    </div>

</div>

<style>
.availability-page {
    max-width:1250px;
    margin:0 auto;
}

.availability-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

.availability-header h1 {
    font-size:36px;
    font-weight:900;
    margin:0 0 8px;
    color:#111827;
}

.availability-header p {
    margin:0;
    color:#6b7280;
    line-height:1.6;
}

.back-btn {
    background:#111827;
    color:white;
    text-decoration:none;
    padding:12px 16px;
    border-radius:14px;
    font-weight:800;
}

.availability-grid {
    display:grid;
    grid-template-columns:380px 1fr;
    gap:28px;
    align-items:flex-start;
}

.availability-card {
    background:white;
    border-radius:26px;
    padding:28px;
    box-shadow:0 10px 28px rgba(15,23,42,0.08);
    border:1px solid #eef2f7;
}

.availability-card h2 {
    font-size:22px;
    font-weight:900;
    margin:0 0 22px;
    color:#111827;
}

.card-header-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.card-header-row h2 {
    margin-bottom:6px;
}

.card-header-row p {
    margin:0;
    color:#6b7280;
}

.form-group {
    margin-bottom:18px;
}

.form-group label {
    display:block;
    font-weight:800;
    margin-bottom:8px;
    color:#111827;
}

.form-group input,
.form-group select,
.form-group textarea {
    width:100%;
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    font-size:15px;
    outline:none;
    background:white;
    box-sizing:border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color:#111827;
}

.primary-btn {
    width:100%;
    background:#111827;
    color:white;
    border:none;
    padding:16px;
    border-radius:16px;
    font-size:16px;
    font-weight:900;
    cursor:pointer;
}

.primary-btn:hover {
    background:#000;
}

.block-list {
    display:flex;
    flex-direction:column;
    gap:16px;
}

.block-item {
    border:1px solid #eef2f7;
    border-radius:20px;
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:18px;
    flex-wrap:wrap;
    background:#f9fafb;
}

.block-main {
    flex:1;
    min-width:240px;
}

.block-badge {
    display:inline-block;
    background:#e0f2fe;
    color:#075985;
    padding:7px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:900;
    margin-bottom:12px;
}

.block-date {
    font-size:18px;
    font-weight:900;
    color:#111827;
    margin-bottom:10px;
}

.block-date span {
    color:#6b7280;
    margin:0 8px;
}

.block-reason {
    color:#4b5563;
    line-height:1.7;
}

.muted {
    color:#9ca3af;
}

.danger-btn {
    background:#fee2e2;
    color:#991b1b;
    border:none;
    padding:10px 16px;
    border-radius:12px;
    cursor:pointer;
    font-weight:900;
}

.danger-btn:hover {
    background:#fecaca;
}

.empty-state {
    text-align:center;
    padding:50px 20px;
    background:#f9fafb;
    border-radius:22px;
    border:1px dashed #d1d5db;
}

.empty-icon {
    font-size:44px;
    margin-bottom:12px;
}

.empty-state h3 {
    margin:0 0 8px;
    font-size:22px;
    color:#111827;
}

.empty-state p {
    margin:0;
    color:#6b7280;
}

@media(max-width: 950px) {
    .availability-grid {
        grid-template-columns:1fr;
    }
}
</style>

@endsection