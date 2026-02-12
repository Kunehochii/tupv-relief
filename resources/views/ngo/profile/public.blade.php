@extends('layouts.app')

@section('title', ($ngo->organization_name ?? $ngo->name) . ' - NGO Profile')

@section('content')
    <div class="ngo-profile-page">
        {{-- Hero Section --}}
        <div class="profile-hero">
            <div class="container py-5">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8 text-center">
                        {{-- Logo --}}
                        <div class="mb-3">
                            @if ($ngo->logo)
                                <img src="{{ $ngo->logo }}" alt="{{ $ngo->display_name }}"
                                    class="ngo-logo-large rounded-circle border border-4 border-white shadow">
                            @else
                                <div
                                    class="ngo-logo-placeholder rounded-circle border border-4 border-white shadow d-flex align-items-center justify-content-center mx-auto">
                                    <i class="bi bi-building fs-1 text-white"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Name & Verification --}}
                        <h2 class="text-white fw-bold mb-1">{{ $ngo->display_name }}</h2>
                        <div class="mb-3">
                            <span class="badge bg-success bg-opacity-75 px-3 py-2">
                                <i class="bi bi-patch-check-fill me-1"></i>Verified Organization
                            </span>
                        </div>

                        {{-- Contact Info --}}
                        @if ($ngo->contact_numbers)
                            <p class="text-white-50 mb-1">
                                <i class="bi bi-telephone me-1"></i>{{ $ngo->contact_numbers }}
                            </p>
                        @endif
                        <p class="text-white-50 mb-3">
                            <i class="bi bi-envelope me-1"></i>{{ $ngo->email }}
                        </p>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            @if ($ngo->external_donation_url)
                                <a href="{{ route('ngo.external-link', $ngo->id) }}"
                                    class="btn btn-warning btn-lg px-4 fw-semibold" target="_blank">
                                    <i class="bi bi-heart-fill me-2"></i>Donate Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-4">
            {{-- Stats Row --}}
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="stat-card text-center p-3 rounded-3 shadow-sm">
                        <div class="stat-icon mb-2">
                            <i class="bi bi-hand-thumbs-up-fill text-primary fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $stats['drives_supported'] }}</h3>
                        <small class="text-muted">Drives Supported</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-card text-center p-3 rounded-3 shadow-sm">
                        <div class="stat-icon mb-2">
                            <i class="bi bi-box-seam-fill text-success fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $stats['total_pledges'] }}</h3>
                        <small class="text-muted">Total Pledges</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-card text-center p-3 rounded-3 shadow-sm">
                        <div class="stat-icon mb-2">
                            <i class="bi bi-people-fill text-warning fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['families_helped']) }}</h3>
                        <small class="text-muted">Families Helped</small>
                    </div>
                </div>
            </div>

            <div class="row g-3 g-lg-4">
                {{-- Main Content --}}
                <div class="col-12 col-lg-8">
                    {{-- About Section --}}
                    @if ($ngo->bio)
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                            <div class="card-body p-3 p-md-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>About Us</h5>
                                <p class="text-muted mb-0" style="white-space: pre-line; line-height: 1.7;">
                                    {{ $ngo->bio }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Supported Drives --}}
                    @if ($supportedDrives->isNotEmpty())
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                            <div class="card-body p-3 p-md-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-heart me-2 text-danger"></i>Drives We Support</h5>
                                <div class="row g-2 g-md-3">
                                    @foreach ($supportedDrives as $drive)
                                        <div class="col-12 col-sm-6">
                                            <a href="{{ route('drive.preview', $drive) }}" class="text-decoration-none">
                                                <div class="card h-100 border-0 bg-light drive-card"
                                                    style="border-radius: 10px;">
                                                    <div class="card-body p-3">
                                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">
                                                            {{ Str::limit($drive->name, 40) }}</h6>
                                                        @if ($drive->address)
                                                            <p class="small text-muted mb-2">
                                                                <i
                                                                    class="bi bi-geo-alt me-1"></i>{{ Str::limit($drive->address, 35) }}
                                                            </p>
                                                        @endif
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success"
                                                                style="width: {{ $drive->progress_percentage }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ $drive->progress_percentage }}%
                                                            funded</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-12 col-lg-4">
                    {{-- QR Payment Channels --}}
                    @if (!empty($ngo->qr_channels))
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                            <div class="card-body p-3 p-md-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-qr-code me-2 text-primary"></i>Payment Channels
                                </h5>
                                <p class="text-muted small mb-3">Scan any QR code below to donate directly.</p>
                                <div class="row g-2">
                                    @foreach ($ngo->qr_channels as $index => $qrPath)
                                        <div class="col-6">
                                            <div class="qr-image-wrapper" data-bs-toggle="modal"
                                                data-bs-target="#qrModal{{ $index }}" role="button">
                                                <img src="{{ asset('storage/' . $qrPath) }}"
                                                    alt="Payment QR {{ $index + 1 }}"
                                                    class="img-fluid rounded border w-100"
                                                    style="aspect-ratio: 1; object-fit: cover;">
                                                <div class="qr-overlay">
                                                    <i class="bi bi-zoom-in fs-4"></i>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- QR Modal --}}
                                        <div class="modal fade" id="qrModal{{ $index }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0" style="border-radius: 16px;">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h6 class="modal-title">Payment QR Code</h6>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-4">
                                                        <img src="{{ asset('storage/' . $qrPath) }}"
                                                            alt="Payment QR {{ $index + 1 }}"
                                                            class="img-fluid rounded" style="max-width: 350px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Donate CTA --}}
                    @if ($ngo->external_donation_url)
                        <div class="card border-0 shadow-sm mb-3"
                            style="border-radius: 12px; background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);">
                            <div class="card-body p-3 p-md-4 text-center">
                                <i class="bi bi-heart-fill fs-2 text-danger mb-2 d-block"></i>
                                <h6 class="fw-bold mb-2">Support {{ $ngo->display_name }}</h6>
                                <p class="text-muted small mb-3">Every donation makes a difference. Help us reach more
                                    families in need.</p>
                                <a href="{{ route('ngo.external-link', $ngo->id) }}"
                                    class="btn btn-warning w-100 fw-semibold" target="_blank">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Donate Now
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Contact Card --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-body p-3 p-md-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Contact</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-envelope text-muted me-2"></i>
                                <a href="mailto:{{ $ngo->email }}"
                                    class="text-muted text-decoration-none small">{{ $ngo->email }}</a>
                            </div>
                            @if ($ngo->contact_numbers)
                                <div class="d-flex align-items-start mb-0">
                                    <i class="bi bi-telephone text-muted me-2 mt-1"></i>
                                    <span class="text-muted small">{{ $ngo->contact_numbers }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('styles')
    <style>
        .profile-hero {
            background: linear-gradient(135deg, var(--dark-blue) 0%, #1a1a8a 50%, var(--vivid-orange) 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .ngo-logo-large {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .ngo-logo-placeholder {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-card {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .drive-card {
            transition: all 0.2s;
        }

        .drive-card:hover {
            background-color: #e9ecef !important;
            transform: translateY(-1px);
        }

        .qr-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .qr-image-wrapper:hover {
            transform: scale(1.03);
        }

        .qr-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            color: #fff;
        }

        .qr-image-wrapper:hover .qr-overlay {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .ngo-logo-large {
                width: 90px;
                height: 90px;
            }

            .ngo-logo-placeholder {
                width: 90px;
                height: 90px;
            }

            .stat-card h3 {
                font-size: 1.2rem;
            }

            .stat-card small {
                font-size: 0.65rem;
            }
        }
    </style>
@endsection
@endsection
