@extends('layouts.app')

@section('title', 'Donation Receipts')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--dark-blue);">Donation Receipts</h4>
                <p class="text-muted small mb-0">Review and manage donation receipts submitted to your organization.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filter Tabs --}}
        <ul class="nav nav-pills mb-3 gap-1">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}"
                    href="{{ route('ngo.receipts.index') }}"
                    style="{{ !request('status') ? 'background: var(--dark-blue);' : 'color: var(--dark-blue);' }}">
                    All <span class="badge bg-secondary bg-opacity-25 text-dark ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}"
                    href="{{ route('ngo.receipts.index', ['status' => 'pending']) }}"
                    style="{{ request('status') === 'pending' ? 'background: var(--dark-blue);' : 'color: var(--dark-blue);' }}">
                    Pending <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'verified' ? 'active' : '' }}"
                    href="{{ route('ngo.receipts.index', ['status' => 'verified']) }}"
                    style="{{ request('status') === 'verified' ? 'active' : 'color: var(--dark-blue);' }}">
                    Verified <span class="badge bg-success bg-opacity-25 text-dark ms-1">{{ $counts['verified'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'rejected' ? 'active' : '' }}"
                    href="{{ route('ngo.receipts.index', ['status' => 'rejected']) }}"
                    style="{{ request('status') === 'rejected' ? 'active' : 'color: var(--dark-blue);' }}">
                    Rejected <span class="badge bg-danger bg-opacity-25 text-dark ms-1">{{ $counts['rejected'] }}</span>
                </a>
            </li>
        </ul>

        {{-- Receipts Table --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="font-size: 0.8rem;">Donor</th>
                            <th style="font-size: 0.8rem;">Amount</th>
                            <th style="font-size: 0.8rem;">Date</th>
                            <th style="font-size: 0.8rem;">Status</th>
                            <th class="pe-3 text-end" style="font-size: 0.8rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $receipt)
                            <tr>
                                <td class="ps-3">
                                    <div>
                                        <strong class="small">{{ $receipt->user->name }}</strong>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $receipt->user->email }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">₱{{ number_format($receipt->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $receipt->created_at->format('M d, Y') }}</span>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        {{ $receipt->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $receipt->status_color }}">
                                        {{ ucfirst($receipt->status) }}
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('ngo.receipts.show', $receipt) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No donation receipts yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($receipts->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
@endsection
