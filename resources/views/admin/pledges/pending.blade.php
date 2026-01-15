@extends('layouts.app')

@section('title', 'Pending Pledges')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Pending Pledge Verifications</h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Donor</th>
                            <th>Drive</th>
                            <th>Items</th>
                            <th>Submitted</th>
                            <th>Time Left</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pledges as $pledge)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.pledges.show', $pledge) }}" class="fw-medium">
                                        {{ $pledge->reference_number }}
                                    </a>
                                </td>
                                <td>
                                    {{ $pledge->user->display_name }}
                                    <br>
                                    <small class="text-muted">{{ ucfirst($pledge->user->role) }}</small>
                                </td>
                                <td>{{ $pledge->drive->name }}</td>
                                <td>
                                    @if($pledge->items)
                                        {{ implode(', ', array_slice($pledge->items, 0, 2)) }}
                                        @if(count($pledge->items) > 2)
                                            <span class="text-muted">+{{ count($pledge->items) - 2 }} more</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $pledge->created_at->format('M d, H:i') }}</td>
                                <td>
                                    @php
                                        $hoursLeft = 24 - $pledge->created_at->diffInHours(now());
                                    @endphp
                                    @if($hoursLeft > 6)
                                        <span class="text-success">{{ $hoursLeft }}h left</span>
                                    @elseif($hoursLeft > 0)
                                        <span class="text-warning">{{ $hoursLeft }}h left</span>
                                    @else
                                        <span class="text-danger">Expiring soon</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pledges.show', $pledge) }}" class="btn btn-outline-primary">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('admin.pledges.verify', $pledge) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check-circle me-1"></i>Verify
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle fs-3 mb-2"></i>
                                    <p class="mb-0">All pledges verified!</p>
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
