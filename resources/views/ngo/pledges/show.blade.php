@extends('layouts.app')

@section('title', 'Pledge ' . $pledge->reference_number)

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0">Pledge Details</h5>
                            <small class="text-muted">{{ auth()->user()->organization_name }}</small>
                        </div>
                        @if ($pledge->isPending())
                            <span class="badge bg-warning">Pending Verification</span>
                        @elseif($pledge->isVerified())
                            <span class="badge bg-success">Verified</span>
                        @elseif($pledge->isDistributed())
                            <span class="badge bg-purple" style="background-color: var(--relief-purple);">Distributed</span>
                        @else
                            <span class="badge bg-danger">Expired</span>
                        @endif
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <!-- Reference Number Card -->
                        <div class="bg-light rounded p-3 p-md-4 text-center mb-4">
                            <p class="text-muted mb-1" style="font-size: 0.85rem;">Reference Number</p>
                            <h2 class="mb-0 fs-3 fs-md-2">{{ $pledge->reference_number }}</h2>
                            <small class="text-muted">Show this at the donation point</small>
                        </div>

                        <div class="row mb-3 g-2">
                            <div class="col-sm-6">
                                <label class="text-muted small">Drive</label>
                                <p class="fw-medium mb-1">{{ $pledge->drive->name }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small">Submitted On</label>
                                <p class="mb-1">{{ $pledge->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if ($pledge->drive->address)
                            <div class="mb-3">
                                <label class="text-muted small">Drop-off Location</label>
                                <p><i class="bi bi-geo-alt me-2"></i>{{ $pledge->drive->address }}</p>
                            </div>
                        @endif

                        @if ($pledge->pledgeItems->count())
                            <div class="mb-3">
                                <label class="text-muted small">Items Pledged</label>
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-center">Qty Pledged</th>
                                                <th class="text-center">Qty Distributed</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pledge->pledgeItems as $item)
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td class="text-center">{{ number_format($item->quantity) }}
                                                        {{ $item->unit }}</td>
                                                    <td class="text-center">{{ number_format($item->quantity_distributed) }}
                                                        {{ $item->unit }}</td>
                                                    <td class="text-center">
                                                        @if ($item->isFullyDistributed())
                                                            <span class="badge bg-success"><i
                                                                    class="bi bi-check-circle me-1"></i>Distributed</span>
                                                        @elseif($item->quantity_distributed > 0)
                                                            <span class="badge bg-info">Partial</span>
                                                        @else
                                                            <span class="badge bg-secondary">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if ($pledge->details)
                            <div class="mb-3">
                                <label class="text-muted small">Details</label>
                                <p>{{ $pledge->details }}</p>
                            </div>
                        @endif

                        @if ($pledge->contact_number)
                            <div class="mb-3">
                                <label class="text-muted small">Contact Number</label>
                                <p><i class="bi bi-telephone me-2"></i>{{ $pledge->contact_number }}</p>
                            </div>
                        @endif

                        @if ($pledge->notes)
                            <div class="mb-3">
                                <label class="text-muted small">Notes</label>
                                <p>{{ $pledge->notes }}</p>
                            </div>
                        @endif

                        @if ($pledge->verified_at)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Verified on {{ $pledge->verified_at->format('M d, Y h:i A') }}
                                @if ($pledge->verifier)
                                    by {{ $pledge->verifier->name }}
                                @endif
                            </div>
                        @endif

                        @if ($pledge->isDistributed())
                            <div class="card border-0 mt-4 mb-3"
                                style="background: linear-gradient(135deg, #f3e8ff 0%, #e0f2fe 100%); border-left: 4px solid var(--relief-purple, #7c3aed) !important;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 48px; height: 48px; background-color: var(--relief-purple, #7c3aed);">
                                            <i class="bi bi-box-seam-fill text-white fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: var(--relief-purple, #7c3aed);">Donation
                                                Distributed!</h5>
                                            <small class="text-muted">Completed on
                                                {{ $pledge->distributed_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <i class="bi bi-heart-fill text-danger me-1"></i>
                                        Your organization's donation has been successfully distributed to families in need.
                                        Thank you for making a difference!
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Impact Feedback -->
                        @if ($pledge->total_families_helped > 0 || $pledge->total_distributed > 0 || $pledge->admin_feedback)
                            <div class="card border-0 mt-3" style="background-color: #f0fdf4;">
                                <div class="card-body">
                                    <h6 class="fw-bold"><i class="bi bi-heart-fill text-danger me-2"></i>Your Organization's
                                        Impact</h6>

                                    <div class="row g-2 g-md-3 mt-1">
                                        @if ($pledge->total_families_helped > 0)
                                            <div class="col-4">
                                                <div class="text-center p-2 p-md-3 bg-white rounded shadow-sm">
                                                    <div class="fs-4 fs-md-3 fw-bold text-success">
                                                        {{ number_format($pledge->total_families_helped) }}</div>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Families
                                                        Helped</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pledge->total_distributed > 0)
                                            <div class="col-4">
                                                <div class="text-center p-2 p-md-3 bg-white rounded shadow-sm">
                                                    <div class="fs-4 fs-md-3 fw-bold text-primary">
                                                        {{ number_format($pledge->total_distributed) }}</div>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Items
                                                        Distributed</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pledge->relief_packages)
                                            <div class="col-4">
                                                <div class="text-center p-2 p-md-3 bg-white rounded shadow-sm">
                                                    <div class="fs-4 fs-md-3 fw-bold"
                                                        style="color: var(--relief-purple, #7c3aed);">
                                                        {{ number_format($pledge->relief_packages) }}</div>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Relief
                                                        Packages</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($pledge->admin_feedback)
                                        <div class="mt-3 p-3 bg-white rounded">
                                            <small class="text-muted d-block mb-1"><i
                                                    class="bi bi-chat-quote me-1"></i>Admin Feedback</small>
                                            <p class="mb-0 fst-italic">"{{ $pledge->admin_feedback }}"</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer p-3">
                        <a href="{{ route('ngo.pledges.index') }}" class="btn btn-outline-secondary btn-sm btn-md-normal">
                            <i class="bi bi-arrow-left me-1 me-md-2"></i><span class="d-none d-sm-inline">Back to
                                Organization Pledges</span><span class="d-sm-none">Back</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
