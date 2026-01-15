@extends('layouts.app')

@section('title', 'Pledge ' . $pledge->reference_number)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pledge Details</h5>
                    @if($pledge->isPending())
                        <span class="badge bg-warning">Pending Verification</span>
                    @elseif($pledge->isVerified())
                        <span class="badge bg-success">Verified</span>
                    @elseif($pledge->isDistributed())
                        <span class="badge bg-purple" style="background-color: var(--relief-purple);">Distributed</span>
                    @else
                        <span class="badge bg-danger">Expired</span>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Reference Number Card -->
                    <div class="bg-light rounded p-4 text-center mb-4">
                        <p class="text-muted mb-1">Reference Number</p>
                        <h2 class="mb-0">{{ $pledge->reference_number }}</h2>
                        <small class="text-muted">Show this at the donation point</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Drive</label>
                            <p class="fw-medium">{{ $pledge->drive->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Submitted On</label>
                            <p>{{ $pledge->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($pledge->drive->address)
                        <div class="mb-3">
                            <label class="text-muted small">Drop-off Location</label>
                            <p><i class="bi bi-geo-alt me-2"></i>{{ $pledge->drive->address }}</p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="text-muted small">Items Pledged</label>
                        <ul class="list-unstyled mb-0">
                            @foreach($pledge->items ?? [] as $item)
                                <li><i class="bi bi-check2 text-success me-2"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <p class="mt-2"><strong>Quantity:</strong> {{ $pledge->quantity }}</p>
                    </div>
                    
                    @if($pledge->details)
                        <div class="mb-3">
                            <label class="text-muted small">Details</label>
                            <p>{{ $pledge->details }}</p>
                        </div>
                    @endif
                    
                    @if($pledge->verified_at)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Verified on {{ $pledge->verified_at->format('M d, Y h:i A') }}
                        </div>
                    @endif
                    
                    @if($pledge->isDistributed())
                        <div class="alert alert-info" style="background-color: #f3e8ff; border-color: #c4b5fd;">
                            <i class="bi bi-box-seam me-2"></i>
                            Your donation has been distributed on {{ $pledge->distributed_at->format('M d, Y') }}!
                        </div>
                    @endif
                    
                    <!-- Impact Feedback -->
                    @if($pledge->families_helped || $pledge->relief_packages || $pledge->admin_feedback)
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-body">
                                <h6><i class="bi bi-heart-fill text-danger me-2"></i>Your Impact</h6>
                                
                                @if($pledge->families_helped)
                                    <p class="mb-1"><strong>{{ $pledge->families_helped }}</strong> families helped</p>
                                @endif
                                @if($pledge->relief_packages)
                                    <p class="mb-1"><strong>{{ $pledge->relief_packages }}</strong> relief packages distributed</p>
                                @endif
                                @if($pledge->items_distributed)
                                    <p class="mb-1"><strong>{{ $pledge->items_distributed }}</strong> items distributed</p>
                                @endif
                                @if($pledge->admin_feedback)
                                    <hr>
                                    <p class="mb-0 fst-italic">"{{ $pledge->admin_feedback }}"</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route(auth()->user()->role . '.pledges.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to My Pledges
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
