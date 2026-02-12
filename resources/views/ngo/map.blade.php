@extends('layouts.app')

@section('title', 'Map - NGO')

@section('content')
    <div class="container-fluid py-3 py-md-4 px-3 px-md-4">
        <h4 class="mb-3 mb-md-4">Active Donation Drives Map</h4>

        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0">
                <div id="map" style="height: 350px;"></div>
            </div>
        </div>

        <div class="mt-3 mt-md-4">
            <h5 class="mb-3">Active Drives</h5>
            <div class="row g-2 g-md-3">
                @foreach ($drives as $drive)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px;">
                            <div class="card-body p-3">
                                <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">{{ Str::limit($drive->name, 35) }}</h6>
                                <p class="small text-muted mb-1"><i
                                        class="bi bi-geo-alt me-1"></i>{{ Str::limit($drive->address, 50) }}</p>
                                <div class="progress mb-2" style="height: 5px;">
                                    <div class="progress-bar"
                                        style="width: {{ $drive->progress_percentage }}%; background: #e51d00;"></div>
                                </div>
                                <a href="{{ route('drive.donate', $drive) }}" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-eye me-1"></i>View Drive
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@section('styles')
    <style>
        @media (min-width: 768px) {
            #map {
                height: 500px !important;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        const map = L.map('map').setView([14.5995, 120.9842], 6); // Philippines center

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Drive markers
        const drives = @json($drives);
        const markers = [];

        drives.forEach(drive => {
            if (drive.latitude && drive.longitude) {
                const progress = drive.progress_percentage || 0;
                const popupContent = `
                <div style="min-width: 200px;">
                    <h6 class="mb-2">${drive.name}</h6>
                    <p class="small mb-2">${drive.description.substring(0, 100)}...</p>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar" style="width: ${progress}%"></div>
                    </div>
                    <p class="small text-muted mb-2">Progress: ${progress}%</p>
                    <a href="/drive/${drive.id}/donate" class="btn btn-sm btn-primary w-100"><i class="bi bi-eye me-1"></i>View Drive</a>
                </div>
            `;

                const marker = L.marker([drive.latitude, drive.longitude])
                    .bindPopup(popupContent)
                    .addTo(map);
                markers.push(marker);
            }
        });

        // Fit map to show all markers
        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    </script>
@endsection
@endsection
