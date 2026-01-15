@extends('layouts.app')

@section('title', 'Pledge Details')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pledges.pending') }}">Pledges</a></li>
            <li class="breadcrumb-item active">{{ $pledge->reference_number }}</li>
        </ol>
    </nav>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pledge Details</h5>
                    <span class="badge bg-{{ $pledge->status === 'pending' ? 'warning' : ($pledge->status === 'verified' ? 'success' : ($pledge->status === 'distributed' ? 'purple' : 'danger')) }}">
                        {{ ucfirst($pledge->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Reference Number</label>
                            <p class="fw-bold fs-5">{{ $pledge->reference_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Submitted</label>
                            <p>{{ $pledge->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Donor</label>
                            <p>
                                <strong>{{ $pledge->user->display_name }}</strong><br>
                                <small class="text-muted">{{ $pledge->user->email }}</small><br>
                                <span class="badge bg-secondary">{{ ucfirst($pledge->user->role) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Contact Number</label>
                            <p>{{ $pledge->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Drive</label>
                        <p>
                            <a href="{{ route('admin.drives.show', $pledge->drive) }}">{{ $pledge->drive->name }}</a>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Items Pledged</label>
                        <ul class="list-unstyled">
                            @if($pledge->items)
                                @foreach($pledge->items as $item)
                                    <li><i class="bi bi-check2 text-success me-2"></i>{{ $item }}</li>
                                @endforeach
                            @endif
                        </ul>
                        <p><strong>Quantity:</strong> {{ $pledge->quantity }}</p>
                    </div>
                    
                    @if($pledge->details)
                        <div class="mb-3">
                            <label class="text-muted small">Details</label>
                            <p>{{ $pledge->details }}</p>
                        </div>
                    @endif
                    
                    @if($pledge->notes)
                        <div class="mb-3">
                            <label class="text-muted small">Additional Notes</label>
                            <p>{{ $pledge->notes }}</p>
                        </div>
                    @endif
                    
                    @if($pledge->verified_at)
                        <div class="mb-3">
                            <label class="text-muted small">Verified</label>
                            <p>
                                {{ $pledge->verified_at->format('M d, Y h:i A') }}
                                @if($pledge->verifier)
                                    by {{ $pledge->verifier->name }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    @if($pledge->isPending())
                        <form method="POST" action="{{ route('admin.pledges.verify', $pledge) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Verify Pledge
                            </button>
                        </form>
                    @elseif($pledge->isVerified())
                        <form method="POST" action="{{ route('admin.pledges.distribute', $pledge) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-seam me-2"></i>Mark as Distributed
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.pledges.pending') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>
        
        <!-- Impact Feedback -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Impact Feedback</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pledges.feedback', $pledge) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="families_helped" class="form-label">Families Helped</label>
                            <input type="number" class="form-control" id="families_helped" name="families_helped" 
                                value="{{ $pledge->families_helped }}" min="0">
                        </div>
                        
                        <div class="mb-3">
                            <label for="relief_packages" class="form-label">Relief Packages</label>
                            <input type="number" class="form-control" id="relief_packages" name="relief_packages" 
                                value="{{ $pledge->relief_packages }}" min="0">
                        </div>
                        
                        <div class="mb-3">
                            <label for="items_distributed" class="form-label">Items Distributed</label>
                            <input type="number" class="form-control" id="items_distributed" name="items_distributed" 
                                value="{{ $pledge->items_distributed }}" min="0">
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_feedback" class="form-label">Feedback Message</label>
                            <textarea class="form-control" id="admin_feedback" name="admin_feedback" rows="3" 
                                placeholder="Add a personal message to the donor...">{{ $pledge->admin_feedback }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Save Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
