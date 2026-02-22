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

        {{-- Active Donation Drives --}}
        <section class="drives-section">
            <div class="section-heading">
                <h2>Active Donation Drives</h2>
                <div class="underline"></div>
                <p class="mt-2 section-subtitle">Support ongoing drives and help communities in need</p>
            </div>
            <div class="row g-4">
                @forelse($activeDrives as $drive)
                    <div class="col-md-4">
                        <a href="{{ route('drive.donate', $drive) }}" class="text-decoration-none">
                            <div class="drive-card">
                                @if ($drive->cover_photo)
                                    <img src="{{ asset('storage/' . $drive->cover_photo) }}"
                                        alt="{{ $drive->name }}" class="drive-card-img">
                                @else
                                    <div class="drive-card-placeholder">
                                        <i class="bi bi-heart-fill"></i>
                                    </div>
                                @endif
                                <div class="drive-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="drive-card-title mb-0">{{ Str::limit($drive->name, 25) }}</h5>
                                        <span class="drive-progress-percent">{{ $drive->progress_percentage }}%</span>
                                    </div>
                                    <div class="drive-progress-bar">
                                        <div class="drive-progress-fill"
                                            style="width: {{ $drive->progress_percentage }}%"></div>
                                    </div>
                                    <p class="drive-card-info mb-0">{{ Str::limit($drive->description, 30) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-heart" style="font-size: 3rem; color: var(--gray);"></i>
                        <p class="text-muted mt-2">No active donation drives at the moment. Check back soon!</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <style>
        .drives-section {
            padding: 1rem 0;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-heading h2 {
            color: var(--dark-blue);
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .section-heading .underline {
            width: 60px;
            height: 4px;
            background: #e51d00;
            margin: 0 auto;
            border-radius: 2px;
        }

        .section-subtitle {
            color: var(--gray-blue);
        }

        .drive-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: none;
            transition: transform 0.3s;
            border: 1.5px solid rgba(0, 0, 0, 0.45);
            background: #f8f8f8;
        }

        .drive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .drive-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        }

        .drive-card-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--gray-blue) 0%, var(--dark-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drive-card-placeholder i {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .drive-card-body {
            padding: 0.75rem 0.75rem 0.9rem;
            background: #f8f8f8;
        }

        .drive-card-title {
            font-weight: 700;
            font-size: 2rem;
            color: var(--dark-blue);
            line-height: 1.05;
            margin-bottom: 0.45rem;
        }

        .drive-progress-bar {
            height: 10px;
            background: #f1dddd;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .drive-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #e51d00 0%, #e54a3a 100%);
            border-radius: 999px;
            transition: width 0.5s ease;
        }

        .drive-card-info {
            font-size: 0.85rem;
            color: #6d6d73;
        }

        .drive-progress-percent {
            font-size: 1.2rem;
            color: #6d6d73;
            line-height: 1;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .drive-card-title {
                font-size: 1.5rem;
            }

            .drive-progress-percent {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .section-heading h2 {
                font-size: 1.6rem;
            }
        }
    </style>
@endsection
