@extends('layouts.admin')

@section('title', 'Pending Pledges')

@section('page', 'pledges')

@section('content')
    <h1 class="page-title">Pending Pledge Receipts</h1>

    <div class="content-card">
        <div class="content-card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Donor</th>
                        <th>Item/s</th>
                        <th>Quantity</th>
                        <th>Submitted</th>
                        <th>Time Left</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pledges as $pledge)
                        <tr>
                            <td>
                                <a href="{{ route('admin.pledges.show', $pledge) }}">
                                    {{ $pledge->reference_number }}
                                </a>
                            </td>
                            <td>{{ $pledge->user->name }}</td>
                            <td>
                                @if ($pledge->items && count($pledge->items) > 0)
                                    {{ $pledge->items[0] }}
                                @elseif($pledge->item_description)
                                    {{ $pledge->item_description }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($pledge->quantity)
                                    {{ $pledge->quantity }} ({{ $pledge->unit ?? 'units' }})
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $pledge->created_at->format('M d, g:i A') }}</td>
                            <td>
                                @php
                                    $hoursLeft = max(0, 24 - $pledge->created_at->diffInHours(now()));
                                    $minutesLeft = max(0, 24 * 60 - $pledge->created_at->diffInMinutes(now()));
                                @endphp
                                @if ($minutesLeft > 0)
                                    {{ floor($minutesLeft / 60) }}:{{ str_pad($minutesLeft % 60, 2, '0', STR_PAD_LEFT) }}h
                                    left
                                @else
                                    <span class="text-danger">Expired</span>
                                @endif
                            </td>
                            <td>
                                @if ($pledge->status === 'pending')
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('admin.pledges.verify', $pledge) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action btn-action-verify">
                                                <i class="bi bi-check-circle me-1"></i>Receive
                                            </button>
                                        </form>
                                        <button type="button" class="btn-action btn-action-reject" data-bs-toggle="modal"
                                            data-bs-target="#rejectPledgeModal-{{ $pledge->id }}">
                                            <i class="bi bi-x-circle me-1"></i>Reject
                                        </button>
                                    </div>
                                @elseif($pledge->status === 'verified')
                                    <form method="POST" action="{{ route('admin.pledges.distribute', $pledge) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-action-verified">
                                            <i class="bi bi-check-circle-fill me-1"></i>Verified
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-check-circle"></i>
                                    <p>All pledges verified!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($pledges->hasPages())
        <div class="mt-4">
            {{ $pledges->links() }}
        </div>
    @endif

    {{-- Reject Pledge Modals --}}
    @foreach ($pledges as $pledge)
        @if ($pledge->status === 'pending')
            <div class="modal fade" id="rejectPledgeModal-{{ $pledge->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.pledges.reject', $pledge) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Pledge</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to reject pledge <strong>{{ $pledge->reference_number }}</strong>?
                                </p>
                                <div class="mb-3">
                                    <label for="rejection_reason_{{ $pledge->id }}" class="form-label">Reason for
                                        Rejection <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="rejection_reason_{{ $pledge->id }}" name="rejection_reason" rows="3"
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
    @endforeach
@endsection

@section('styles')
    .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 100px;
    }

    .btn-action-pending {
    background-color: var(--relief-red);
    color: #ffffff;
    }

    .btn-action-pending:hover {
    background-color: var(--relief-vivid-red);
    }

    .btn-action-verify {
    background-color: #198754;
    color: #ffffff;
    }

    .btn-action-verify:hover {
    background-color: #157347;
    }

    .btn-action-reject {
    background-color: #dc3545;
    color: #ffffff;
    }

    .btn-action-reject:hover {
    background-color: #bb2d3b;
    }

    .btn-action-verified {
    background-color: #198754;
    color: #ffffff;
    }

    .btn-action-verified:hover {
    background-color: #157347;
    }

    .btn-action-distributed {
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    }

    @media (max-width: 768px) {
    .content-card-body {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    }

    .admin-table {
    min-width: 640px;
    }

    .btn-action {
    padding: 6px 10px;
    font-size: 12px;
    min-width: 70px;
    }

    .d-flex.gap-1 {
    flex-wrap: nowrap;
    }
    }
@endsection
