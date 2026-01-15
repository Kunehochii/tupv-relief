@extends('layouts.app')

@section('title', 'Map - NGO')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Active Donation Drives Map</h2>
    
    <div class="card">
        <div class="card-body p-0">
            <div id="map" style="height: 600px;"></div>
        </div>
    </div>
</div>

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
                    <a href="/ngo/pledges/create?drive_id=${drive.id}" class="btn btn-sm btn-primary">Pledge Now</a>
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
