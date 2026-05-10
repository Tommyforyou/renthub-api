@extends('layouts.app')

@section('content')

<div style="max-width:1200px; margin:0 auto; padding:30px 20px;">

    <div style="margin-bottom:30px;">
        <h1 style="font-size:34px; font-weight:800; margin-bottom:6px;">
            Edit Vehicle
        </h1>

        <p style="color:#6b7280;">
            Update vehicle details, cover image, gallery photos, and availability.
        </p>
    </div>

    @if(session('success'))
        <div style="
            background:#dcfce7;
            color:#166534;
            padding:14px 16px;
            border-radius:12px;
            margin-bottom:20px;
        ">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="
            background:#fee2e2;
            color:#991b1b;
            padding:14px 16px;
            border-radius:12px;
            margin-bottom:20px;
        ">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="
            background:#fee2e2;
            color:#991b1b;
            padding:16px;
            border-radius:12px;
            margin-bottom:20px;
        ">
            <strong>Please fix the following:</strong>
            <ul style="margin-top:8px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- MAIN UPDATE FORM --}}
    <form id="updateVehicleForm"
          method="POST"
          action="{{ route('vehicles.update', $vehicle->id) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div style="
            display:grid;
            grid-template-columns: 2fr 1fr;
            gap:24px;
            align-items:start;
        ">

            {{-- LEFT PANEL --}}
            <div style="
                background:white;
                padding:28px;
                border-radius:22px;
                box-shadow:0 8px 24px rgba(0,0,0,0.06);
            ">

                <h2 style="font-size:22px; margin-bottom:22px;">
                    Vehicle Information
                </h2>

                <div style="
                    display:grid;
                    grid-template-columns:1fr 1fr;
                    gap:18px;
                ">

                    <div>
                        <label>Vehicle Title</label>
                        <input class="form-input"
                               type="text"
                               name="title"
                               value="{{ old('title', $vehicle->title) }}"
                               required>
                    </div>

                    <div>
                        <label>Brand</label>
                        <input class="form-input"
                               type="text"
                               name="brand"
                               value="{{ old('brand', $vehicle->brand) }}"
                               required>
                    </div>

                    <div>
                        <label>Model</label>
                        <input class="form-input"
                               type="text"
                               name="model"
                               value="{{ old('model', $vehicle->model) }}"
                               required>
                    </div>

                    <div>
                        <label>Year</label>
                        <input class="form-input"
                               type="number"
                               name="year"
                               value="{{ old('year', $vehicle->year) }}">
                    </div>

                    <div>
                        <label>Transmission</label>
                        <select name="transmission" class="form-input">
                            <option value="">Select transmission</option>
                            <option value="Automatic" {{ old('transmission', $vehicle->transmission) == 'Automatic' ? 'selected' : '' }}>
                                Automatic
                            </option>
                            <option value="Manual" {{ old('transmission', $vehicle->transmission) == 'Manual' ? 'selected' : '' }}>
                                Manual
                            </option>
                        </select>
                    </div>

                    <div>
                        <label>Fuel Type</label>
                        <select name="fuel_type" class="form-input">
                            <option value="">Select fuel type</option>
                            <option value="Petrol" {{ old('fuel_type', $vehicle->fuel_type) == 'Petrol' ? 'selected' : '' }}>
                                Petrol
                            </option>
                            <option value="Diesel" {{ old('fuel_type', $vehicle->fuel_type) == 'Diesel' ? 'selected' : '' }}>
                                Diesel
                            </option>
                            <option value="Hybrid" {{ old('fuel_type', $vehicle->fuel_type) == 'Hybrid' ? 'selected' : '' }}>
                                Hybrid
                            </option>
                            <option value="Electric" {{ old('fuel_type', $vehicle->fuel_type) == 'Electric' ? 'selected' : '' }}>
                                Electric
                            </option>
                        </select>
                    </div>

                    <div>
                        <label>Seats</label>
                        <input class="form-input"
                               type="number"
                               name="seats"
                               value="{{ old('seats', $vehicle->seats) }}">
                    </div>

                    <div>
                        <label>Price Per Day</label>
                        <input class="form-input"
                               type="number"
                               step="0.01"
                               name="price_per_day"
                               value="{{ old('price_per_day', $vehicle->price_per_day) }}"
                               required>
                    </div>

                </div>

                <div style="margin-top:20px;">
                    <label>Description</label>
                    <textarea class="form-input"
                              rows="6"
                              name="description">{{ old('description', $vehicle->description) }}</textarea>
                </div>

                <div style="
                    margin-top:22px;
                    padding:16px;
                    background:#f9fafb;
                    border-radius:14px;
                    border:1px solid #e5e7eb;
                ">
                    <label style="
                        display:flex;
                        align-items:center;
                        gap:10px;
                        margin-bottom:0;
                        cursor:pointer;
                    ">
                        <input type="checkbox"
                               name="available"
                               {{ old('available', $vehicle->available) ? 'checked' : '' }}>

                        Vehicle Available
                    </label>
                </div>

            </div>

            {{-- RIGHT PANEL --}}
            <div style="
                display:flex;
                flex-direction:column;
                gap:20px;
            ">

                {{-- COVER IMAGE --}}
                <div style="
                    background:white;
                    padding:22px;
                    border-radius:22px;
                    box-shadow:0 8px 24px rgba(0,0,0,0.06);
                ">

                    <h2 style="font-size:22px; margin-bottom:18px;">
                        Cover Image
                    </h2>

                    @if($vehicle->image)
                        <img
                            src="{{ asset('storage/' . $vehicle->image) }}"
                            style="
                                width:100%;
                                height:220px;
                                object-fit:cover;
                                border-radius:16px;
                                margin-bottom:16px;
                                background:#f3f4f6;
                            "
                        >
                    @else
                        <div style="
                            height:220px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            background:#f3f4f6;
                            color:#6b7280;
                            border-radius:16px;
                            margin-bottom:16px;
                        ">
                            No cover image
                        </div>
                    @endif

                    <label>Replace Cover Image</label>
                    <input type="file"
                           name="image"
                           accept="image/*"
                           class="form-file">

                    <small style="color:#6b7280;">
                        This image appears on vehicle listing cards.
                    </small>

                </div>

                {{-- ADD GALLERY IMAGES --}}
                <div style="
                    background:white;
                    padding:22px;
                    border-radius:22px;
                    box-shadow:0 8px 24px rgba(0,0,0,0.06);
                ">

                    <h2 style="font-size:22px; margin-bottom:18px;">
                        Add Gallery Images
                    </h2>

                    <input
                        type="file"
                        name="gallery_images[]"
                        multiple
                        accept="image/*"
                        id="galleryInput"
                        class="form-file"
                    >

                    <small style="color:#6b7280;">
                        Upload more vehicle photos.
                    </small>

                    <div id="galleryPreview" style="
                        margin-top:18px;
                        display:grid;
                        grid-template-columns:repeat(2,1fr);
                        gap:12px;
                    "></div>

                </div>

            </div>

        </div>

        <div style="
            margin-top:28px;
            display:flex;
            justify-content:flex-end;
            gap:14px;
        ">

            <a href="{{ route('company.vehicles') }}"
               style="
                   background:#e5e7eb;
                   color:#111827;
                   padding:13px 20px;
                   border-radius:12px;
                   text-decoration:none;
               ">
                Cancel
            </a>

            <button type="submit"
                    style="
                        background:#111827;
                        color:white;
                        padding:13px 22px;
                        border:none;
                        border-radius:12px;
                        cursor:pointer;
                    ">
                Update Vehicle
            </button>

        </div>

    </form>

    {{-- GALLERY MANAGER OUTSIDE MAIN FORM --}}
    <div style="
        margin-top:28px;
        background:white;
        padding:24px;
        border-radius:22px;
        box-shadow:0 8px 24px rgba(0,0,0,0.06);
    ">

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
            gap:12px;
        ">
            <div>
                <h2 style="font-size:22px; margin-bottom:4px;">
                    Existing Gallery Images
                </h2>

                <p style="color:#6b7280; margin:0;">
                    Manage current gallery photos without affecting the update form.
                </p>
            </div>
        </div>

        @if($vehicle->images && $vehicle->images->count())

            <div style="
                display:grid;
                grid-template-columns:repeat(auto-fill, minmax(210px, 1fr));
                gap:18px;
            ">

                @foreach($vehicle->images as $img)

                    <div style="
                        border:1px solid #e5e7eb;
                        border-radius:18px;
                        overflow:hidden;
                        background:white;
                    ">

                        <img
                            src="{{ asset('storage/' . $img->image_path) }}"
                            style="
                                width:100%;
                                height:160px;
                                object-fit:cover;
                                display:block;
                                background:#f3f4f6;
                            "
                        >

                        <div style="padding:12px;">

                            @if($img->is_primary)

                                <div style="
                                    background:#dcfce7;
                                    color:#166534;
                                    padding:7px 10px;
                                    border-radius:999px;
                                    font-size:12px;
                                    text-align:center;
                                    font-weight:700;
                                    margin-bottom:10px;
                                ">
                                    Primary Image
                                </div>

                            @else

                                <button
                                    type="submit"
                                    form="set-primary-{{ $img->id }}"
                                    style="
                                        width:100%;
                                        margin-bottom:10px;
                                        background:#111827;
                                        color:white;
                                        border:none;
                                        padding:9px;
                                        border-radius:10px;
                                        cursor:pointer;
                                    ">
                                    Set Primary
                                </button>

                            @endif

                            <button
                                type="submit"
                                form="delete-image-{{ $img->id }}"
                                onclick="return confirm('Delete this image?')"
                                style="
                                    width:100%;
                                    background:#dc2626;
                                    color:white;
                                    border:none;
                                    padding:9px;
                                    border-radius:10px;
                                    cursor:pointer;
                                ">
                                Delete
                            </button>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div style="
                background:#f9fafb;
                color:#6b7280;
                padding:24px;
                border-radius:16px;
                text-align:center;
                border:1px dashed #d1d5db;
            ">
                No gallery images uploaded yet.
            </div>

        @endif

    </div>

    {{-- HIDDEN FORMS FOR GALLERY ACTIONS --}}
    @foreach($vehicle->images as $img)

        <form
            id="set-primary-{{ $img->id }}"
            method="POST"
            action="{{ route('vehicles.images.primary', $img->id) }}"
            style="display:none;"
        >
            @csrf
        </form>

        <form
            id="delete-image-{{ $img->id }}"
            method="POST"
            action="{{ route('vehicles.images.delete', $img->id) }}"
            style="display:none;"
        >
            @csrf
            @method('DELETE')
        </form>

    @endforeach

