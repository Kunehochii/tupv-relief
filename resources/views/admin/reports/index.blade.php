@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('page', 'reports')

@section('content')
    <h1 class="page-title">Reports & Analytics</h1>

    <!-- Stats Cards -->
    <div class="stat-cards-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($stats['total_pledges'] ?? 0) }}</span>
                <span class="stat-label">Total Pledges</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($stats['total_inkind'] ?? 0) }}</span>
                <span class="stat-label">Items Pledged</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($stats['active_donors'] ?? 0) }}</span>
                <span class="stat-label">Active Donors (30d)</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <i class="bi bi-heart"></i>
            </div>
            <div class="stat-content">
                <span class="stat-value">{{ number_format($stats['families_helped'] ?? 0) }}</span>
                <span class="stat-label">Families Helped</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card mb-4">
        <div class="filter-card-body">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="filter-form">
                <div class="filter-group">
                    <label class="filter-label">Date From</label>
                    <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Date To</label>
                    <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Donation Drive</label>
                    <select name="drive_id" class="filter-select">
                        <option value="">All Donation Drives</option>
                        @foreach ($drives as $drive)
                            <option value="{{ $drive->id }}" {{ request('drive_id') == $drive->id ? 'selected' : '' }}>
                                {{ $drive->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="btn-filter-reset">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="reports-grid">
        <!-- Drive Performance -->
        <div class="reports-main">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">Donation Drive Performance</h5>
                    <a href="{{ route('admin.reports.export', ['type' => 'drives']) }}" class="btn-export">
                        <i class="bi bi-download me-1"></i>Export
                    </a>
                </div>
                <div class="content-card-body">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Donation Drive</th>
                                <th>Status</th>
                                <th>Pledges</th>
                                <th>Collected</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($driveStats as $drive)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.drives.show', $drive) }}">
                                            {{ Str::limit($drive->name, 28) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($drive->status === 'active')
                                            <span class="status-badge status-active">Active</span>
                                        @elseif($drive->status === 'completed')
                                            <span class="status-badge status-completed">Completed</span>
                                        @else
                                            <span class="status-badge status-closed">{{ ucfirst($drive->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $drive->pledges_count }}</td>
                                    <td>
                                        @if ($drive->target_type === 'financial')
                                            ₱{{ number_format($drive->collected_amount, 0) }}
                                        @else
                                            {{ number_format($drive->collected_amount) }} items
                                        @endif
                                    </td>
                                    <td style="width: 130px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress-sm flex-grow-1">
                                                <div class="progress-bar"
                                                    style="width: {{ $drive->progress_percentage }}%"></div>
                                            </div>
                                            <span class="progress-text">{{ $drive->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="bi bi-folder"></i>
                                            <p>No donation drive data available</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="reports-sidebar">
            <!-- Top Donors -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="content-card-title">Top Donors</h5>
                </div>
                <div class="content-card-body">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Donor</th>
                                <th>Pledges</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDonors as $donor)
                                <tr>
                                    <td>
                                        {{ Str::limit($donor->name, 14) }}
                                        @if ($donor->role === 'ngo')
                                            <span class="donor-badge">NGO</span>
                                        @endif
                                    </td>
                                    <td>{{ $donor->pledges_count }}</td>
                                    <td>{{ number_format($donor->total_quantity ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">
                                        <span class="text-muted">No donor data</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Export Actions -->
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">Export Reports</h5>
                </div>
                <div class="export-card-body">
                    <a href="{{ route('admin.reports.export', ['type' => 'pledges']) }}" class="export-btn">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        <span>Export All Pledges</span>
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'donors']) }}" class="export-btn">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        <span>Export Donor List</span>
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'impact']) }}" class="export-btn">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        <span>Export Impact Report</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- NGO Performance -->
    <div class="content-card mt-4">
        <div class="content-card-header">
            <h5 class="content-card-title">NGO Contributions</h5>
            <a href="{{ route('admin.reports.export', ['type' => 'ngos']) }}" class="btn-export">
                <i class="bi bi-download me-1"></i>Export
            </a>
        </div>
        <div class="content-card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Pledges</th>
                        <th>Total Quantity</th>
                        <th>Link Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ngoStats as $ngo)
                        <tr>
                            <td>{{ $ngo->organization_name ?? $ngo->name }}</td>
                            <td>
                                @if ($ngo->verification_status === 'verified')
                                    <span class="status-badge status-active">Verified</span>
                                @elseif($ngo->verification_status === 'pending')
                                    <span class="status-badge status-pending">Pending</span>
                                @else
                                    <span
                                        class="status-badge status-closed">{{ ucfirst($ngo->verification_status ?? 'N/A') }}</span>
                                @endif
                            </td>
                            <td>{{ $ngo->pledges_count }}</td>
                            <td>{{ number_format($ngo->total_quantity ?? 0) }} items</td>
                            <td>{{ number_format($ngo->link_clicks_count ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <p>No NGO data available</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('styles')
    .stat-icon-green {
    background-color: #198754;
    }

    .stat-icon-orange {
    background-color: var(--relief-orange);
    }

    .stat-icon-red {
    background-color: var(--relief-red);
    }

    .filter-card-body {
    padding: 20px;
    }

    .filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
    }

    .filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 160px;
    }

    .filter-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--relief-gray-blue);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .filter-input,
    .filter-select {
    padding: 10px 14px;
    border: 1px solid #d0d0d0;
    border-radius: 8px;
    font-size: 14px;
    background-color: #ffffff;
    transition: border-color 0.2s;
    }

    .filter-input:focus,
    .filter-select:focus {
    border-color: var(--relief-dark-blue);
    outline: none;
    }

    .filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    }

    .btn-filter {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
    }

    .btn-filter:hover {
    background-color: #000155;
    }

    .btn-filter-reset {
    padding: 10px 16px;
    color: var(--relief-gray-blue);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s;
    }

    .btn-filter-reset:hover {
    color: var(--relief-dark-blue);
    }

    .reports-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    }

    .btn-export {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    background-color: rgba(0, 1, 103, 0.08);
    color: var(--relief-dark-blue);
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    }

    .btn-export:hover {
    background-color: var(--relief-dark-blue);
    color: #ffffff;
    }

    .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
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

    .status-pending {
    background-color: rgba(255, 174, 68, 0.15);
    color: #b37700;
    }

    .status-closed {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    }

    .progress-text {
    font-size: 11px;
    color: var(--relief-gray-blue);
    min-width: 32px;
    }

    .donor-badge {
    display: inline-block;
    padding: 2px 6px;
    background-color: rgba(0, 1, 103, 0.1);
    color: var(--relief-dark-blue);
    font-size: 10px;
    font-weight: 600;
    border-radius: 4px;
    margin-left: 4px;
    }

    .export-card-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    }

    .export-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background-color: #fafafa;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    color: var(--relief-dark-blue);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
    }

    .export-btn:hover {
    background-color: var(--relief-dark-blue);
    border-color: var(--relief-dark-blue);
    color: #ffffff;
    }

    .export-btn i {
    font-size: 18px;
    }

    @media (max-width: 1200px) {
    .reports-grid {
    grid-template-columns: 1fr;
    }

    .filter-form {
    flex-direction: column;
    align-items: stretch;
    }

    .filter-group {
    width: 100%;
    }
    }

    @media (max-width: 768px) {
    .filter-card-body {
    padding: 14px;
    }

    .filter-input,
    .filter-select {
    padding: 8px 12px;
    font-size: 13px;
    }

    .filter-actions {
    flex-direction: row;
    }

    .btn-filter {
    flex: 1;
    justify-content: center;
    padding: 10px 14px;
    }

    .content-card-body {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    }

    .admin-table {
    min-width: 520px;
    }

    .export-card-body {
    padding: 12px;
    gap: 8px;
    }

    .export-btn {
    padding: 10px 12px;
    font-size: 12px;
    }

    .btn-export {
    padding: 5px 10px;
    font-size: 12px;
    }
    }
@endsection
