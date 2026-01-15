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
        <div class="col-md-8">
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
                        <div class="col-md-3"><strong>Date Range</strong></div>
                        <div class="col-md-9">
                            {{ $drive->start_date->format('M d, Y') }} - {{ $drive->end_date->format('M d, Y') }}
                        </div>
                    </div>
                    @if($drive->items_needed && count($drive->items_needed) > 0)
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Items Needed</strong></div>
                            <div class="col-md-9">
                                @foreach($drive->items_needed as $item)
                                    <span class="badge bg-light text-dark border me-1">{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
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
                                <th>Amount/Qty</th>
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
                                        @if($pledge->type === 'financial')
                                            ₱{{ number_format($pledge->amount, 2) }}
                                        @else
                                            {{ $pledge->quantity }} items
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
        
        <div class="col-md-4">
            <!-- Progress Card -->
            <div class="card mb-4">
                <div class="card-header">Progress</div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h1 class="display-4">{{ $drive->progress_percentage }}%</h1>
                        <p class="text-muted">of target reached</p>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar" style="width: {{ $drive->progress_percentage }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Collected: {{ number_format($drive->collected_amount) }}</span>
                        <span>Target: {{ number_format($drive->target_amount) }}</span>
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
                    <div class="d-flex justify-content-between py-2">
                        <span>Pending</span>
                        <strong class="text-warning">{{ $drive->pledges()->where('status', 'pending')->count() }}</strong>
                    </div>
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
