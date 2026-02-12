@extends('layouts.app')

@section('title', 'Organization Pledges')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
            <h4 class="mb-0"><span class="d-none d-sm-inline">Organization </span>Pledges</h4>
            <a href="{{ route('ngo.pledges.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1 me-sm-2"></i><span class="d-none d-sm-inline">New Pledge</span><span
                    class="d-sm-none">New</span>
            </a>
        </div>

        <!-- Impact Stats -->
        <div class="row g-2 g-md-3 mb-3 mb-md-4">
            <div class="col-4">
                <div class="card bg-light border-0">
                    <div class="card-body text-center p-2 p-md-3">
                        <i class="bi bi-people fs-4 fs-md-3 text-primary"></i>
                        <h4 class="mb-0 mt-1 mt-md-2 fs-5 fs-md-4">{{ $impact['families_helped'] ?? 0 }}</h4>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">Families Helped</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card bg-light border-0">
                    <div class="card-body text-center p-2 p-md-3">
                        <i class="bi bi-box-seam fs-4 fs-md-3 text-success"></i>
                        <h4 class="mb-0 mt-1 mt-md-2 fs-5 fs-md-4">{{ $impact['relief_packages'] ?? 0 }}</h4>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">Relief Packages</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card bg-light border-0">
                    <div class="card-body text-center p-2 p-md-3">
                        <i class="bi bi-gift fs-4 fs-md-3 text-info"></i>
                        <h4 class="mb-0 mt-1 mt-md-2 fs-5 fs-md-4">{{ $impact['items_distributed'] ?? 0 }}</h4>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">Items Distributed</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desktop Table (hidden on mobile) --}}
        <div class="card d-none d-md-block">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reference</th>
                                <th>Drive</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pledges as $pledge)
                                <tr>
                                    <td>
                                        <a href="{{ route('ngo.pledges.show', $pledge) }}" class="fw-medium">
                                            {{ $pledge->reference_number }}
                                        </a>
                                    </td>
                                    <td>{{ $pledge->drive->name }}</td>
                                    <td>
                                        @if ($pledge->items)
                                            {{ Str::limit(implode(', ', $pledge->items), 30) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pledge->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($pledge->status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($pledge->status === 'distributed')
                                            <span class="badge"
                                                style="background-color: var(--relief-purple);">Distributed</span>
                                        @else
                                            <span class="badge bg-danger">Expired</span>
                                        @endif
                                    </td>
                                    <td>{{ $pledge->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('ngo.pledges.show', $pledge) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 mb-2"></i>
                                        <p class="mb-0">No pledges yet. <a href="{{ route('ngo.pledges.create') }}">Make
                                                your first pledge</a></p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="d-md-none">
            @forelse($pledges as $pledge)
                <a href="{{ route('ngo.pledges.show', $pledge) }}" class="text-decoration-none">
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="fw-semibold text-primary"
                                        style="font-size: 0.85rem;">{{ $pledge->reference_number }}</span>
                                    <span class="d-block text-dark fw-medium"
                                        style="font-size: 0.9rem;">{{ Str::limit($pledge->drive->name, 35) }}</span>
                                </div>
                                @if ($pledge->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($pledge->status === 'verified')
                                    <span class="badge bg-success">Verified</span>
                                @elseif($pledge->status === 'distributed')
                                    <span class="badge" style="background-color: var(--relief-purple);">Distributed</span>
                                @else
                                    <span class="badge bg-danger">Expired</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    @if ($pledge->items)
                                        {{ Str::limit(implode(', ', $pledge->items), 40) }}
                                    @else
                                        No items specified
                                    @endif
                                </small>
                                <small class="text-muted">{{ $pledge->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-1">No pledges yet.</p>
                    <a href="{{ route('ngo.pledges.create') }}">Make your first pledge</a>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $pledges->links() }}
        </div>
    </div>
@endsection
