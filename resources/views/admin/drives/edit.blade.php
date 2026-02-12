@extends('layouts.admin')

@section('title', 'Edit Drive')

@section('page', 'drives')

@section('styles')
    .edit-drive-form {
    background-color: #f5f3ed;
    border-radius: 16px;
    padding: 32px;
    max-width: 900px;
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

    .btn-save-drive {
    background-color: var(--relief-vivid-orange);
    border: none;
    color: #ffffff;
    padding: 12px 32px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    transition: background-color 0.2s;
    }

    .btn-save-drive:hover {
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

    .current-cover {
    border-radius: 12px;
    max-height: 200px;
    object-fit: cover;
    width: 100%;
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

    .items-table {
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    }

    .items-table table {
    margin-bottom: 0;
    }

    .items-table th {
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    font-size: 12px;
    font-weight: 600;
    padding: 10px 12px;
    border: none;
    }

    .items-table td {
    font-size: 13px;
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
    }

    .progress-section {
    background: #ffffff;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #d0d0d0;
    }

    .status-select-wrapper {
    position: relative;
    }

    .status-badge-preview {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    }
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 13px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.drives.index') }}"
                            class="text-decoration-none">Drives</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.drives.show', $drive) }}"
                            class="text-decoration-none">{{ Str::limit($drive->name, 20) }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h1 class="page-title mb-0">Edit Drive</h1>
        </div>
        <a href="{{ route('admin.drives.show', $drive) }}" class="btn-header-action">
            <i class="bi bi-arrow-left me-1"></i>Back to Drive
        </a>
    </div>

    <div class="edit-drive-form">
        <form method="POST" action="{{ route('admin.drives.update', $drive) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Cover Photo Section --}}
            <div class="mb-4">
                <label class="form-label-styled">Cover Photo</label>
                @if ($drive->cover_photo)
                    <div class="mb-3">
                        <img src="{{ $drive->cover_photo_url }}" alt="Current cover" class="current-cover">
                    </div>
                @endif
                <div class="cover-upload-area" onclick="document.getElementById('cover_photo').click()"
                    id="cover_upload_area">
                    <i class="bi bi-image"></i>
                    <p class="mb-1 text-muted" style="font-size: 13px;">Click to upload new cover image</p>
                    <small class="text-muted">16:9 ratio recommended • Max 5MB • Leave empty to keep current</small>
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
                    id="name" name="name" value="{{ old('name', $drive->name) }}" placeholder="Enter drive name"
                    required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="form-label-styled">Description *</label>
                <textarea class="form-control form-control-styled @error('description') is-invalid @enderror" id="description"
                    name="description" rows="3" placeholder="Describe the relief drive..." required>{{ old('description', $drive->description) }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Pack Types Selection --}}
            <div class="mb-4">
                <label class="form-label-styled">Pack Types Needed *</label>
                <div class="row g-2">
                    @php
                        $selectedPacks = old('pack_types', $drive->pack_types_needed ?? []);
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

            {{-- Families Affected and Status Row --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="families_affected" class="form-label-styled">Families Affected *</label>
                    <input type="number"
                        class="form-control form-control-styled @error('families_affected') is-invalid @enderror"
                        id="families_affected" name="families_affected"
                        value="{{ old('families_affected', $drive->families_affected ?? 100) }}" min="1" required>
                    @error('families_affected')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Quantities for each item will be calculated using Mother Formula ×
                        families.</small>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label-styled">Status</label>
                    <select class="form-select form-select-styled @error('status') is-invalid @enderror" id="status"
                        name="status">
                        <option value="active" {{ old('status', $drive->status) === 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="completed" {{ old('status', $drive->status) === 'completed' ? 'selected' : '' }}>
                            Completed</option>
                        <option value="closed" {{ old('status', $drive->status) === 'closed' ? 'selected' : '' }}>Closed
                        </option>
                    </select>
                    @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Target Row --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="target_type" class="form-label-styled">Target Type *</label>
                    <select class="form-select form-select-styled @error('target_type') is-invalid @enderror"
                        id="target_type" name="target_type" required>
                        <option value="quantity"
                            {{ old('target_type', $drive->target_type) === 'quantity' ? 'selected' : '' }}>Quantity (Items)
                        </option>
                    </select>
                    @error('target_type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="target_amount" class="form-label-styled">Target Amount *</label>
                    <input type="number"
                        class="form-control form-control-styled @error('target_amount') is-invalid @enderror"
                        id="target_amount" name="target_amount"
                        value="{{ old('target_amount', $drive->target_amount) }}" min="1" step="0.01"
                        required>
                    @error('target_amount')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Date Range --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="start_date" class="form-label-styled">Start Date</label>
                    <input type="date"
                        class="form-control form-control-styled @error('start_date') is-invalid @enderror" id="start_date"
                        name="start_date" value="{{ old('start_date', $drive->start_date?->format('Y-m-d')) }}">
                    @error('start_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label-styled">End Date *</label>
                    <input type="date" class="form-control form-control-styled @error('end_date') is-invalid @enderror"
                        id="end_date" name="end_date" value="{{ old('end_date', $drive->end_date?->format('Y-m-d')) }}"
                        required>
                    @error('end_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Current Progress --}}
            <div class="mb-4">
                <label class="form-label-styled">Current Progress</label>
                <div class="progress-section">
                    @include('partials.progress-bar-3color', [
                        'distributed' => $drive->distributed_percentage,
                        'pledged' => $drive->pledged_percentage,
                        'showLegend' => false,
                    ])
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="d-flex gap-4">
                            <small class="text-muted">
                                <i class="bi bi-square-fill text-success me-1"></i>
                                Distributed: {{ number_format($drive->distributed_amount ?? 0) }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-square-fill text-primary me-1"></i>
                                Pledged: {{ number_format($drive->pledged_amount ?? 0) }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-square-fill text-secondary me-1"></i>
                                Target: {{ number_format($drive->target_amount) }}
                            </small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="recalculateProgress()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Recalculate
                        </button>
                    </div>
                </div>
            </div>

            {{-- Current Drive Items --}}
            @if ($drive->driveItems->count() > 0)
                <div class="mb-4">
                    <label class="form-label-styled">Current Items ({{ $drive->driveItems->count() }} items)</label>
                    <div class="items-table" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Needed</th>
                                    <th class="text-end">Pledged</th>
                                    <th class="text-end">Distributed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drive->driveItems as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td class="text-end">{{ number_format($item->quantity_needed, 2) }}
                                            {{ $item->unit }}</td>
                                        <td class="text-end">{{ number_format($item->quantity_pledged, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->quantity_distributed, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning mt-2 mb-0" style="font-size: 13px;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Changing pack types or families affected will regenerate items. Existing pledges will remain linked.
                    </div>
                </div>
            @endif

            {{-- Location Map --}}
            <div class="mb-4">
                <label class="form-label-styled">Location</label>
                <div class="map-container">
                    <div id="map"></div>
                </div>
                <small class="text-muted d-block mt-2">Click on the map to set the drive location</small>
            </div>

            {{-- Hidden location fields --}}
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $drive->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $drive->longitude) }}">

            {{-- Address --}}
            <div class="mb-4">
                <label for="address" class="form-label-styled">Address</label>
                <input type="text" class="form-control form-control-styled @error('address') is-invalid @enderror"
                    id="address" name="address" value="{{ old('address', $drive->address) }}"
                    placeholder="e.g., Quezon City, Metro Manila">
                @error('address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-save-drive">
                    <i class="bi bi-check-circle me-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.drives.show', $drive) }}" class="btn btn-cancel">Cancel</a>
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
                    document.getElementById('cover_upload_area').style.display = 'none';
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

        // Recalculate progress
        function recalculateProgress() {
            fetch('{{ route('admin.drives.recalculate', $drive) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Progress recalculated successfully. Refreshing page...');
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Still reload for form post fallback
                    window.location.reload();
                });
        }

        // Initialize map
        const defaultLat = {{ old('latitude', $drive->latitude ?? 14.5995) }};
        const defaultLng = {{ old('longitude', $drive->longitude ?? 120.9842) }};
        const map = L.map('map').setView([defaultLat, defaultLng], {{ $drive->latitude ? 13 : 6 }});

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        // Set initial marker if exists
        @if ($drive->latitude && $drive->longitude)
            marker = L.marker([{{ $drive->latitude }}, {{ $drive->longitude }}]).addTo(map);
        @endif

        // Click handler
        map.on('click', function(e) {
            const {
                lat,
                lng
            } = e.latlng;

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }

            // Reverse geocode
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('address').value = data.display_name;
                    }
                });
        });
    </script>
@endsection
