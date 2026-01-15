@extends('layouts.app')

@section('title', $drive->name . ' - Drive Preview')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-success mb-2">Active Drive</span>
                            <h2>{{ $drive->name }}</h2>
                        </div>
                    </div>
                    
                    <p class="lead">{{ $drive->description }}</p>
                    
                    <!-- Progress -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Progress</span>
                            <span>{{ $drive->progress_percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                        </div>
                        <small class="text-muted">Target: {{ number_format($drive->target_amount) }} {{ $drive->target_type === 'financial' ? 'PHP' : 'items' }}</small>
                    </div>
                    
                    <!-- Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-calendar text-muted me-2"></i>
                                <strong>Ends:</strong> {{ $drive->end_date->format('M d, Y') }}
                            </p>
                        </div>
                        @if($drive->address)
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="bi bi-geo-alt text-muted me-2"></i>
                                    <strong>Location:</strong> {{ $drive->address }}
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Map -->
                    @if($drive->latitude && $drive->longitude)
                        <div class="mb-4">
                            <div id="map" style="height: 250px; border-radius: 0.5rem;"></div>
                        </div>
                    @endif
                    
                    <!-- Items Needed -->
                    @if($drive->items_needed && count($drive->items_needed) > 0)
                        <div class="mb-4">
                            <h5>Items Needed</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($drive->items_needed as $item)
                                    <span class="badge bg-light text-dark border">{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <hr>
                    
                    <!-- CTA -->
                    <div class="text-center py-3">
                        <h5 class="mb-3">Ready to help?</h5>
                        @auth
                            <a href="{{ route(auth()->user()->role . '.pledges.create', ['drive_id' => $drive->id]) }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-heart-fill me-2"></i>Pledge to this Drive
                            </a>
                        @else
                            <p class="text-muted mb-3">Login or register to make a pledge</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($drive->latitude && $drive->longitude)
@section('scripts')
<script>
    const map = L.map('map').setView([{{ $drive->latitude }}, {{ $drive->longitude }}], 14);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ $drive->latitude }}, {{ $drive->longitude }}]).addTo(map)
        .bindPopup('<strong>{{ $drive->name }}</strong><br>{{ $drive->address }}')
        .openPopup();
</script>
@endsection
@endif
@endsection
