@extends('layouts.admin')

@section('title', $drive->name . ' - Drive Details')

@section('page', 'drives')

@section('styles')
    .drive-header {
    background: linear-gradient(135deg, var(--relief-dark-blue) 0%, #001199 100%);
    border-radius: 16px;
    padding: 24px;
    color: #ffffff;
    margin-bottom: 24px;
    }

    .drive-header-content {
    display: flex;
    gap: 24px;
    }

    .drive-cover {
    width: 280px;
    height: 160px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
    }

    .drive-cover-placeholder {
    width: 280px;
    height: 160px;
    border-radius: 12px;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    }

    .drive-cover-placeholder i {
    font-size: 48px;
    opacity: 0.5;
    }

    .drive-info {
    flex: 1;
    }

    .drive-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
    }

    .drive-description {
    opacity: 0.9;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 16px;
    }

    .drive-meta {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    }

    .drive-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    opacity: 0.9;
    }

    .drive-meta-item i {
    font-size: 16px;
    }

    .content-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    }

    .detail-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .detail-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    color: var(--relief-dark-blue);
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

    .detail-card-body {
    padding: 20px;
    }

    .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    }

    .detail-row:last-child {
    border-bottom: none;
    }

    .detail-label {
    color: #666;
    }

    .detail-value {
    font-weight: 500;
    color: #333;
    }

    .progress-card {
    text-align: center;
    padding: 24px;
    }

    .progress-percentage {
    font-size: 48px;
    font-weight: 700;
    color: var(--relief-dark-blue);
    line-height: 1;
    }

    .progress-label {
    color: #666;
    font-size: 14px;
    margin-bottom: 16px;
    }

    .progress-stats {
    margin-top: 20px;
    }

    .progress-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    padding: 6px 0;
    }

    .stat-dot {
    width: 10px;
    height: 10px;
    border-radius: 2px;
    }

    .stat-dot.distributed { background-color: #198754; }
    .stat-dot.pledged { background-color: #0d6efd; }
    .stat-dot.target { background-color: #6c757d; }

    .items-table {
    width: 100%;
    }

    .items-table th {
    background: var(--relief-dark-blue);
    color: #ffffff;
    font-size: 12px;
    font-weight: 600;
    padding: 12px;
    text-align: left;
    }

    .items-table th:not(:first-child) {
    text-align: right;
    }

    .items-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
    }

    .items-table td:not(:first-child) {
    text-align: right;
    }

    .pledges-table {
    width: 100%;
    }

    .pledges-table th {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    padding: 12px;
    border-bottom: 2px solid #eee;
    text-align: left;
    }

    .pledges-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13px;
    }

    .pledges-table tbody tr:hover {
    background-color: #f8f9fa;
    }

    .action-card .btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
    margin-bottom: 10px;
    }

    .action-card .btn-action:last-child {
    margin-bottom: 0;
    }

    .btn-action-primary {
    background: var(--relief-vivid-orange);
    color: #ffffff;
    border: none;
    }

    .btn-action-primary:hover {
    background: var(--relief-red);
    color: #ffffff;
    }

    .btn-action-outline {
    background: transparent;
    color: var(--relief-dark-blue);
    border: 1px solid #d0d0d0;
    }

    .btn-action-outline:hover {
    background: #f5f5f5;
    color: var(--relief-dark-blue);
    }

    .btn-action-success {
    background: #198754;
    color: #ffffff;
    border: none;
    }

    .btn-action-success:hover {
    background: #157347;
    color: #ffffff;
    }

    .pack-badge {
    display: inline-block;
    padding: 4px 10px;
    background: rgba(0, 1, 103, 0.1);
    color: var(--relief-dark-blue);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    margin-right: 6px;
    margin-bottom: 6px;
    }

    .map-container {
    height: 200px;
    border-radius: 8px;
    overflow: hidden;
    }

    #map {
    height: 100%;
    width: 100%;
    }

    .stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    }

    .stat-card-mini {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    }

    .stat-card-mini .value {
    font-size: 24px;
    font-weight: 700;
    color: var(--relief-dark-blue);
    }

    .stat-card-mini .label {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    }
