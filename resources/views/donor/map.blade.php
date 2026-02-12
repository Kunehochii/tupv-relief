@extends('layouts.app')

@section('title', 'Drive Map')

@section('content')
    <div class="container-fluid py-3 py-md-4 px-3 px-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
            <h4 class="mb-0">Donation Drives Map</h4>
            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i><span class="d-none d-sm-inline">Back to Dashboard</span><span
                    class="d-sm-none">Back</span>
            </a>
        </div>

        <div class="card mb-3 mb-md-4 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0">
                <div id="map" style="height: 350px;"></div>
            </div>
        </div>

        <div>
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
        const map = L.map('map').setView([14.5995, 120.9842], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const drives = @json($drives);
        const markers = [];

        drives.forEach(drive => {
            if (drive.latitude && drive.longitude) {
                const marker = L.marker([drive.latitude, drive.longitude]).addTo(map);
                marker.bindPopup(`
                <div style="min-width: 200px;">
                    <strong>${drive.name}</strong><br>
                    <span class="text-muted">${drive.address || ''}</span><br>
                    <a href="/drive/${drive.id}/donate" class="btn btn-sm btn-primary mt-2 w-100"><i class="bi bi-eye me-1"></i>View Drive</a>
                </div>
            `);
                markers.push(marker);
            }
        });

        // Fit bounds if there are markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    </script>
@endsection
@endsection
