@extends('layouts.app')

@section('content')

<div style="
    max-width:1200px;
    margin:0 auto;
">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        flex-wrap:wrap;
        margin-bottom:30px;
    ">

        <div>
            <h1 style="
                font-size:34px;
                font-weight:800;
                margin-bottom:6px;
            ">
                Availability Manager
            </h1>

            <div style="color:#6b7280;">
                {{ $vehicle->brand }} {{ $vehicle->model }}
            </div>
        </div>

    </div>

    <div style="
        display:grid;
        grid-template-columns:380px 1fr;
        gap:28px;
        align-items:start;
    " class="availability-grid">

        {{-- CREATE BLOCK --}}
        <div class="card">

            <h2 style="
                font-size:22px;
                margin-bottom:22px;
            ">
                Block Dates
            </h2>

            <form
                method="POST"
                action="{{ route('company.vehicles.availability.store', $vehicle) }}"
            >
                @csrf

                <div class="form-group">
                    <label>From</label>

                    <input
                        type="date"
                        name="blocked_from"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Until</label>

                    <input
                        type="date"
                        name="blocked_until"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Type</label>

                    <select name="type" required>

                        <option value="maintenance">
                            Maintenance
                        </option>

                        <option value="private_use">
                            Private Use
                        </option>

                        <option value="manual">
                            Manual Block
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Reason</label>

                    <textarea
                        name="reason"
                        rows="3"
                        placeholder="Optional note"
                    ></textarea>
                </div>

                <button class="primary-btn">
                    Save Availability Block
                </button>

            </form>

        </div>

        {{-- BLOCK LIST --}}
        <div class="card">

            <h2 style="
                font-size:22px;
                margin-bottom:24px;
            ">
                Existing Blocks
            </h2>

            @if($blocks->count())

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:18px;
                ">

                    @foreach($blocks as $block)

                        <div class="block-card">

                            <div style="
                                display:flex;
                                justify-content:space-between;
                                align-items:start;
                                gap:20px;
                                flex-wrap:wrap;
                            ">

                                <div>

                                    <div class="block-type">
                                        {{ ucfirst(str_replace('_', ' ', $block->type)) }}
                                    </div>

                                    <div class="block-date">
                                        {{ $block->blocked_from->format('d M Y') }}
                                        →
                                        {{ $block->blocked_until->format('d M Y') }}
                                    </div>

                                    @if($block->reason)
                                        <div class="block-reason">
                                            {{ $block->reason }}
                                        </div>
                                    @endif

                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('company.vehicles.availability.destroy', $block) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="danger-btn">
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div style="color:#6b7280;">
                    No blocked dates yet.
                </div>

            @endif

        </div>

    </div>

</div>

<style>

.card{
    background:white;
    padding:28px;
    border-radius:24px;
    box-shadow:0 8px 24px rgba(0,0,0,0.06);
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:700;
}

.form-group input,
.form-group select,
.form-group textarea{
    width:100%;
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    font-size:15px;
    outline:none;
}

.primary-btn{
    width:100%;
    background:#111827;
    color:white;
    border:none;
    padding:16px;
    border-radius:16px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
}

.block-card{
    border:1px solid #f3f4f6;
    border-radius:18px;
    padding:20px;
}

.block-type{
    font-size:18px;
    font-weight:800;
    margin-bottom:8px;
}

.block-date{
    color:#374151;
    margin-bottom:10px;
}

.block-reason{
    color:#6b7280;
    line-height:1.7;
}

.danger-btn{
    background:#fee2e2;
    color:#991b1b;
    border:none;
    padding:10px 16px;
    border-radius:12px;
    cursor:pointer;
    font-weight:700;
}

@media(max-width:900px){

    .availability-grid{
        grid-template-columns:1fr !important;
    }

}

</style>

@endsection