@endsection

@section('content')
    {{-- Breadcrumb and Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ route('admin.drives.index') }}" class="text-decoration-none">Drives</a>
                </li>
                <li class="breadcrumb-item active">{{ Str::limit($drive->name, 30) }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('drive.preview', $drive) }}" class="btn-header-action" target="_blank">
                <i class="bi bi-eye me-1"></i>Public Page
            </a>
            <a href="{{ route('admin.drives.edit', $drive) }}" class="btn-header-action btn-header-primary">
                <i class="bi bi-pencil me-1"></i>Edit Drive
            </a>
        </div>
    </div>

    {{-- Drive Header --}}
    <div class="drive-header">
        <div class="drive-header-content">
            @if ($drive->cover_photo)
                <img src="{{ $drive->cover_photo_url }}" alt="{{ $drive->name }}" class="drive-cover">
            @else
                <div class="drive-cover-placeholder">
                    <i class="bi bi-image"></i>
                </div>
            @endif
            <div class="drive-info">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h1 class="drive-title mb-0">{{ $drive->name }}</h1>
                    @if ($drive->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($drive->status === 'completed')
                        <span class="badge bg-secondary">Completed</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ ucfirst($drive->status) }}</span>
                    @endif
                </div>
                <p class="drive-description">{{ Str::limit($drive->description, 200) }}</p>
                <div class="drive-meta">
                    <div class="drive-meta-item">
                        <i class="bi bi-calendar3"></i>
                        {{ $drive->start_date?->format('M d') ?? 'N/A' }} -
                        {{ $drive->end_date?->format('M d, Y') ?? 'N/A' }}
                    </div>
                    <div class="drive-meta-item">
                        <i class="bi bi-people"></i>
                        {{ number_format($drive->families_affected ?? 0) }} families
                    </div>
                    @if ($drive->address)
                        <div class="drive-meta-item">
                            <i class="bi bi-geo-alt"></i>
                            {{ Str::limit($drive->address, 40) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="content-grid">
        {{-- Main Content --}}
        <div class="main-content">
            {{-- Pack Types --}}
            @if ($drive->pack_types_needed && count($drive->pack_types_needed) > 0)
                <div class="detail-card mb-4">
                    <div class="detail-card-header">Pack Types Needed</div>
                    <div class="detail-card-body">
                        @foreach ($drive->pack_types_needed as $type)
                            <span class="pack-badge">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ \App\Models\ReliefPackItem::PACK_TYPES[$type] ?? ucfirst($type) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Items Needed --}}
            @if ($drive->driveItems->count() > 0)
                <div class="detail-card mb-4">
                    <div class="detail-card-header">
                        <span>Items Needed ({{ $drive->driveItems->count() }})</span>
                        <form action="{{ route('admin.drives.recalculate', $drive) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Recalculate
                            </button>
                        </form>
                    </div>
                    <div style="max-height: 350px; overflow-y: auto;">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Needed</th>
                                    <th>Pledged</th>
                                    <th>Distributed</th>
                                    <th style="width: 100px;">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drive->driveItems as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ number_format($item->quantity_needed, 2) }} {{ $item->unit }}</td>
                                        <td>{{ number_format($item->quantity_pledged, 2) }}</td>
                                        <td>{{ number_format($item->quantity_distributed, 2) }}</td>
                                        <td>
                                            @include('partials.progress-bar-3color', [
                                                'distributed' => $item->distributed_percentage,
                                                'pledged' => $item->pledged_percentage,
                                                'height' => '10px',
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Recent Pledges --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <span>Recent Pledges</span>
                    <a href="{{ route('admin.pledges.index', ['drive_id' => $drive->id]) }}"
                        class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="pledges-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Donor</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($drive->pledges()->latest()->take(5)->get() as $pledge)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.pledges.show', $pledge) }}" class="text-decoration-none">
                                            {{ $pledge->reference_number }}
                                        </a>
                                    </td>
                                    <td>{{ $pledge->user->name }}</td>
                                    <td>
                                        @if ($pledge->pledge_type === 'financial')
                                            <span
                                                class="badge bg-success">₱{{ number_format($pledge->financial_amount, 2) }}</span>
                                        @else
                                            <span class="badge bg-primary">{{ $pledge->pledgeItems->count() }} items</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $pledge->status_color }}">{{ ucfirst($pledge->status) }}</span>
                                    </td>
                                    <td>{{ $pledge->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No pledges yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="sidebar-content">
            {{-- Progress Card --}}
            <div class="detail-card mb-4 progress-card">
                <div class="progress-percentage">{{ $drive->distributed_percentage }}%</div>
                <div class="progress-label">Distributed</div>
                @include('partials.progress-bar-3color', [
                    'distributed' => $drive->distributed_percentage,
                    'pledged' => $drive->pledged_percentage,
                ])
                <div class="progress-stats">
                    <div class="progress-stat">
                        <span class="stat-dot distributed"></span>
                        <span>Distributed: {{ number_format($drive->distributed_amount ?? 0) }}</span>
                    </div>
                    <div class="progress-stat">
                        <span class="stat-dot pledged"></span>
                        <span>Pledged: {{ number_format($drive->pledged_amount ?? 0) }}</span>
                    </div>
                    <div class="progress-stat">
                        <span class="stat-dot target"></span>
                        <span>Target: {{ number_format($drive->target_amount) }}</span>
                    </div>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="detail-card mb-4">
                <div class="detail-card-header">Statistics</div>
                <div class="detail-card-body">
                    <div class="stats-grid">
                        <div class="stat-card-mini">
                            <div class="value">{{ $drive->pledges()->count() }}</div>
                            <div class="label">Total Pledges</div>
                        </div>
                        <div class="stat-card-mini">
                            <div class="value text-warning">{{ $drive->pledges()->where('status', 'pending')->count() }}
                            </div>
                            <div class="label">Pending</div>
                        </div>
                        <div class="stat-card-mini">
                            <div class="value text-success">{{ $drive->pledges()->where('status', 'verified')->count() }}
                            </div>
                            <div class="label">Verified</div>
                        </div>
                        <div class="stat-card-mini">
                            <div class="value text-primary">
                                {{ $drive->pledges()->where('status', 'distributed')->count() }}</div>
                            <div class="label">Distributed</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Location Map --}}
            @if ($drive->latitude && $drive->longitude)
                <div class="detail-card mb-4">
                    <div class="detail-card-header">Location</div>
                    <div class="detail-card-body p-0">
                        <div class="map-container">
                            <div id="map"></div>
                        </div>
                        <div class="p-3">
                            <small class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>{{ $drive->address ?? 'No address provided' }}
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="detail-card action-card">
                <div class="detail-card-header">Actions</div>
                <div class="detail-card-body">
                    <a href="{{ route('admin.drives.edit', $drive) }}" class="btn-action btn-action-primary">
                        <i class="bi bi-pencil"></i>Edit Drive
                    </a>
                    <a href="{{ route('drive.preview', $drive) }}" class="btn-action btn-action-outline"
                        target="_blank">
                        <i class="bi bi-eye"></i>View Public Page
                    </a>
                    @if ($drive->status === 'active')
                        <form action="{{ route('admin.drives.complete', $drive) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-action btn-action-success"
                                onclick="return confirm('Mark this drive as completed?')">
                                <i class="bi bi-check-circle"></i>Mark Complete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($drive->latitude && $drive->longitude)
    @section('scripts')
        <script>
            const map = L.map('map').setView([{{ $drive->latitude }}, {{ $drive->longitude }}], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([{{ $drive->latitude }}, {{ $drive->longitude }}]).addTo(map)
                .bindPopup('<strong>{{ $drive->name }}</strong>')
                .openPopup();
        </script>
    @endsection
@endif