</div>

<style>
    body {
        background:#f3f4f6;
    }

    label {
        display:block;
        margin-bottom:8px;
        font-weight:600;
        color:#111827;
    }

    .form-input {
        width:100%;
        padding:12px 14px;
        border:1px solid #d1d5db;
        border-radius:12px;
        box-sizing:border-box;
        background:white;
        font-size:15px;
    }

    .form-input:focus {
        outline:none;
        border-color:#111827;
        box-shadow:0 0 0 3px rgba(17,24,39,0.08);
    }

    .form-file {
        width:100%;
        padding:12px;
        border:1px dashed #9ca3af;
        border-radius:14px;
        background:#f9fafb;
        box-sizing:border-box;
        margin-bottom:8px;
    }

    button:hover,
    a:hover {
        opacity:0.92;
    }

    @media (max-width: 900px) {
        #updateVehicleForm > div {
            grid-template-columns:1fr !important;
        }
    }
</style>

<script>
    const galleryInput = document.getElementById('galleryInput');

    if (galleryInput) {
        galleryInput.addEventListener('change', function(event) {
            const preview = document.getElementById('galleryPreview');

            preview.innerHTML = '';

            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');

                    div.innerHTML = `
                        <div style="
                            border-radius:14px;
                            overflow:hidden;
                            background:white;
                            box-shadow:0 4px 12px rgba(0,0,0,0.08);
                        ">
                            <img src="${e.target.result}"
                                 style="width:100%; height:120px; object-fit:cover; display:block;">

                            <div style="
                                padding:7px;
                                font-size:11px;
                                color:#374151;
                                background:#f9fafb;
                                white-space:nowrap;
                                overflow:hidden;
                                text-overflow:ellipsis;
                            ">
                                ${file.name}
                            </div>
                        </div>
                    `;

                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        });
    }
</script>

@endsection