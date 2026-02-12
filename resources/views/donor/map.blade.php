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

        <div class="card mb-3 mb-md-4">
            <div class="card-body p-0">
                <div id="map" style="height: 350px;"></div>
            </div>
        </div>

        <div>
            <h5 class="mb-3">Active Drives</h5>
            <div class="row g-2 g-md-3">
                @foreach ($drives as $drive)
                    <div class="col-6 col-md-4">
                        <div class="card h-100">
                            <div class="card-body p-2 p-md-3">
                                <h6 class="mb-1" style="font-size: 0.85rem;">{{ Str::limit($drive->name, 30) }}</h6>
                                <p class="small text-muted mb-2 d-none d-sm-block">{{ Str::limit($drive->address, 50) }}</p>
                                <a href="{{ route(auth()->user()->role . '.pledges.create', ['drive_id' => $drive->id]) }}"
                                    class="btn btn-sm btn-primary w-100">
                                    Pledge
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
                <strong>${drive.name}</strong><br>
                ${drive.address || ''}<br>
                <a href="{{ url(auth()->user()->role . '/pledges/create') }}?drive_id=${drive.id}" class="btn btn-sm btn-primary mt-2">Pledge to Drive</a>
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
