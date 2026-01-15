@extends('layouts.app')

@section('title', 'Manage Drives')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Manage Donation Drives</h4>
        <a href="{{ route('admin.drives.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create Drive
        </a>
    </div>
    
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
                            <th>Drive Name</th>
                            <th>Status</th>
                            <th>End Date</th>
                            <th>Pledges</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drives as $drive)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.drives.show', $drive) }}" class="fw-medium">
                                        {{ $drive->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($drive->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($drive->status === 'completed')
                                        <span class="badge bg-primary">Completed</span>
                                    @else
                                        <span class="badge bg-secondary">Closed</span>
                                    @endif
                                </td>
                                <td>{{ $drive->end_date->format('M d, Y') }}</td>
                                <td>{{ $drive->pledges_count }}</td>
                                <td style="width: 150px;">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $drive->progress_percentage }}%</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.drives.show', $drive) }}" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.drives.edit', $drive) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($drive->status === 'active')
                                            <form method="POST" action="{{ route('admin.drives.close', $drive) }}" class="d-inline" 
                                                onsubmit="return confirm('Are you sure you want to close this drive?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger" title="Close">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No drives found. <a href="{{ route('admin.drives.create') }}">Create one</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $drives->links() }}
    </div>
</div>
@endsection
