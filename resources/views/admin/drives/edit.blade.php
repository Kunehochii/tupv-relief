@extends('layouts.app')

@section('title', 'Edit Drive - Admin')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.drives.index') }}">Drives</a></li>
            <li class="breadcrumb-item active">Edit {{ $drive->name }}</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">Edit Donation Drive</div>
                <div class="card-body">
                    <form action="{{ route('admin.drives.update', $drive) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Cover Photo --}}
                        <div class="mb-4">
                            <label for="cover_photo" class="form-label">Cover Photo</label>
                            @if($drive->cover_photo)
                                <div class="mb-2">
                                    <img src="{{ $drive->cover_photo_url }}" alt="Current cover" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('cover_photo') is-invalid @enderror" 
                                   id="cover_photo" name="cover_photo" accept="image/*">
                            @error('cover_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max 2MB. Leave empty to keep current photo.</small>
                            <div id="coverPreview" class="mt-2"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Drive Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $drive->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $drive->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Pack Types Selection --}}
                        <div class="mb-4">
                            <label class="form-label">Pack Types Needed <span class="text-danger">*</span></label>
                            <div class="row">
                                @php
                                    $packTypes = \App\Models\ReliefPackItem::getPackTypes();
                                    $selectedPacks = old('pack_types', $drive->pack_types_needed ?? []);
                                @endphp
                                @foreach($packTypes as $packType)
                                    <div class="col-md-4 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input pack-type-check" type="checkbox" 
                                                   name="pack_types[]" value="{{ $packType }}" id="pack_{{ Str::slug($packType) }}"
                                                   {{ in_array($packType, $selectedPacks) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pack_{{ Str::slug($packType) }}">
                                                {{ $packType }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('pack_types')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Items will be auto-generated from Mother Formula based on families affected.</small>
                        </div>
                        
                        {{-- Families Affected --}}
                        <div class="mb-4">
                            <label for="families_affected" class="form-label">Families Affected <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('families_affected') is-invalid @enderror" 
                                   id="families_affected" name="families_affected" 
                                   value="{{ old('families_affected', $drive->families_affected ?? 100) }}" min="1" required>
                            @error('families_affected')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Quantities for each item will be calculated using Mother Formula × families.</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="target_type" class="form-label">Target Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('target_type') is-invalid @enderror" 
                                        id="target_type" name="target_type" required>
                                    <option value="financial" {{ old('target_type', $drive->target_type) === 'financial' ? 'selected' : '' }}>Financial (PHP)</option>
                                    <option value="quantity" {{ old('target_type', $drive->target_type) === 'quantity' ? 'selected' : '' }}>Quantity (Items)</option>
                                </select>
                                @error('target_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="target_amount" class="form-label">Target Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('target_amount') is-invalid @enderror" 
                                       id="target_amount" name="target_amount" value="{{ old('target_amount', $drive->target_amount) }}" 
                                       min="1" step="0.01" required>
                                @error('target_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 3-Color Progress Section -->
                        <div class="mb-4">
                            <label class="form-label">Current Progress</label>
                            @include('partials.progress-bar-3color', [
                                'distributed' => $drive->distributed_percentage,
                                'pledged' => $drive->pledged_percentage,
                            ])
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Pledged: {{ $drive->pledged_amount ?? 0 }}</small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Distributed: {{ $drive->distributed_amount ?? 0 }}</small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="recalculateProgress()">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Recalculate
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date', $drive->start_date?->format('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date', $drive->end_date?->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Current Drive Items --}}
                        @if($drive->driveItems->count() > 0)
                            <div class="mb-4">
                                <label class="form-label">Current Items ({{ $drive->driveItems->count() }} items)</label>
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-end">Needed</th>
                                                <th class="text-end">Pledged</th>
                                                <th class="text-end">Distributed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($drive->driveItems as $item)
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td class="text-end">{{ number_format($item->quantity_needed, 2) }} {{ $item->unit }}</td>
                                                    <td class="text-end">{{ number_format($item->quantity_pledged, 2) }}</td>
                                                    <td class="text-end">{{ number_format($item->quantity_distributed, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="alert alert-warning mt-2">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Changing pack types or families affected will regenerate items. Existing pledges will remain linked.
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status">
                                <option value="active" {{ old('status', $drive->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $drive->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="closed" {{ old('status', $drive->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Location -->
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <div id="map" style="height: 300px; border-radius: 0.5rem;" class="mb-2"></div>
                            <small class="text-muted">Click on the map to set the drive location</small>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $drive->latitude) }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $drive->longitude) }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address', $drive->address) }}"
                                   placeholder="e.g., Quezon City, Metro Manila">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Drive</button>
                            <a href="{{ route('admin.drives.show', $drive) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Cover photo preview
    document.getElementById('cover_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreview').innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">
                `;
            };
            reader.readAsDataURL(file);
        }
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
            alert('Failed to recalculate progress.');
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
    @if($drive->latitude && $drive->longitude)
        marker = L.marker([{{ $drive->latitude }}, {{ $drive->longitude }}]).addTo(map);
    @endif
    
    // Click handler
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
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
@endsection
