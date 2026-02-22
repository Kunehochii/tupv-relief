@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page', 'dashboard')

@section('content')
    <h1 class="page-title">Dashboard Overview</h1>

    <!-- Stats Cards -->
    <div class="stat-cards-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-folder-fill"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $metrics['total_drives'] }}</span>
                <span class="stat-label">Total Donation Drives</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-play-circle-fill"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $metrics['active_drives'] }}</span>
                <span class="stat-label">Active Donation Drives</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-handshake-fill"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $metrics['total_donations'] }}</span>
                <span class="stat-label">Total Pledges</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ $metrics['pending_verifications'] }}</span>
                <span class="stat-label">Pending Verification</span>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="content-row">
        <!-- Active Drives -->
        <div class="content-card">
            <div class="content-card-header">
                <h2 class="content-card-title">Active Donation Drives</h2>
                <a href="{{ route('admin.drives.index') }}" class="view-all-link">View All</a>
            </div>
            <div class="content-card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Donation Drive Name</th>
                            <th>Date Started</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeDrives as $drive)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.drives.show', $drive) }}">{{ $drive->name }}</a>
                                </td>
                                <td>{{ $drive->start_date ? $drive->start_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="progress-sm">
                                        <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No active donation drives</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="content-card">
            <div class="content-card-header">
                <h2 class="content-card-title">Pending Verifications</h2>
                <a href="{{ route('admin.pledges.pending') }}" class="view-all-link">View All</a>
            </div>
            <div class="content-card-body">
                @forelse($pendingPledges as $pledge)
                    <a href="{{ route('admin.pledges.show', $pledge) }}" class="d-block text-decoration-none"
                        style="padding: 14px 16px; border-bottom: 1px solid #f5f5f5;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong style="color: var(--relief-dark-blue);">{{ $pledge->reference_number }}</strong>
                                <br>
                                <small style="color: var(--relief-gray-blue);">{{ $pledge->user->name }} →
                                    {{ $pledge->drive->name }}</small>
                            </div>
                            <small
                                style="color: var(--relief-gray-blue);">{{ $pledge->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-check-circle"></i>
                        <p>All caught up!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
