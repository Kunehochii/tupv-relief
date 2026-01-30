@extends('layouts.app')

@section('title', 'NGO Dashboard')

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
    
    <!-- Verification Banner -->
    @if(auth()->user()->isPending())
        <div class="verification-banner rounded p-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-hourglass-split fs-4 me-3 text-warning"></i>
                <div>
                    <h6 class="mb-0">Account Pending Verification</h6>
                    <small class="text-muted">Your certificate is being reviewed. Some features are limited until verification.</small>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->isRejected())
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-x-circle fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0">Verification Rejected</h6>
                    <p class="mb-0"><strong>Reason:</strong> {{ auth()->user()->rejection_reason }}</p>
                </div>
            </div>
        </div>
    @endif
    
    <h4 class="mb-4">Welcome, {{ auth()->user()->organization_name }}!</h4>
    
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
                        <span class="text-muted">Our Pledges</span>
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
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $stats['drives_participated'] }}</h3>
                        <span class="text-muted">Drives Participated</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="{{ route('ngo.pledges.create') }}" class="card text-decoration-none h-100 {{ auth()->user()->isPending() ? 'opacity-50' : '' }}">
                <div class="card-body text-center py-4">
                    <i class="bi bi-plus-circle fs-1 text-primary mb-2"></i>
                    <h6>Pledge New Donation</h6>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('ngo.donation-link.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-link-45deg fs-1 text-success mb-2"></i>
                    <h6>Manage Donation Link</h6>
                    <small class="text-muted">{{ $linkClicks }} clicks</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('ngo.map') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-geo-alt fs-1 text-info mb-2"></i>
                    <h6>View Drives Map</h6>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Active Drives -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Active Donation Drives</h5>
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
                                
                                {{-- 3-Color Progress Bar --}}
                                <div class="mb-3">
                                    @include('partials.progress-bar-3color', ['drive' => $drive, 'showLegend' => true])
                                </div>
                                
                                {{-- Items Needed with Exact Quantities (NGO can see this) --}}
                                @if($drive->driveItems->count() > 0)
                                    <div class="mb-3">
                                        <small class="fw-bold text-muted d-block mb-1">Items Needed:</small>
                                        <div class="small" style="max-height: 120px; overflow-y: auto;">
                                            @foreach($drive->driveItems->take(5) as $item)
                                                <div class="d-flex justify-content-between border-bottom py-1">
                                                    <span>{{ $item->item_name }}</span>
                                                    <span class="text-muted">
                                                        {{ number_format($item->quantity_needed) }} {{ $item->unit }}
                                                        @if($item->quantity_pledged > 0)
                                                            <span class="text-success">({{ number_format($item->quantity_pledged) }} pledged)</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                            @if($drive->driveItems->count() > 5)
                                                <small class="text-muted">+{{ $drive->driveItems->count() - 5 }} more items</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if($drive->address)
                                    <p class="small mb-2">
                                        <i class="bi bi-geo-alt text-muted me-1"></i>{{ Str::limit($drive->address, 40) }}
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex gap-2">
                                    @if(auth()->user()->isVerified())
                                        {{-- Support Button --}}
                                        <form action="{{ route('ngo.drives.support', $drive) }}" method="POST" class="d-inline">
                                            @csrf
                                            @if(in_array($drive->id, $supportedDriveIds ?? []))
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-heart-fill me-1"></i>Supporting
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-heart me-1"></i>Support
                                                </button>
                                            @endif
                                        </form>
                                        <a href="{{ route('ngo.pledges.create', ['drive_id' => $drive->id]) }}" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="bi bi-gift me-1"></i>Pledge
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            Account Pending
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mb-0">No active drives at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
