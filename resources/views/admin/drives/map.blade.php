@extends('layouts.admin')

@section('title', 'Drive Locations Map')

@section('page', 'map')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Drive Locations</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.drives.index') }}" class="btn-header-action">
                <i class="bi bi-list me-1"></i>List View
            </a>
            <a href="{{ route('admin.drives.create') }}" class="btn-header-action btn-header-primary">
                <i class="bi bi-plus-lg me-1"></i>Create Drive
            </a>
        </div>
    </div>

    <div class="map-layout">
        <div class="map-main">
            <div class="content-card">
                <div class="map-container">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="map-sidebar">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">Active Drives</h5>
                    <span class="drives-count">{{ $drives->count() }}</span>
                </div>
                <div class="drives-list">
                    @forelse($drives as $drive)
                        <div class="drive-item" data-lat="{{ $drive->latitude }}" data-lng="{{ $drive->longitude }}"
                            data-id="{{ $drive->id }}">
                            <div class="drive-item-header">
                                <span class="drive-name">{{ Str::limit($drive->name, 22) }}</span>
                                <span
                                    class="status-dot {{ $drive->status === 'active' ? 'status-active' : 'status-inactive' }}"></span>
                            </div>
                            <div class="drive-location">
                                <i class="bi bi-geo-alt"></i>
                                {{ Str::limit($drive->address ?? 'No address', 28) }}
                            </div>
                            <div class="drive-progress">
                                <div class="progress-sm">
                                    <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                                </div>
                                <span class="progress-text">{{ $drive->progress_percentage }}%</span>
                            </div>
                            <div class="drive-actions">
                                <a href="{{ route('admin.drives.show', $drive) }}" class="btn-small">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('admin.drives.edit', $drive) }}" class="btn-small btn-small-outline">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state py-5">
                            <i class="bi bi-geo-alt"></i>
                            <p>No drives with locations</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    .map-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    height: calc(100vh - 180px);
    }

    .map-main {
    min-height: 0;
    }

    .map-main .content-card {
    height: 100%;
    }

    .map-container {
    height: 100%;
    border-radius: 12px;
    overflow: hidden;
    }

    #map {
    height: 100%;
    width: 100%;
    min-height: 500px;
    }

    .map-sidebar .content-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    }

    .drives-count {
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 12px;
    }

    .drives-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    }

    .drive-item {
    background-color: #fafafa;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid transparent;
    }

    .drive-item:hover {
    background-color: #f0f0f0;
    border-color: var(--relief-dark-blue);
    }

    .drive-item.active {
    background-color: rgba(0, 1, 103, 0.08);
    border-color: var(--relief-dark-blue);
    }

    .drive-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    }

    .drive-name {
    font-weight: 600;
    font-size: 14px;
    color: var(--relief-dark-blue);
    }

    .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    }

    .status-dot.status-active {
    background-color: #198754;
    }

    .status-dot.status-inactive {
    background-color: #6c757d;
    }

    .drive-location {
    font-size: 12px;
    color: var(--relief-gray-blue);
    margin-bottom: 10px;
    }

    .drive-location i {
    margin-right: 4px;
    }

    .drive-progress {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    }

    .drive-progress .progress-sm {
    flex: 1;
    }

    .progress-text {
    font-size: 11px;
    color: var(--relief-gray-blue);
    min-width: 30px;
    }

    .drive-actions {
    display: flex;
    gap: 8px;
    }

    .btn-small {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 6px;
    text-decoration: none;
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    transition: all 0.2s;
    }

    .btn-small:hover {
    background-color: #000155;
    color: #ffffff;
    }

    .btn-small-outline {
    background-color: #ffffff;
    color: var(--relief-dark-blue);
    border: 1px solid #d0d0d0;
    }

    .btn-small-outline:hover {
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    border-color: var(--relief-dark-blue);
    }

    .btn-header-action {
    display: inline-flex;
    align-items: center;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    background-color: #ffffff;
    color: var(--relief-dark-blue);
    border: 1px solid #d0d0d0;
    transition: all 0.2s;
    }

    .btn-header-action:hover {
    background-color: #f5f5f5;
    color: var(--relief-dark-blue);
    }

    .btn-header-primary {
    background-color: var(--relief-vivid-orange);
    color: #ffffff;
    border-color: var(--relief-vivid-orange);
    }

    .btn-header-primary:hover {
    background-color: var(--relief-red);
    border-color: var(--relief-red);
    color: #ffffff;
    }

    /* Leaflet popup styling */
    .leaflet-popup-content-wrapper {
    border-radius: 10px;
    }

    .leaflet-popup-content {
    margin: 12px 14px;
    }

    .popup-title {
    font-weight: 600;
    font-size: 14px;
    color: var(--relief-dark-blue);
    margin-bottom: 8px;
    }

    .popup-desc {
    font-size: 12px;
    color: #666;
    margin-bottom: 10px;
    }

    .popup-info {
    font-size: 12px;
    margin-bottom: 4px;
    }

    .popup-info strong {
    color: var(--relief-dark-blue);
    }

    .popup-btn {
    display: block;
    text-align: center;
    padding: 8px 14px;
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    margin-top: 10px;
    }

    .popup-btn:hover {
    background-color: #000155;
    color: #ffffff;
    }

    @media (max-width: 1200px) {
    .map-layout {
    grid-template-columns: 1fr;
    height: auto;
    }

    .map-main .content-card {
    height: 450px;
    }

    .drives-list {
    max-height: 350px;
    }
    }
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

            // Custom marker icon
            const markerIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: #000167; width: 24px; height: 24px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            // Add markers for each drive
            @foreach ($drives as $drive)
                @if ($drive->latitude && $drive->longitude)
                    const marker{{ $drive->id }} = L.marker([{{ $drive->latitude }},
                        {{ $drive->longitude }}], {
                            icon: markerIcon
                        })
                        .addTo(map)
                        .bindPopup(`
                        <div style="min-width: 200px;">
                            <div class="popup-title">{{ $drive->name }}</div>
                            <p class="popup-desc">{{ Str::limit($drive->description, 80) }}</p>
                            <div class="popup-info"><strong>Target:</strong> 
                                @if ($drive->target_type === 'financial')
                                    ₱{{ number_format($drive->target_amount, 0) }}
                                @else
                                    {{ number_format($drive->target_amount) }} items
                                @endif
                            </div>
                            <div class="popup-info"><strong>Progress:</strong> {{ $drive->progress_percentage }}%</div>
                            <div class="popup-info"><strong>Ends:</strong> {{ $drive->end_date->format('M d, Y') }}</div>
                            <a href="{{ route('admin.drives.show', $drive) }}" class="popup-btn">View Details</a>
                        </div>
                    `);
                    markers[{{ $drive->id }}] = marker{{ $drive->id }};
                @endif
            @endforeach

            // Fit bounds to show all markers if there are any
            @if ($drives->count() > 0)
                const allCoords = [
                    @foreach ($drives as $drive)
                        @if ($drive->latitude && $drive->longitude)
                            [{{ $drive->latitude }}, {{ $drive->longitude }}],
                        @endif
                    @endforeach
                ];
                if (allCoords.length > 0) {
                    const bounds = L.latLngBounds(allCoords);
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
                }
            @endif

            // Click on sidebar item to zoom to marker
            document.querySelectorAll('.drive-item').forEach(item => {
                item.addEventListener('click', function() {
                    const lat = this.dataset.lat;
                    const lng = this.dataset.lng;
                    const id = this.dataset.id;

                    // Remove active class from all items
                    document.querySelectorAll('.drive-item').forEach(el => el.classList.remove(
                        'active'));
                    this.classList.add('active');

                    if (lat && lng && markers[id]) {
                        map.setView([lat, lng], 14);
                        markers[id].openPopup();
                    }
                });
            });
        });
    </script>
@endsection
