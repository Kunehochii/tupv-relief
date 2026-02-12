@extends('layouts.admin')

@section('title', 'Manage Drives')

@section('page', 'drives')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Manage Donation Drives</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.drives.map') }}" class="btn-header-action">
                <i class="bi bi-geo-alt me-1"></i>Map View
            </a>
            <a href="{{ route('admin.drives.create') }}" class="btn-header-action btn-header-primary">
                <i class="bi bi-plus-lg me-1"></i>Create Drive
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <form action="{{ route('admin.drives.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: auto;"
                    onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @if (request()->has('status'))
                    <a href="{{ route('admin.drives.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
        <div class="content-card-body">
            <table class="admin-table">
                <thead>
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
                                <a href="{{ route('admin.drives.show', $drive) }}">
                                    {{ $drive->name }}
                                </a>
                            </td>
                            <td>
                                @if ($drive->status === 'active')
                                    <span class="status-badge status-active">Active</span>
                                @elseif($drive->status === 'completed')
                                    <span class="status-badge status-completed">Completed</span>
                                @elseif($drive->status === 'upcoming')
                                    <span class="status-badge status-upcoming">Upcoming</span>
                                @else
                                    <span class="status-badge status-closed">Closed</span>
                                @endif
                            </td>
                            <td>{{ $drive->end_date->format('M d, Y') }}</td>
                            <td>{{ $drive->pledges_count }}</td>
                            <td style="width: 140px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-sm flex-grow-1">
                                        <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                                    </div>
                                    <span class="progress-text">{{ $drive->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.drives.show', $drive) }}" class="btn-icon" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.drives.edit', $drive) }}" class="btn-icon" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($drive->status === 'active')
                                        <form method="POST" action="{{ route('admin.drives.close', $drive) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to close this drive?')">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Close">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-folder"></i>
                                    <p>No drives found</p>
                                    <a href="{{ route('admin.drives.create') }}"
                                        class="btn-relief-primary btn btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i>Create Drive
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($drives->hasPages())
        <div class="mt-4">
            {{ $drives->links() }}
        </div>
    @endif
@endsection

@section('styles')
    .btn-header-action {
    display: inline-flex;
    align-items: center;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    background-color: #ffffff;
    color: var(--relief-dark-blue);
    border: 1px solid #d0d0d0;
    transition: all 0.2s;
    }

    .btn-header-action:hover {
    background-color: #f5f5f5;
    color: var(--relief-dark-blue);
    }

    .btn-header-primary {
    background-color: var(--relief-vivid-orange);
    color: #ffffff;
    border-color: var(--relief-vivid-orange);
    }

    .btn-header-primary:hover {
    background-color: var(--relief-red);
    border-color: var(--relief-red);
    color: #ffffff;
    }

    .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    }

    .status-active {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
    }

    .status-completed {
    background-color: rgba(0, 1, 103, 0.1);
    color: var(--relief-dark-blue);
    }

    .status-upcoming {
    background-color: rgba(255, 174, 68, 0.15);
    color: #b37700;
    }

    .status-closed {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    }

    .progress-text {
    font-size: 12px;
    color: var(--relief-gray-blue);
    min-width: 35px;
    }

    .action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
    }

    .btn-icon {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    background-color: #ffffff;
    color: var(--relief-dark-blue);
    text-decoration: none;
    transition: all 0.2s;
    }

    .btn-icon:hover {
    background-color: var(--relief-dark-blue);
    border-color: var(--relief-dark-blue);
    color: #ffffff;
    }

    .btn-icon-danger:hover {
    background-color: var(--relief-red);
    border-color: var(--relief-red);
    color: #ffffff;
    }

    @media (max-width: 768px) {
    .btn-header-action {
    padding: 8px 12px;
    font-size: 13px;
    }

    .d-flex.gap-2 {
    flex-wrap: wrap;
    }

    .d-flex.justify-content-between.align-items-center.mb-4 {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 12px;
    }

    .content-card-header {
    flex-wrap: wrap;
    gap: 8px;
    }

    .content-card-body {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    }

    .admin-table {
    min-width: 640px;
    }
    }
@endsection
