@extends('layouts.admin')

@section('title', 'Create Drive')

@section('page', 'create-drive')

@section('styles')
    .create-drive-form {
    background-color: #f5f3ed;
    border-radius: 16px;
    padding: 32px;
    max-width: 800px;
    }

    .form-section-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--relief-dark-blue);
    margin-bottom: 8px;
    }

    .form-label-styled {
    font-size: 13px;
    font-weight: 500;
    color: #333;
    margin-bottom: 6px;
    }

    .form-select-styled,
    .form-control-styled {
    border: 1px solid #d0d0d0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    background-color: #ffffff;
    transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-select-styled:focus,
    .form-control-styled:focus {
    border-color: var(--relief-dark-blue);
    box-shadow: 0 0 0 3px rgba(0, 1, 103, 0.1);
    outline: none;
    }

    .map-container {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #d0d0d0;
    }

    #map {
    height: 280px;
    width: 100%;
    }

    .btn-create-drive {
    background-color: var(--relief-vivid-orange);
    border: none;
    color: #ffffff;
    padding: 12px 32px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    transition: background-color 0.2s;
    }

    .btn-create-drive:hover {
    background-color: var(--relief-red);
    color: #ffffff;
    }

    .btn-cancel {
    background-color: transparent;
    border: 1px solid #d0d0d0;
    color: #666;
    padding: 12px 32px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.2s;
    }

    .btn-cancel:hover {
    background-color: #f0f0f0;
    color: #333;
    }

    .cover-upload-area {
    border: 2px dashed #d0d0d0;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    background-color: #ffffff;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
    }

    .cover-upload-area:hover {
    border-color: var(--relief-dark-blue);
    background-color: #fafafa;
    }

    .cover-upload-area i {
    font-size: 32px;
    color: var(--relief-gray-blue);
    margin-bottom: 8px;
    }

    .cover-preview {
    max-width: 100%;
    border-radius: 12px;
    margin-top: 12px;
    }

    .pack-type-card {
    background: #ffffff;
    border: 1px solid #d0d0d0;
    border-radius: 8px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s;
    }

    .pack-type-card:hover {
    border-color: var(--relief-dark-blue);
    }

    .pack-type-card.selected {
    border-color: var(--relief-dark-blue);
    background-color: rgba(0, 1, 103, 0.05);
    }

    .pack-type-card input {
    display: none;
    }
@endsection

@section('content')
    <h1 class="page-title">Create New Drive</h1>

    <div class="create-drive-form">
        <form method="POST" action="{{ route('admin.drives.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Cover Photo Section --}}
            <div class="mb-4">
                <label class="form-label-styled">Cover Photo</label>
                <div class="cover-upload-area" onclick="document.getElementById('cover_photo').click()">
                    <i class="bi bi-image"></i>
                    <p class="mb-1 text-muted" style="font-size: 13px;">Click to upload cover image</p>
                    <small class="text-muted">16:9 ratio recommended • Max 5MB</small>
                </div>
                <input type="file" class="d-none @error('cover_photo') is-invalid @enderror" id="cover_photo"
                    name="cover_photo" accept="image/jpeg,image/png,image/jpg,image/webp">
                @error('cover_photo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div id="cover_preview" style="display: none;">
                    <img id="cover_preview_img" src="" alt="Preview" class="cover-preview"
                        style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                </div>
            </div>

            {{-- Drive Name --}}
            <div class="mb-4">
                <label for="name" class="form-label-styled">Drive Name *</label>
                <input type="text" class="form-control form-control-styled @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}" placeholder="Enter drive name" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="form-label-styled">Description *</label>
                <textarea class="form-control form-control-styled @error('description') is-invalid @enderror" id="description"
                    name="description" rows="3" placeholder="Describe the relief drive..." required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Pack Types Selection --}}
            <div class="mb-4">
                <label class="form-label-styled">Pack Types Needed *</label>
                <div class="row g-2">
                    @php
                        $selectedPacks = old('pack_types', []);
                    @endphp
                    @foreach ($packTypes as $value => $label)
                        <div class="col-md-4 col-6">
                            <label
                                class="pack-type-card d-flex align-items-center gap-2 {{ in_array($value, $selectedPacks) ? 'selected' : '' }}"
                                for="pack_{{ $value }}">
                                <input type="checkbox" name="pack_types[]" value="{{ $value }}"
                                    id="pack_{{ $value }}" {{ in_array($value, $selectedPacks) ? 'checked' : '' }}>
                                <i class="bi bi-box-seam"></i>
                                <span>{{ $label }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('pack_types')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-2">Items will be auto-generated from Mother Formula based on families
                    affected.</small>
            </div>

            {{-- Families Affected and Start Date Row --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="families_affected" class="form-label-styled">Families Affected *</label>
                    <input type="number"
                        class="form-control form-control-styled @error('families_affected') is-invalid @enderror"
                        id="families_affected" name="families_affected" value="{{ old('families_affected', 100) }}"
                        min="1" required>
                    @error('families_affected')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Quantities for each item will be calculated using Mother Formula ×
                        families.</small>
                </div>
                <div class="col-md-6">
                    <label for="start_date" class="form-label-styled">Start Date</label>
                    <input type="date" class="form-control form-control-styled @error('start_date') is-invalid @enderror"
                        id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}">
                    @error('start_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Hidden fields for backend compatibility --}}
            <input type="hidden" name="target_type" value="quantity">
            <input type="hidden" name="target_amount" value="0">
            <input type="hidden" name="end_date" value="{{ now()->addYear()->format('Y-m-d') }}">

            {{-- Location Map --}}
            <div class="mb-4">
                <label class="form-label-styled">Location:</label>
                <div class="map-container">
                    <div id="map"></div>
                </div>
                <small class="text-muted d-block mt-2">Click on the map to set the drive location</small>
            </div>

            {{-- Hidden location fields --}}
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
            <input type="hidden" id="address" name="address" value="{{ old('address') }}">

            {{-- Action Buttons --}}
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-create-drive">
                    <i class="bi bi-plus-circle me-2"></i>Create Drive
                </button>
                <a href="{{ route('admin.drives.index') }}" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Cover photo preview
        document.getElementById('cover_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('cover_preview_img').src = e.target.result;
                    document.getElementById('cover_preview').style.display = 'block';
                    document.querySelector('.cover-upload-area').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Pack type card selection
        document.querySelectorAll('.pack-type-card input').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('.pack-type-card').classList.toggle('selected', this.checked);
            });
        });

        // Initialize map
        const map = L.map('map').setView([14.5995, 120.9842], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Reverse geocode to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('address').value = data.display_name;
                    }
                });
        });

        // Restore marker from old values
        @if (old('latitude') && old('longitude'))
            marker = L.marker([{{ old('latitude') }}, {{ old('longitude') }}]).addTo(map);
            map.setView([{{ old('latitude') }}, {{ old('longitude') }}], 12);
        @endif
    </script>
@endsection
