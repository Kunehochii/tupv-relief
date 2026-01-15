@extends('layouts.app')

@section('title', 'Create Drive')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Donation Drive</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.drives.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Drive Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="target_type" class="form-label">Target Type *</label>
                                <select class="form-select @error('target_type') is-invalid @enderror" 
                                    id="target_type" name="target_type" required>
                                    <option value="quantity" {{ old('target_type') == 'quantity' ? 'selected' : '' }}>Quantity (Items)</option>
                                    <option value="financial" {{ old('target_type') == 'financial' ? 'selected' : '' }}>Financial (Value)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="target_amount" class="form-label">Target Amount *</label>
                                <input type="number" class="form-control @error('target_amount') is-invalid @enderror" 
                                    id="target_amount" name="target_amount" value="{{ old('target_amount', 0) }}" min="0" step="0.01" required>
                                @error('target_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                    id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                    id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="items_needed" class="form-label">Items Needed (comma-separated)</label>
                            <input type="text" class="form-control @error('items_needed') is-invalid @enderror" 
                                   id="items_needed" name="items_needed" 
                                   value="{{ old('items_needed') }}"
                                   placeholder="e.g., Rice, Canned Goods, Water, Blankets">
                            @error('items_needed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">List of items that donors can contribute</small>
                        </div>
                        
                        <hr>
                        
                        <h6 class="mb-3">Location (Pin on Map)</h6>
                        
                        <div class="mb-3">
                            <div id="map" style="height: 300px; border-radius: 0.5rem;"></div>
                            <small class="text-muted">Click on the map to set the drive location</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" class="form-control" id="latitude" name="latitude" 
                                    value="{{ old('latitude') }}" step="any" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" class="form-control" id="longitude" name="longitude" 
                                    value="{{ old('longitude') }}" step="any" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                    id="address" name="address" value="{{ old('address') }}">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Drive
                            </button>
                            <a href="{{ route('admin.drives.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Initialize map
    const map = L.map('map').setView([14.5995, 120.9842], 10); // Default to Manila
    
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
</script>
@endsection
@endsection
