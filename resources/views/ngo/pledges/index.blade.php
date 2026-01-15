@extends('layouts.app')

@section('title', 'Organization Pledges')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Organization Pledges</h4>
        <a href="{{ route('ngo.pledges.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Pledge
        </a>
    </div>
    
    <!-- Impact Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-light border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-3 text-primary"></i>
                    <h4 class="mb-0 mt-2">{{ $impact['families_helped'] ?? 0 }}</h4>
                    <small class="text-muted">Families Helped</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light border-0">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-3 text-success"></i>
                    <h4 class="mb-0 mt-2">{{ $impact['relief_packages'] ?? 0 }}</h4>
                    <small class="text-muted">Relief Packages</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light border-0">
                <div class="card-body text-center">
                    <i class="bi bi-gift fs-3 text-info"></i>
                    <h4 class="mb-0 mt-2">{{ $impact['items_distributed'] ?? 0 }}</h4>
                    <small class="text-muted">Items Distributed</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
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
                                    @if($pledge->items)
                                        {{ Str::limit(implode(', ', $pledge->items), 30) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($pledge->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($pledge->status === 'verified')
                                        <span class="badge bg-success">Verified</span>
                                    @elseif($pledge->status === 'distributed')
                                        <span class="badge" style="background-color: var(--relief-purple);">Distributed</span>
                                    @else
                                        <span class="badge bg-danger">Expired</span>
                                    @endif
                                </td>
                                <td>{{ $pledge->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('ngo.pledges.show', $pledge) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 mb-2"></i>
                                    <p class="mb-0">No pledges yet. <a href="{{ route('ngo.pledges.create') }}">Make your first pledge</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $pledges->links() }}
    </div>
</div>
@endsection
