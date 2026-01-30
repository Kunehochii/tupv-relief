@extends('layouts.app')

@section('title', 'NGO Dashboard')

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

        {{-- Verification Banner --}}
        @if (auth()->user()->isPending())
            <div class="verification-banner rounded p-3 mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-hourglass-split fs-4 me-3 text-warning"></i>
                    <div>
                        <h6 class="mb-0">Account Pending Verification</h6>
                        <small class="text-muted">Your certificate is being reviewed. Support & Pledge features are limited
                            until verification.</small>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->isRejected())
            <div class="alert alert-danger mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-x-circle fs-4 me-3"></i>
                    <div>
                        <h6 class="mb-0">Verification Rejected</h6>
                        <p class="mb-0"><strong>Reason:</strong> {{ auth()->user()->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Drive Carousel --}}
        @include('partials.drive-carousel', [
            'drives' => $activeDrives,
            'userType' => 'ngo',
            'supportedDriveIds' => $supportedDriveIds ?? [],
        ])
    </div>

    <style>
        .verification-banner {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border: 1px solid #ffc107;
        }
    </style>
@endsection
