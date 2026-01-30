@extends('layouts.app')

@section('title', 'Drives We Support')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-heart-fill text-danger me-2"></i>
                Drives We Support
            </h4>
            <a href="{{ route('ngo.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if ($supportedDrives->count() > 0)
                    <div class="table-responsive">
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
                                                        <i class="bi bi-heart-break"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $supportedDrives->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">No Supported Drives Yet</h5>
                        <p class="text-muted">
                            Support a drive to show your organization's commitment to the cause.<br>
                            Donors will be able to see that your NGO supports the drive.
                        </p>
                        <a href="{{ route('ngo.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Browse Drives
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
