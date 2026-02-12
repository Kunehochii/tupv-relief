@extends('layouts.app')

@section('title', 'Drive Support')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
            <h4 class="mb-0">
                <i class="bi bi-heart-fill text-danger me-2"></i>
                <span class="d-none d-sm-inline">Drives You Support</span>
                <span class="d-sm-none">Your Drives</span>
            </h4>
            <a href="{{ route('ngo.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i><span class="d-none d-sm-inline">Back to Dashboard</span><span
                    class="d-sm-none">Back</span>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-2 p-md-3">
                @if ($supportedDrives->count() > 0)
                    {{-- Desktop Table (hidden on mobile) --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Drive</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supportedDrives as $drive)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($drive->cover_photo)
                                                    <img src="{{ $drive->cover_photo_url }}" alt="{{ $drive->name }}"
                                                        class="rounded me-3"
                                                        style="width: 60px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 40px;">
                                                        <i class="bi bi-image text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $drive->name }}</strong>
                                                    @if ($drive->address)
                                                        <small class="d-block text-muted">
                                                            <i class="bi bi-geo-alt"></i>
                                                            {{ Str::limit($drive->address, 30) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($drive->isActive())
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($drive->status) }}</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success"
                                                    style="width: {{ $drive->progress_percentage }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $drive->progress_percentage }}%</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $drive->end_date ? $drive->end_date->format('M d, Y') : 'No end date' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('drive.preview', $drive) }}"
                                                    class="btn btn-outline-primary" title="View Drive">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if ($drive->isActive())
                                                    <a href="{{ route('ngo.pledges.create', ['drive_id' => $drive->id]) }}"
                                                        class="btn btn-outline-success" title="Make a Pledge">
                                                        <i class="bi bi-gift"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('ngo.drives.support', $drive) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to withdraw support from this drive?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger"
                                                        title="Withdraw Support">
                                                        <i class="bi bi-x-circle"></i> Withdraw
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="d-md-none">
                        @foreach ($supportedDrives as $drive)
                            <div class="card mb-2 border">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start gap-2 mb-2">
                                        @if ($drive->cover_photo)
                                            <img src="{{ $drive->cover_photo_url }}" alt="{{ $drive->name }}"
                                                class="rounded flex-shrink-0"
                                                style="width: 50px; height: 35px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded flex-shrink-0 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 35px;">
                                                <i class="bi bi-image text-white small"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1 min-width-0">
                                            <strong style="font-size: 0.9rem;">{{ Str::limit($drive->name, 35) }}</strong>
                                            @if ($drive->address)
                                                <small class="d-block text-muted text-truncate">
                                                    <i class="bi bi-geo-alt"></i> {{ Str::limit($drive->address, 40) }}
                                                </small>
                                            @endif
                                        </div>
                                        @if ($drive->isActive())
                                            <span class="badge bg-success flex-shrink-0">Active</span>
                                        @else
                                            <span
                                                class="badge bg-secondary flex-shrink-0">{{ ucfirst($drive->status) }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $drive->progress_percentage }}%"></div>
                                        </div>
                                        <small class="text-muted fw-medium">{{ $drive->progress_percentage }}%</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $drive->end_date ? $drive->end_date->format('M d, Y') : 'No end date' }}
                                        </small>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('drive.preview', $drive) }}"
                                                class="btn btn-outline-primary btn-sm px-2">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if ($drive->isActive())
                                                <a href="{{ route('ngo.pledges.create', ['drive_id' => $drive->id]) }}"
                                                    class="btn btn-outline-success btn-sm px-2">
                                                    <i class="bi bi-gift"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('ngo.drives.support', $drive) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Withdraw support?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $supportedDrives->links() }}
                    </div>
                @else
                    <div class="text-center py-4 py-md-5">
                        <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No Supported Drives Yet</h5>
                        <p class="text-muted small">
                            Support a drive to show your organization's commitment.<br>
                            Donors will see that your NGO supports the drive.
                        </p>
                        <a href="{{ route('ngo.dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-search me-2"></i>Browse Drives
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
