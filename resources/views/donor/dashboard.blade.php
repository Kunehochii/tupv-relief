@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Drive Carousel --}}
        @include('partials.drive-carousel', ['drives' => $activeDrives, 'userType' => 'donor'])

        {{-- Active Drives List --}}
        <div class="mt-5">
            <h4 class="mb-3" style="color: #e51d00; font-weight: 700;">On-going Donation Drives</h4>
            <div class="row g-3">
                @forelse($activeDrives as $drive)
                    <div class="col-md-4">
                        <a href="{{ route('drive.donate', $drive) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-0"
                                style="border-radius: 12px; overflow: hidden; transition: transform 0.3s;"
                                onmouseenter="this.style.transform='translateY(-5px)'"
                                onmouseleave="this.style.transform='none'">
                                @if ($drive->cover_photo)
                                    <img src="{{ asset('storage/' . $drive->cover_photo) }}" alt="{{ $drive->name }}"
                                        style="height: 160px; object-fit: cover;">
                                @else
                                    <div
                                        style="height: 160px; background: linear-gradient(135deg, #8a95b6 0%, #000167 100%); display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-heart-fill"
                                            style="font-size: 2.5rem; color: rgba(255,255,255,0.5);"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0 fw-bold text-dark">{{ Str::limit($drive->name, 25) }}
                                        </h6>
                                        <span class="text-danger fw-bold small">{{ $drive->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress mb-2"
                                        style="height: 6px; background: #e6e6e4; border-radius: 4px;">
                                        <div class="progress-bar"
                                            style="width: {{ $drive->progress_percentage }}%; background: #e51d00; border-radius: 4px;">
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-0">{{ Str::limit($drive->description, 60) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">No active drives at the moment.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Completed Drives List --}}
        @if ($completedDrives->count() > 0)
            <div class="mt-5">
                <h4 class="mb-3" style="color: #000167; font-weight: 700;">Completed Drives</h4>
                <div class="row g-3">
                    @foreach ($completedDrives as $drive)
                        <div class="col-md-4">
                            <a href="{{ route('drive.donate', $drive) }}" class="text-decoration-none">
                                <div class="card h-100 shadow-sm border-0"
                                    style="border-radius: 12px; overflow: hidden; opacity: 0.85; transition: transform 0.3s;"
                                    onmouseenter="this.style.transform='translateY(-5px)'"
                                    onmouseleave="this.style.transform='none'">
                                    @if ($drive->cover_photo)
                                        <img src="{{ asset('storage/' . $drive->cover_photo) }}" alt="{{ $drive->name }}"
                                            style="height: 160px; object-fit: cover; filter: grayscale(30%);">
                                    @else
                                        <div
                                            style="height: 160px; background: linear-gradient(135deg, #999 0%, #555 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-check-circle-fill"
                                                style="font-size: 2.5rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0 fw-bold text-dark">
                                                {{ Str::limit($drive->name, 25) }}</h6>
                                            <span class="badge bg-success">Completed</span>
                                        </div>
                                        <div class="progress mb-2"
                                            style="height: 6px; background: #e6e6e4; border-radius: 4px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $drive->progress_percentage }}%; border-radius: 4px;">
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0">{{ Str::limit($drive->description, 60) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
