@extends('layouts.admin')

@section('title', ($user->organization_name ?? $user->name) . ' - NGO Details')

@section('page', 'ngos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ route('admin.ngos.pending') }}" class="text-decoration-none">Account
                        Verifications</a></li>
                <li class="breadcrumb-item active">{{ $user->organization_name ?? $user->name }}</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="content-card mb-4">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">NGO Details</h5>
                    <div>
                        @if ($user->verification_status === 'pending')
                            <span class="badge bg-warning text-dark">Pending Verification</span>
                        @elseif ($user->verification_status === 'verified')
                            <span class="badge bg-success">Verified</span>
                        @elseif ($user->verification_status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>
                </div>
                <div class="content-card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Organization Name</label>
                            <p class="fw-bold fs-5">{{ $user->organization_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Contact Person</label>
                            <p>{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Phone</label>
                            <p>{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Registered</label>
                            <p>{{ $user->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">External Donation Link</label>
                            <p>
                                @if ($user->external_donation_url)
                                    <a href="{{ $user->external_donation_url }}"
                                        target="_blank">{{ $user->external_donation_url }}</a>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($user->certificate_path)
                        <div class="mb-3">
                            <label class="text-muted small">Certificate of Authenticity</label>
                            <p>
                                <a href="{{ route('admin.ngos.certificate', $user) }}"
                                    class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i
                                        class="bi bi-file-earmark-arrow-down me-1"></i>{{ basename($user->certificate_path) }}
                                </a>
                            </p>
                        </div>
                    @endif

                    @if ($user->rejection_reason)
                        <div class="mb-3">
                            <label class="text-muted small">Rejection Reason</label>
                            <div class="alert alert-danger mb-0">{{ $user->rejection_reason }}</div>
                        </div>
                    @endif
                </div>
                <div class="content-card-footer p-3 d-flex gap-2">
                    @if ($user->verification_status === 'pending')
                        <form method="POST" action="{{ route('admin.ngos.approve', $user) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#rejectNgoModal">
                            <i class="bi bi-x-circle me-2"></i>Reject
                        </button>
                    @endif
                    <form method="POST" action="{{ route('admin.ngos.destroy', $user) }}" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this NGO? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash3 me-2"></i>Delete
                        </button>
                    </form>
                    <a href="{{ route('admin.ngos.pending') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            @if ($user->logo_path || $user->logo_url)
                <div class="content-card mb-4">
                    <div class="content-card-header">Organization Logo</div>
                    <div class="content-card-body text-center">
                        <img src="{{ $user->logo_path ? asset('storage/' . $user->logo_path) : $user->logo_url }}"
                            alt="{{ $user->organization_name }}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
            @endif

            <div class="content-card">
                <div class="content-card-header">Quick Stats</div>
                <div class="content-card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Total Pledges</span>
                        <strong>{{ $user->pledges()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Link Clicks</span>
                        <strong>{{ $user->linkClicks()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Supported Drives</span>
                        <strong>{{ $user->supportedDrives()->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject NGO Modal --}}
    @if ($user->verification_status === 'pending')
        <div class="modal fade" id="rejectNgoModal" tabindex="-1" aria-labelledby="rejectNgoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.ngos.reject', $user) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectNgoModalLabel">Reject NGO</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject
                                <strong>{{ $user->organization_name ?? $user->name }}</strong>?</p>
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Reason for Rejection <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                    placeholder="Provide a reason for rejecting this NGO..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-2"></i>Reject NGO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
