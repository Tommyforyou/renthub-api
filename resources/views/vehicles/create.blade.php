@extends('layouts.app')

@section('content')

<div style="max-width:1100px; margin:0 auto; padding:30px 20px;">

    <div style="margin-bottom:30px;">
        <h1 style="font-size:34px; font-weight:800; margin-bottom:8px;">
            Add New Vehicle
        </h1>
        <p style="color:#6b7280; font-size:15px;">
            Add your vehicle details, cover image, and gallery photos for customers to view.
        </p>
    </div>

    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; padding:16px; border-radius:12px; margin-bottom:20px;">
            <strong>Please fix the following:</strong>
            <ul style="margin-top:8px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('vehicles.store') }}"
          enctype="multipart/form-data">

        @csrf

        <div style="
            display:grid;
            grid-template-columns: 2fr 1fr;
            gap:24px;
            align-items:start;
        ">

            {{-- LEFT SIDE --}}
            <div style="
                background:white;
                padding:28px;
                border-radius:20px;
                box-shadow:0 10px 25px rgba(0,0,0,0.06);
            ">

                <h2 style="font-size:22px; margin-bottom:20px;">
                    Vehicle Information
                </h2>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                    <div>
                        <label>Vehicle Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-input" required>
                    </div>

                    <div>
                        <label>Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" class="form-input" required>
                    </div>

                    <div>
                        <label>Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" class="form-input" required>
                    </div>

                    <div>
                        <label>Year</label>
                        <input type="number" name="year" value="{{ old('year') }}" class="form-input">
                    </div>

                    <div>
                        <label>Transmission</label>
                        <select name="transmission" class="form-input">
                            <option value="">Select transmission</option>
                            <option value="Automatic">Automatic</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>

                    <div>
                        <label>Fuel Type</label>
                        <select name="fuel_type" class="form-input">
                            <option value="">Select fuel type</option>
                            <option value="Petrol">Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Electric">Electric</option>
                        </select>
                    </div>

                    <div>
                        <label>Seats</label>
                        <input type="number" name="seats" value="{{ old('seats') }}" class="form-input">
                    </div>

                    <div>
                        <label>Price Per Day</label>
                        <input type="number" name="price_per_day" value="{{ old('price_per_day') }}" class="form-input" required>
                    </div>

                </div>

                <div style="margin-top:20px;">
                    <label>Description</label>
                    <textarea name="description" rows="5" class="form-input">{{ old('description') }}</textarea>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div style="
                background:white;
                padding:24px;
                border-radius:20px;
                box-shadow:0 10px 25px rgba(0,0,0,0.06);
            ">

                <h2 style="font-size:22px; margin-bottom:20px;">
                    Vehicle Images
                </h2>

                <div style="margin-bottom:22px;">
                    <label>Cover Image</label>
                    <input type="file" name="image" accept="image/*" class="form-file">
                    <small style="color:#6b7280;">Main image used on listing cards.</small>
                </div>

                <div>
                    <label>Gallery Images</label>
                    <input
                        type="file"
                        name="gallery_images[]"
                        multiple
                        accept="image/*"
                        id="galleryInput"
                        class="form-file"
                    >
                    <small style="color:#6b7280;">Upload multiple photos of the vehicle.</small>

                    <div id="galleryPreview" style="
                        margin-top:18px;
                        display:grid;
                        grid-template-columns:repeat(2, 1fr);
                        gap:12px;
                    "></div>
                </div>

            </div>

        </div>

        <div style="margin-top:28px; display:flex; justify-content:flex-end; gap:12px;">
            <a href="{{ route('company.vehicles') }}"
               style="padding:13px 20px; border-radius:12px; background:#e5e7eb; color:#111827; text-decoration:none;">
                Cancel
            </a>

            <button type="submit"
                    style="padding:13px 24px; border-radius:12px; background:#111827; color:white; border:none; cursor:pointer;">
                Add Vehicle
            </button>
        </div>

    </form>

</div>

<style>
    body {
        background:#f3f4f6;
    }

    label {
        display:block;
        font-weight:600;
        margin-bottom:7px;
        color:#111827;
    }

    .form-input {
        width:100%;
        padding:12px 14px;
        border:1px solid #d1d5db;
        border-radius:12px;
        font-size:15px;
        background:white;
        box-sizing:border-box;
    }

    .form-input:focus {
        outline:none;
        border-color:#111827;
        box-shadow:0 0 0 3px rgba(17,24,39,0.08);
    }

    .form-file {
        width:100%;
        padding:13px;
        border:1px dashed #9ca3af;
        border-radius:14px;
        background:#f9fafb;
        box-sizing:border-box;
        margin-bottom:8px;
    }

    @media (max-width: 900px) {
        form > div {
            grid-template-columns:1fr !important;
        }

        .form-grid {
            grid-template-columns:1fr !important;
        }
    }
</style>

<script>
document.getElementById('galleryInput').addEventListener('change', function(event) {
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
</script>

@endsection