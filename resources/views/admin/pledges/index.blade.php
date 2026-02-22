@extends('layouts.admin')

@section('title', 'All Pledges')

@section('page', 'pledges')

@section('content')
    <h1 class="page-title">Pledge Verifications</h1>

    <div class="content-card">
        <div class="content-card-header">
            <form action="{{ route('admin.pledges.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="distributed" {{ request('status') === 'distributed' ? 'selected' : '' }}>Distributed
                    </option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <select name="drive_id" class="form-select form-select-sm" style="width: auto;"
                    onchange="this.form.submit()">
                    <option value="">All Donation Drives</option>
                    @foreach ($drives as $drive)
                        <option value="{{ $drive->id }}" {{ request('drive_id') == $drive->id ? 'selected' : '' }}>
                            {{ $drive->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
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
                                @if ($pledge->status === 'distributed' || $pledge->status === 'expired')
                                    <span class="text-muted">-</span>
                                @elseif($minutesLeft > 0)
                                    {{ floor($minutesLeft / 60) }}:{{ str_pad($minutesLeft % 60, 2, '0', STR_PAD_LEFT) }}h
                                    left
                                @else
                                    <span class="text-danger">Expired</span>
                                @endif
                            </td>
                            <td>
                                @if ($pledge->status === 'pending')
                                    <form method="POST" action="{{ route('admin.pledges.verify', $pledge) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-action-pending">
                                            Pending
                                        </button>
                                    </form>
                                @elseif($pledge->status === 'verified')
                                    <form method="POST" action="{{ route('admin.pledges.distribute', $pledge) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-action-verified">
                                            <i class="bi bi-check-circle-fill me-1"></i>Verified
                                        </button>
                                    </form>
                                @elseif($pledge->status === 'distributed')
                                    <span class="btn-action btn-action-distributed">
                                        <i class="bi bi-box-seam me-1"></i>Distributed
                                    </span>
                                @else
                                    <span class="btn-action btn-action-expired">
                                        {{ ucfirst($pledge->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No pledges found</p>
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
            {{ $pledges->withQueryString()->links() }}
        </div>
    @endif
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

    .btn-action-expired {
    background-color: var(--relief-gray-blue);
    color: #ffffff;
    }

    @media (max-width: 768px) {
    .content-card-header {
    flex-wrap: wrap;
    gap: 8px;
    }

    .content-card-header .d-flex.gap-2 {
    flex-direction: column;
    width: 100%;
    }

    .content-card-header .form-select {
    width: 100% !important;
    }

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
    min-width: 80px;
    }
    }
@endsection
