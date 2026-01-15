@extends('layouts.app')

@section('title', 'Drive Map')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Donation Drives Map</h4>
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div id="map" style="height: 500px;"></div>
        </div>
    </div>
    
    <div class="mt-4">
        <h5>Active Drives</h5>
        <div class="row g-3">
            @foreach($drives as $drive)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>{{ $drive->name }}</h6>
                            <p class="small text-muted mb-2">{{ Str::limit($drive->address, 50) }}</p>
                            <a href="{{ route(auth()->user()->role . '.pledges.create', ['drive_id' => $drive->id]) }}" class="btn btn-sm btn-primary">
                                Pledge
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

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
