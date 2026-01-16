@extends('layouts.app')

@section('title', 'Drives Map - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Drive Locations Map</h2>
        <div>
            <a href="{{ route('admin.drives.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-list me-1"></i>List View
            </a>
            <a href="{{ route('admin.drives.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Create Drive
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <strong>Active Drives</strong>
                    <span class="badge bg-primary float-end">{{ $drives->count() }}</span>
                </div>
                <div class="card-body p-0" style="max-height: 550px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @forelse($drives as $drive)
                            <li class="list-group-item drive-item" data-lat="{{ $drive->latitude }}" data-lng="{{ $drive->longitude }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong class="d-block">{{ Str::limit($drive->name, 25) }}</strong>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($drive->address ?? 'No address', 30) }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $drive->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($drive->status) }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $drive->progress_percentage }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $drive->progress_percentage }}% of target</small>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('admin.drives.show', $drive) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('admin.drives.edit', $drive) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                No active drives with locations
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map centered on Philippines
        const map = L.map('map').setView([12.8797, 121.7740], 6);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Store markers for later reference
        const markers = {};

        // Add markers for each drive
        @foreach($drives as $drive)
            @if($drive->latitude && $drive->longitude)
                const marker{{ $drive->id }} = L.marker([{{ $drive->latitude }}, {{ $drive->longitude }}])
                    .addTo(map)
                    .bindPopup(`
                        <div style="min-width: 200px;">
                            <h6 class="mb-2">{{ $drive->name }}</h6>
                            <p class="mb-1 text-muted small">{{ Str::limit($drive->description, 100) }}</p>
                            <hr class="my-2">
                            <p class="mb-1"><strong>Target:</strong> 
                                @if($drive->target_type === 'financial')
                                    ₱{{ number_format($drive->target_amount, 0) }}
                                @else
                                    {{ number_format($drive->target_amount) }} items
                                @endif
                            </p>
                            <p class="mb-1"><strong>Collected:</strong> 
                                @if($drive->target_type === 'financial')
                                    ₱{{ number_format($drive->collected_amount, 0) }}
                                @else
                                    {{ number_format($drive->collected_amount) }} items
                                @endif
                            </p>
                            <p class="mb-1"><strong>Progress:</strong> {{ $drive->progress_percentage }}%</p>
                            <p class="mb-2"><strong>Ends:</strong> {{ $drive->end_date->format('M d, Y') }}</p>
                            <div class="d-grid gap-1">
                                <a href="{{ route('admin.drives.show', $drive) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                                <a href="{{ route('admin.drives.edit', $drive) }}" class="btn btn-sm btn-outline-secondary">
                                    Edit Drive
                                </a>
                            </div>
                        </div>
                    `);
                markers[{{ $drive->id }}] = marker{{ $drive->id }};
            @endif
        @endforeach

        // Fit bounds to show all markers if there are any
        @if($drives->count() > 0)
            const allCoords = [
                @foreach($drives as $drive)
                    @if($drive->latitude && $drive->longitude)
                        [{{ $drive->latitude }}, {{ $drive->longitude }}],
                    @endif
                @endforeach
            ];
            if (allCoords.length > 0) {
                const bounds = L.latLngBounds(allCoords);
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        @endif

        // Click on sidebar item to zoom to marker
        document.querySelectorAll('.drive-item').forEach(item => {
            item.addEventListener('click', function() {
                const lat = this.dataset.lat;
                const lng = this.dataset.lng;
                if (lat && lng) {
                    map.setView([lat, lng], 14);
                    // Find and open the popup for this location
                    Object.values(markers).forEach(marker => {
                        const markerLat = marker.getLatLng().lat;
                        const markerLng = marker.getLatLng().lng;
                        if (Math.abs(markerLat - lat) < 0.0001 && Math.abs(markerLng - lng) < 0.0001) {
                            marker.openPopup();
                        }
                    });
                }
            });
            item.style.cursor = 'pointer';
        });
    });
</script>
@endsection
