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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pledge Details</h5>
                        <div>
                            <span class="badge bg-{{ $pledge->pledge_type === 'financial' ? 'info' : 'primary' }} me-2">
                                {{ $pledge->pledge_type === 'financial' ? 'Financial' : 'In-Kind' }}
                            </span>
                            <span
                                class="badge bg-{{ $pledge->status === 'pending' ? 'warning' : ($pledge->status === 'verified' ? 'success' : ($pledge->status === 'distributed' ? 'purple' : 'danger')) }}">
                                {{ ucfirst($pledge->status) }}
                            </span>
                        </div>
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

                        {{-- Financial Pledge --}}
                        @if ($pledge->pledge_type === 'financial')
                            <div class="mb-3">
                                <label class="text-muted small">Financial Amount</label>
                                <p class="fs-4 fw-bold text-success">₱{{ number_format($pledge->financial_amount, 2) }}</p>
                            </div>
                        @endif

                        {{-- In-Kind Items --}}
                        @if ($pledge->pledgeItems->count() > 0)
                            <div class="mb-3">
                                <label class="text-muted small">Items Pledged</label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-end">Pledged</th>
                                                <th class="text-end">Distributed</th>
                                                <th class="text-end">Families</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pledge->pledgeItems as $item)
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td class="text-end">{{ number_format($item->quantity, 2) }}
                                                        {{ $item->unit }}</td>
                                                    <td class="text-end">
                                                        @if ($item->quantity_distributed > 0)
                                                            <span
                                                                class="text-success">{{ number_format($item->quantity_distributed, 2) }}
                                                                {{ $item->unit }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($item->families_helped > 0)
                                                            <span class="badge bg-info">{{ $item->families_helped }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total</th>
                                                <th class="text-end">{{ number_format($pledge->total_quantity, 2) }}</th>
                                                <th class="text-end">{{ number_format($pledge->total_distributed, 2) }}
                                                </th>
                                                <th class="text-end">{{ $pledge->total_families_helped }}</th>
                                            </tr>
                                        </tfoot>
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

                        @if ($pledge->notes)
                            <div class="mb-3">
                                <label class="text-muted small">Additional Notes</label>
                                <p>{{ $pledge->notes }}</p>
                            </div>
                        @endif

                        @if ($pledge->verified_at)
                            <div class="mb-3">
                                <label class="text-muted small">Verified</label>
                                <p>
                                    {{ $pledge->verified_at->format('M d, Y h:i A') }}
                                    @if ($pledge->verifier)
                                        by {{ $pledge->verifier->name }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if ($pledge->isPending())
                            <form method="POST" action="{{ route('admin.pledges.verify', $pledge) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i>Receive Pledge
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectPledgeModal">
                                <i class="bi bi-x-circle me-2"></i>Reject Pledge
                            </button>
                        @endif
                        <a href="{{ route('admin.pledges.pending') }}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </div>

                {{-- Distribution Form (for verified pledges with items) --}}
                @if ($pledge->isVerified() && $pledge->pledgeItems->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Record Distribution</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.pledges.distribute', $pledge) }}">
                                @csrf

                                <p class="text-muted small mb-3">Enter the quantity distributed for each item. Families
                                    helped will be auto-calculated using the Mother Formula.</p>

                                @foreach ($pledge->pledgeItems as $index => $item)
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-5">
                                            <strong>{{ $item->item_name }}</strong>
                                            <div class="small text-muted">
                                                Available: {{ number_format($item->remaining_to_distribute, 2) }}
                                                {{ $item->unit }}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="number" class="form-control"
                                                    name="items[{{ $index }}][quantity_distributed]"
                                                    value="{{ $item->remaining_to_distribute }}"
                                                    max="{{ $item->remaining_to_distribute }}" min="0"
                                                    step="0.01">
                                                <span class="input-group-text">{{ $item->unit }}</span>
                                            </div>
                                            <input type="hidden" name="items[{{ $index }}][id]"
                                                value="{{ $item->id }}">
                                        </div>
                                        <div class="col-md-3">
                                            @if ($item->isFullyDistributed())
                                                <span class="badge bg-success">Fully Distributed</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label for="admin_feedback" class="form-label fw-semibold">Feedback Message <span
                                            class="text-muted fw-normal">(optional)</span></label>
                                    <textarea class="form-control" id="admin_feedback" name="admin_feedback" rows="3"
                                        placeholder="Add a personal message to the donor about this distribution...">{{ $pledge->admin_feedback }}</textarea>
                                    <div class="form-text">This message will be sent to the donor as part of the
                                        distribution notification.</div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2-all me-2"></i>Record Distribution
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Feedback form for already-distributed pledges --}}
                @if ($pledge->status === 'distributed')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Distribution Feedback</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.pledges.feedback', $pledge) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="admin_feedback" class="form-label fw-semibold">Feedback Message</label>
                                    <textarea class="form-control" id="admin_feedback" name="admin_feedback" rows="3"
                                        placeholder="Add or update a personal message to the donor...">{{ $pledge->admin_feedback }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Save Feedback
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reject Pledge Modal --}}
    @if ($pledge->isPending())
        <div class="modal fade" id="rejectPledgeModal" tabindex="-1" aria-labelledby="rejectPledgeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.pledges.reject', $pledge) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectPledgeModalLabel">Reject Pledge</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject pledge <strong>{{ $pledge->reference_number }}</strong>?</p>
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Reason for Rejection <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                    placeholder="Provide a reason for rejecting this pledge..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-2"></i>Reject Pledge
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
