@extends('layouts.app')

@section('title', 'Reports - Admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Reports & Analytics</h2>
    
    <div class="row mb-4">
        <!-- Summary Cards -->
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Total Donations</h6>
                    <h2 class="mb-0">₱{{ number_format($stats['total_financial'] ?? 0, 2) }}</h2>
                    <small>Financial contributions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title opacity-75">In-Kind Items</h6>
                    <h2 class="mb-0">{{ number_format($stats['total_inkind'] ?? 0) }}</h2>
                    <small>Items donated</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Active Donors</h6>
                    <h2 class="mb-0">{{ number_format($stats['active_donors'] ?? 0) }}</h2>
                    <small>Unique contributors</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Impact</h6>
                    <h2 class="mb-0">{{ number_format($stats['families_helped'] ?? 0) }}</h2>
                    <small>Families helped</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Drive</label>
                    <select name="drive_id" class="form-select">
                        <option value="">All Drives</option>
                        @foreach($drives as $drive)
                            <option value="{{ $drive->id }}" {{ request('drive_id') == $drive->id ? 'selected' : '' }}>
                                {{ $drive->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <!-- Drive Performance -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Drive Performance</span>
                    <a href="{{ route('admin.reports.export', ['type' => 'drives']) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>Export
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Drive</th>
                                <th>Status</th>
                                <th>Pledges</th>
                                <th>Collected</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driveStats as $drive)
                                <tr>
                                    <td>{{ Str::limit($drive->name, 30) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $drive->status === 'active' ? 'success' : ($drive->status === 'completed' ? 'secondary' : 'warning') }}">
                                            {{ ucfirst($drive->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $drive->pledges_count }}</td>
                                    <td>
                                        @if($drive->target_type === 'financial')
                                            ₱{{ number_format($drive->collected_amount, 0) }}
                                        @else
                                            {{ number_format($drive->collected_amount) }} items
                                        @endif
                                    </td>
                                    <td style="width: 150px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                                            </div>
                                            <small>{{ $drive->progress_percentage }}%</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Top Donors -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Top Donors</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Donor</th>
                                <th>Pledges</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topDonors as $donor)
                                <tr>
                                    <td>
                                        {{ Str::limit($donor->name, 15) }}
                                        @if($donor->role === 'ngo')
                                            <span class="badge bg-info">NGO</span>
                                        @endif
                                    </td>
                                    <td>{{ $donor->pledges_count }}</td>
                                    <td>₱{{ number_format($donor->total_amount, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">Export Reports</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reports.export', ['type' => 'pledges']) }}" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export All Pledges
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'donors']) }}" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export Donor List
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'impact']) }}" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export Impact Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- NGO Performance -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>NGO Contributions</span>
            <a href="{{ route('admin.reports.export', ['type' => 'ngos']) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-download me-1"></i>Export
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Pledges</th>
                        <th>Financial</th>
                        <th>In-Kind</th>
                        <th>Link Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ngoStats as $ngo)
                        <tr>
                            <td>{{ $ngo->organization_name ?? $ngo->name }}</td>
                            <td>
                                @if($ngo->verification_status === 'verified')
                                    <span class="badge bg-success">Verified</span>
                                @elseif($ngo->verification_status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($ngo->verification_status ?? 'N/A') }}</span>
                                @endif
                            </td>
                            <td>{{ $ngo->pledges_count }}</td>
                            <td>₱{{ number_format($ngo->financial_total ?? 0, 0) }}</td>
                            <td>{{ number_format($ngo->inkind_total ?? 0) }} items</td>
                            <td>{{ number_format($ngo->link_clicks_count ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No NGO data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
