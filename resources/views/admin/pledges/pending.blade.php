@extends('layouts.admin')

@section('title', 'Pending Pledges')

@section('page', 'pledges')

@section('content')
    <h1 class="page-title">Pending Pledge Verifications</h1>

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
@endsection
