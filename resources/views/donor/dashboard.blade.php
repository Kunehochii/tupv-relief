@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <h4 class="mb-4">Welcome, {{ auth()->user()->name }}!</h4>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $stats['total_pledges'] }}</h3>
                        <span class="text-muted">My Pledges</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $stats['verified_count'] }}</h3>
                        <span class="text-muted">Verified</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $stats['pending_count'] }}</h3>
                        <span class="text-muted">Pending</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $stats['families_helped'] ?? 0 }}</h3>
                        <span class="text-muted">Families Helped</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Drives -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Active Donation Drives</h5>
            <a href="{{ route('donor.map') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-geo-alt me-1"></i>View on Map
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse($activeDrives as $drive)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border">
                            @if($drive->cover_photo)
                                <img src="{{ $drive->cover_photo_url }}" class="card-img-top" 
                                     alt="{{ $drive->name }}" style="height: 160px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                     style="height: 160px;">
                                    <i class="bi bi-image text-white fs-1"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $drive->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($drive->description, 100) }}</p>
                                
                                {{-- 3-Color Progress Bar (donors only see progress, not exact quantities) --}}
                                <div class="mb-3">
                                    @include('partials.progress-bar-3color', ['drive' => $drive, 'showLegend' => true])
                                </div>
                                
                                @if($drive->address)
                                    <p class="small mb-2">
                                        <i class="bi bi-geo-alt text-muted me-1"></i>{{ Str::limit($drive->address, 40) }}
                                    </p>
                                @endif
                                
                                <p class="small mb-3">
                                    <i class="bi bi-calendar text-muted me-1"></i>Ends {{ $drive->end_date->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('donor.pledges.create', ['drive_id' => $drive->id]) }}" class="btn btn-primary w-100">
                                    <i class="bi bi-heart me-2"></i>Pledge to this Drive
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mb-0">No active drives at the moment. Check back soon!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $activeDrives->links() }}
    </div>
</div>
@endsection
