@extends('layouts.app')

@section('title', $drive->name . (auth()->check() && auth()->user()->isNgo() ? '' : ' - Donate'))

@section('content')
    <div class="container py-5">
        <div class="row @if(auth()->check() && auth()->user()->isNgo()) justify-content-center @endif">
            <!-- Left Side: Drive Details -->
            <div class="@if(auth()->check() && auth()->user()->isNgo()) col-lg-8 text-center @else col-lg-5 @endif mb-4">
                <h2 class="drive-title mb-3">{{ $drive->name }}</h2>

                @if ($drive->cover_photo)
                    <img src="{{ asset('storage/' . $drive->cover_photo) }}" alt="{{ $drive->name }}"
                        class="img-fluid rounded mb-4 drive-image">
                @else
                    <img src="https://placehold.co/800x600?text={{ urlencode($drive->name) }}" alt="{{ $drive->name }}"
                        class="img-fluid rounded mb-4 drive-image">
                @endif

                <div class="drive-details">
                    @if ($drive->families_affected)
                        <p class="detail-item">
                            <strong>Affected families:</strong> {{ number_format($drive->families_affected) }}
                        </p>
                    @endif

                    <p class="detail-item">
                        <strong>Target:</strong> {{ number_format($drive->total_items_needed) }} items
                    </p>

                    <p class="detail-item">
                        <strong>Progress:</strong> {{ $drive->progress_percentage }}%
                    </p>

                    @if ($drive->address)
                        <p class="detail-item">
                            <strong>Location:</strong> {{ $drive->address }}
                        </p>
                    @endif

                    @if ($drive->end_date)
                        <p class="detail-item">
                            <strong>Ends:</strong> {{ $drive->end_date->format('M d, Y') }}
                        </p>
                    @endif

                    @if ($drive->description)
                        <p class="detail-item mt-3">{{ $drive->description }}</p>
                    @endif
                </div>

                <!-- Pledge Button -->
                <div class="mt-4">
                    @auth
                        @if (auth()->user()->role !== 'admin')
                            <a href="{{ route(auth()->user()->role . '.pledges.create', ['drive_id' => $drive->id]) }}"
                                class="btn-pledge-custom">
                                <i class="bi bi-heart-fill me-2"></i>PLEDGE
                            </a>
                        @else
                            <a href="{{ route('admin.drives.show', $drive) }}" class="btn-pledge-custom">
                                <i class="bi bi-gear-fill me-2"></i>MANAGE DRIVE
                            </a>
                        @endif

                        @if (auth()->user()->isNgo() && auth()->user()->isVerifiedNgo())
                            <form action="{{ route('ngo.drives.support', $drive) }}" method="POST" class="mt-3 @if(auth()->user()->isNgo()) d-inline-block @endif">
                                @csrf
                                @if ($isSupporting ?? false)
                                    <button type="submit" class="btn-support-custom btn-supporting-custom">
                                        <i class="bi bi-heart-fill me-2"></i>SUPPORTING THIS DRIVE
                                    </button>
                                @else
                                    <button type="submit" class="btn-support-custom">
                                        <i class="bi bi-heart me-2"></i>SUPPORT THIS DRIVE
                                    </button>
                                @endif
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-pledge-custom">
                            <i class="bi bi-box-arrow-in-right me-2"></i>LOGIN TO PLEDGE
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Side: Donate Section with NGO Logos -->
            @if(!auth()->check() || !auth()->user()->isNgo())
            <div class="col-lg-7">
                <h2 class="donate-title mb-4">DONATE</h2>

                @if ($supportingNgos->count() > 0)
                    <div class="row g-4">
                        @foreach ($supportingNgos as $ngo)
                            <div class="col-md-4 col-6">
                                <a href="{{ route('ngo.profile.public', $ngo->id) }}" class="ngo-logo-card d-block">
                                    <div class="logo-wrapper">
                                        <img src="{{ $ngo->logo ?? 'https://placehold.co/800x800?text=' . urlencode(Str::limit($ngo->organization_name ?? $ngo->name, 10)) }}"
                                            alt="{{ $ngo->organization_name ?? $ngo->name }}" class="ngo-logo">
                                    </div>
                                    <p class="ngo-name text-center mt-2 mb-0">
                                        {{ $ngo->organization_name ?? $ngo->name }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-ngos-message">
                        <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">No NGO partners are currently supporting this drive.</p>
                        <p class="text-muted">You can still make a pledge directly to help!</p>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <style>
        .drive-title {
            font-weight: 700;
            color: #333;
            font-size: 1.75rem;
        }

        .drive-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .drive-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .detail-item {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .detail-item strong {
            color: var(--dark-blue);
        }

        .btn-pledge-custom {
            display: inline-block;
            background: var(--dark-blue);
            color: #ffffff !important;
            padding: 1rem 2.5rem;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-pledge-custom:hover {
            background: #000050;
            transform: translateY(-2px);
        }

        .donate-title {
            color: var(--vivid-red);
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: 2px;
        }

        .ngo-logo-card {
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }

        .ngo-logo-card:hover {
            transform: translateY(-5px);
        }

        .logo-wrapper {
            background: #ffffff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .ngo-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .ngo-name {
            font-size: 0.85rem;
            color: var(--gray-blue);
            font-weight: 500;
        }

        .no-ngos-message {
            text-align: center;
            padding: 3rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .btn-support-custom {
            display: inline-block;
            background: var(--dark-blue);
            color: #ffffff;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid var(--dark-blue);
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .btn-support-custom:hover {
            background: #000050;
            border-color: #000050;
        }

        .btn-supporting-custom {
            background: #e8f5e9;
            color: #2e7d32;
            border: 2px solid #2e7d32;
        }

        .btn-supporting-custom:hover {
            background: #ffebee;
            color: #c62828;
            border-color: #c62828;
        }

        @media (max-width: 768px) {
            .donate-title {
                font-size: 2rem;
            }

            .drive-title {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection
