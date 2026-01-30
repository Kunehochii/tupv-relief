@extends('layouts.app')

@section('title', $drive->name . ' - Drive Details')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.drives.index') }}">Drives</a></li>
            <li class="breadcrumb-item active">{{ $drive->name }}</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $drive->name }}</h2>
        <div>
            <a href="{{ route('admin.drives.edit', $drive) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            {{-- Cover Photo --}}
            @if($drive->cover_photo)
                <div class="mb-4">
                    <img src="{{ $drive->cover_photo_url }}" alt="{{ $drive->name }}" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                </div>
            @endif
            
            <!-- Details Card -->
            <div class="card mb-4">
                <div class="card-header">Drive Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Status</strong></div>
                        <div class="col-md-9">
                            @if($drive->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($drive->status === 'completed')
                                <span class="badge bg-secondary">Completed</span>
                            @else
                                <span class="badge bg-warning">Upcoming</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Description</strong></div>
                        <div class="col-md-9">{{ $drive->description }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Target</strong></div>
                        <div class="col-md-9">
                            {{ number_format($drive->target_amount) }} {{ $drive->target_type === 'financial' ? 'PHP' : 'items' }}
                            ({{ ucfirst($drive->target_type) }})
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Families Affected</strong></div>
                        <div class="col-md-9">{{ number_format($drive->families_affected ?? 0) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Date Range</strong></div>
                        <div class="col-md-9">
                            {{ $drive->start_date?->format('M d, Y') ?? 'Not set' }} - {{ $drive->end_date?->format('M d, Y') ?? 'Not set' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Progress</strong></div>
                        <div class="col-md-9">
                            @include('partials.progress-bar-3color', [
                                'distributed' => $drive->distributed_percentage,
                                'pledged' => $drive->pledged_percentage,
                            ])
                            <small class="text-muted">
                                Pledged: {{ number_format($drive->pledged_amount ?? 0) }} |
                                Distributed: {{ number_format($drive->distributed_amount ?? 0) }} |
                                Target: {{ number_format($drive->target_amount) }}
                            </small>
                        </div>
                    </div>
                    @if($drive->pack_types_needed && count($drive->pack_types_needed) > 0)
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Pack Types</strong></div>
                            <div class="col-md-9">
                                @foreach($drive->pack_types_needed as $type)
                                    <span class="badge bg-info me-1">{{ $type }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Drive Items Card --}}
            @if($drive->driveItems->count() > 0)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Items Needed ({{ $drive->driveItems->count() }})</span>
                        <form action="{{ route('admin.drives.recalculate', $drive) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Recalculate
                            </button>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-end">Needed</th>
                                        <th class="text-end">Pledged</th>
                                        <th class="text-end">Distributed</th>
                                        <th class="text-center">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($drive->driveItems as $item)
                                        <tr>
                                            <td>{{ $item->item_name }}</td>
                                            <td class="text-end">{{ number_format($item->quantity_needed, 2) }} {{ $item->unit }}</td>
                                            <td class="text-end">{{ number_format($item->quantity_pledged, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->quantity_distributed, 2) }}</td>
                                            <td style="width: 120px;">
                                                @include('partials.progress-bar-3color', [
                                                    'distributed' => $item->distributed_percentage,
                                                    'pledged' => $item->pledged_percentage,
                                                    'height' => '12px',
                                                ])
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Location Card -->
            @if($drive->latitude && $drive->longitude)
                <div class="card mb-4">
                    <div class="card-header">Location</div>
                    <div class="card-body">
                        <p class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ $drive->address }}</p>
                        <div id="map" style="height: 300px; border-radius: 0.5rem;"></div>
                    </div>
                </div>
            @endif
            
            <!-- Recent Pledges -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Recent Pledges</span>
                    <a href="{{ route('admin.pledges.index', ['drive_id' => $drive->id]) }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
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
                                        <a href="{{ route('admin.pledges.show', $pledge) }}">{{ $pledge->reference_number }}</a>
                                    </td>
                                    <td>{{ $pledge->user->name }}</td>
                                    <td>
                                        @if($pledge->pledge_type === 'financial')
                                            <span class="badge bg-success">₱{{ number_format($pledge->financial_amount, 2) }}</span>
                                        @else
                                            <span class="badge bg-primary">{{ $pledge->pledgeItems->count() }} items</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $pledge->status_color }}">{{ ucfirst($pledge->status) }}</span>
                                    </td>
                                    <td>{{ $pledge->created_at->format('M d') }}</td>
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
        
        <div class="col-lg-4">
            <!-- Progress Card -->
            <div class="card mb-4">
                <div class="card-header">Progress Summary</div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h1 class="display-4 text-success">{{ $drive->distributed_percentage }}%</h1>
                        <p class="text-muted mb-0">distributed</p>
                        <small class="text-primary">{{ $drive->pledged_percentage }}% pledged</small>
                    </div>
                    @include('partials.progress-bar-3color', [
                        'distributed' => $drive->distributed_percentage,
                        'pledged' => $drive->pledged_percentage,
                    ])
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-square-fill text-success me-1"></i>Distributed: {{ number_format($drive->distributed_amount ?? 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-square-fill text-primary me-1"></i>Pledged: {{ number_format($drive->pledged_amount ?? 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-square-fill text-secondary me-1"></i>Target: {{ number_format($drive->target_amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Card -->
            <div class="card mb-4">
                <div class="card-header">Statistics</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Total Pledges</span>
                        <strong>{{ $drive->pledges()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Verified</span>
                        <strong class="text-success">{{ $drive->pledges()->where('status', 'verified')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Distributed</span>
                        <strong class="text-primary">{{ $drive->pledges()->where('status', 'distributed')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Pending</span>
                        <strong class="text-warning">{{ $drive->pledges()->where('status', 'pending')->count() }}</strong>
                    </div>
                    @if($drive->supportingNgos && $drive->supportingNgos->count() > 0)
                        <div class="d-flex justify-content-between py-2">
                            <span>Supporting NGOs</span>
                            <strong class="text-info">{{ $drive->supportingNgos->count() }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">Actions</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('drive.preview', $drive) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="bi bi-eye me-1"></i>View Public Page
                        </a>
                        @if($drive->status === 'active')
                            <form action="{{ route('admin.drives.complete', $drive) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success w-100" onclick="return confirm('Mark this drive as completed?')">
                                    <i class="bi bi-check-circle me-1"></i>Mark Complete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($drive->latitude && $drive->longitude)
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
@endsection
