@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
            <h4 class="mb-0">My Profile</h4>
            @if (auth()->user()->isVerified())
                <a href="{{ route('ngo.profile.public', auth()->user()->id) }}" class="btn btn-outline-primary btn-sm"
                    target="_blank">
                    <i class="bi bi-eye me-1"></i>View Public Page
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('ngo.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3 g-md-4">
                {{-- Left Column: Organization Info --}}
                <div class="col-12 col-lg-8">
                    {{-- Basic Info Card --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 px-3 px-md-4">
                            <h5 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Organization Details</h5>
                        </div>
                        <div class="card-body p-3 px-md-4">
                            <div class="mb-3">
                                <label for="organization_name" class="form-label fw-semibold">Organization Name</label>
                                <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                                    id="organization_name" name="organization_name"
                                    value="{{ old('organization_name', auth()->user()->organization_name) }}"
                                    placeholder="Your organization's name">
                                @error('organization_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label fw-semibold">Bio / About</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4"
                                    placeholder="Tell donors about your organization, mission, and impact...">{{ old('bio', auth()->user()->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Max 2000 characters. This will appear on your public profile.</div>
                            </div>

                            <div class="mb-3">
                                <label for="contact_numbers" class="form-label fw-semibold">Contact Numbers</label>
                                <input type="text" class="form-control @error('contact_numbers') is-invalid @enderror"
                                    id="contact_numbers" name="contact_numbers"
                                    value="{{ old('contact_numbers', auth()->user()->contact_numbers) }}"
                                    placeholder="e.g. +63 912 345 6789, +63 2 1234 5678">
                                @error('contact_numbers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Separate multiple numbers with commas.</div>
                            </div>

                            <div class="mb-0">
                                <label for="external_donation_url" class="form-label fw-semibold">External Donation
                                    URL</label>
                                <input type="url"
                                    class="form-control @error('external_donation_url') is-invalid @enderror"
                                    id="external_donation_url" name="external_donation_url"
                                    value="{{ old('external_donation_url', auth()->user()->external_donation_url) }}"
                                    placeholder="https://your-donation-page.com">
                                @error('external_donation_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Link to your organization's donation or payment page.</div>
                            </div>
                        </div>
                    </div>

                    {{-- Logo Card --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 px-3 px-md-4">
                            <h5 class="mb-0"><i class="bi bi-image me-2 text-primary"></i>Organization Logo</h5>
                        </div>
                        <div class="card-body p-3 px-md-4">
                            @if (auth()->user()->logo)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Current Logo</label>
                                    <div class="p-3 bg-light rounded text-center">
                                        <img src="{{ auth()->user()->logo }}" alt="Organization Logo"
                                            style="max-height: 100px; max-width: 100%; object-fit: contain;"
                                            class="rounded">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="logo_upload" class="form-label fw-semibold">Upload Logo</label>
                                <input type="file" class="form-control @error('logo_upload') is-invalid @enderror"
                                    id="logo_upload" name="logo_upload" accept="image/*">
                                @error('logo_upload')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">JPG, PNG, GIF, or WebP. Max 2MB.</div>
                            </div>

                            <div class="mb-0">
                                <label for="logo_url" class="form-label fw-semibold">Or Logo URL</label>
                                <input type="url" class="form-control @error('logo_url') is-invalid @enderror"
                                    id="logo_url" name="logo_url" value="{{ old('logo_url', auth()->user()->logo_url) }}"
                                    placeholder="https://your-logo-image-url.com/logo.png">
                                @error('logo_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Alternatively, provide a URL to your logo image.</div>
                            </div>
                        </div>
                    </div>

                    {{-- QR Channels Card --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 px-3 px-md-4">
                            <h5 class="mb-0"><i class="bi bi-qr-code me-2 text-primary"></i>Payment QR Codes</h5>
                        </div>
                        <div class="card-body p-3 px-md-4">
                            <p class="text-muted small mb-3">Upload QR codes for your payment channels (GCash, Maya, bank
                                transfers, etc.) so donors can easily send contributions.</p>

                            @if (!empty(auth()->user()->qr_channels))
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Current QR Codes</label>
                                    <div class="row g-2">
                                        @foreach (auth()->user()->qr_channels as $index => $qrPath)
                                            <div class="col-6 col-sm-4 col-md-3">
                                                <div class="position-relative qr-card">
                                                    <img src="{{ asset('storage/' . $qrPath) }}"
                                                        alt="QR Code {{ $index + 1 }}"
                                                        class="img-fluid rounded border"
                                                        style="width: 100%; aspect-ratio: 1; object-fit: cover;">
                                                    <div class="form-check position-absolute top-0 end-0 m-1">
                                                        <input class="form-check-input bg-danger border-danger"
                                                            type="checkbox" name="remove_qr[]"
                                                            value="{{ $qrPath }}"
                                                            id="removeQr{{ $index }}">
                                                        <label class="form-check-label visually-hidden"
                                                            for="removeQr{{ $index }}">Remove</label>
                                                    </div>
                                                    <small class="text-muted d-block text-center mt-1"
                                                        style="font-size: 0.7rem;">Check to remove</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-0">
                                <label for="qr_uploads" class="form-label fw-semibold">Upload New QR Codes</label>
                                <input type="file"
                                    class="form-control @error('qr_uploads') is-invalid @enderror @error('qr_uploads.*') is-invalid @enderror"
                                    id="qr_uploads" name="qr_uploads[]" accept="image/*" multiple>
                                @error('qr_uploads')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('qr_uploads.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Select multiple images at once. JPG, PNG, GIF, or WebP. Max 2MB
                                    each, up to 5 images.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Stats & Shareable Link --}}
                <div class="col-12 col-lg-4">
                    {{-- Profile Preview Card --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-body p-3 text-center">
                            @if (auth()->user()->logo)
                                <img src="{{ auth()->user()->logo }}" alt="Logo" class="rounded-circle mb-2 border"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-building fs-2 text-primary"></i>
                                </div>
                            @endif
                            <h6 class="mb-1">{{ auth()->user()->display_name }}</h6>
                            <span
                                class="badge bg-{{ auth()->user()->isVerified() ? 'success' : (auth()->user()->isPending() ? 'warning' : 'danger') }} mb-2">
                                {{ ucfirst(auth()->user()->verification_status) }}
                            </span>
                            <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    {{-- Link Statistics --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                            <h6 class="mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Link Statistics</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-center mb-3">
                                <h2 class="text-primary mb-0">{{ $linkStats['total_clicks'] }}</h2>
                                <span class="text-muted small">Total Clicks</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 text-center">
                                        <h5 class="mb-0">{{ $linkStats['clicks_this_month'] }}</h5>
                                        <small class="text-muted" style="font-size: 0.7rem;">This Month</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 text-center">
                                        <h5 class="mb-0">{{ $linkStats['clicks_this_week'] }}</h5>
                                        <small class="text-muted" style="font-size: 0.7rem;">This Week</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shareable Link --}}
                    @if (auth()->user()->isVerified())
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                                <h6 class="mb-0"><i class="bi bi-link-45deg me-2 text-primary"></i>Shareable Links</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted">Public Profile</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" readonly
                                            value="{{ route('ngo.profile.public', auth()->user()->id) }}"
                                            id="profileLink" style="font-size: 0.75rem;">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="copyToClipboard('profileLink', this)">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>

                                @if (auth()->user()->external_donation_url)
                                    <div class="mb-0">
                                        <label class="form-label small fw-semibold text-muted">Tracked Donation
                                            Link</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm" readonly
                                                value="{{ route('ngo.external-link', auth()->user()->id) }}"
                                                id="donationLink" style="font-size: 0.75rem;">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="copyToClipboard('donationLink', this)">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                        <div class="form-text" style="font-size: 0.7rem;">Share this to track clicks</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Save Button --}}
            <div class="row mt-3">
                <div class="col-12 col-lg-8">
                    <button type="submit" class="btn btn-primary w-100 w-md-auto py-2">
                        <i class="bi bi-save me-2"></i>Save Profile
                    </button>
                </div>
            </div>
        </form>
    </div>

@section('styles')
    <style>
        .qr-card {
            transition: transform 0.2s;
        }

        .qr-card:hover {
            transform: scale(1.02);
        }
    </style>
@endsection

@section('scripts')
    <script>
        function copyToClipboard(inputId, btn) {
            const input = document.getElementById(inputId);
            input.select();
            document.execCommand('copy');

            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i>';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-secondary');
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        }
    </script>
@endsection
@endsection
