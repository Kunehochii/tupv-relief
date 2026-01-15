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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Donation Drive</div>
                <div class="card-body">
                    <form action="{{ route('admin.drives.update', $drive) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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

                        <!-- Progress Section -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="collected_amount" class="form-label">Collected Amount (Progress)</label>
                                <input type="number" class="form-control @error('collected_amount') is-invalid @enderror" 
                                       id="collected_amount" name="collected_amount" 
                                       value="{{ old('collected_amount', $drive->collected_amount ?? 0) }}" 
                                       min="0" step="0.01">
                                @error('collected_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Current progress: {{ $drive->progress_percentage ?? 0 }}%</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Progress Bar</label>
                                <div class="progress" style="height: 38px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $drive->progress_percentage ?? 0 }}%" 
                                         aria-valuenow="{{ $drive->progress_percentage ?? 0 }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $drive->progress_percentage ?? 0 }}%
                                    </div>
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
                        
                        <div class="mb-3">
                            <label for="items_needed" class="form-label">Items Needed (comma-separated)</label>
                            <input type="text" class="form-control @error('items_needed') is-invalid @enderror" 
                                   id="items_needed" name="items_needed" 
                                   value="{{ old('items_needed', $drive->items_needed ? implode(', ', $drive->items_needed) : '') }}"
                                   placeholder="e.g., Rice, Canned Goods, Water, Blankets">
                            @error('items_needed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">List of items that donors can contribute</small>
                        </div>
                        
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

    // Real-time progress bar update
    function updateProgressBar() {
        const targetAmount = parseFloat(document.getElementById('target_amount').value) || 0;
        const collectedAmount = parseFloat(document.getElementById('collected_amount').value) || 0;
        
        let percentage = 0;
        if (targetAmount > 0) {
            percentage = Math.min(100, Math.round((collectedAmount / targetAmount) * 100 * 100) / 100);
        }
        
        const progressBar = document.querySelector('.progress-bar');
        const progressText = progressBar.parentElement.nextElementSibling;
        
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressBar.textContent = percentage + '%';
        
        if (progressText) {
            progressText.innerHTML = `<small class="text-muted">Current progress: ${percentage}%</small>`;
        }
    }

    // Attach event listeners
    document.getElementById('target_amount').addEventListener('input', updateProgressBar);
    document.getElementById('collected_amount').addEventListener('input', updateProgressBar);
</script>
@endsection
@endsection
