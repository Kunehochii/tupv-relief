@extends('layouts.app')

@section('title', 'Manage Donation Link')

@section('content')
    <div class="container py-3 py-md-4">
        <h4 class="mb-3 mb-md-4">Manage External Donation Link</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            {{-- Stats card first on mobile for quick glanceability --}}
            <div class="col-12 col-md-4 order-md-2">
                <div class="card">
                    <div class="card-header p-2 p-md-3">
                        <h5 class="mb-0 fs-6">Link Statistics</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="text-center mb-3">
                            <h1 class="display-5 display-md-4 text-primary mb-0">{{ $linkStats['total_clicks'] }}</h1>
                            <span class="text-muted small">Total Clicks</span>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="bg-light rounded p-2 p-md-3 text-center">
                                    <h4 class="mb-0 fs-5">{{ $linkStats['clicks_this_month'] }}</h4>
                                    <small class="text-muted" style="font-size: 0.75rem;">This Month</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2 p-md-3 text-center">
                                    <h4 class="mb-0 fs-5">{{ $linkStats['clicks_this_week'] }}</h4>
                                    <small class="text-muted" style="font-size: 0.75rem;">This Week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 order-md-1">
                <div class="card">
                    <div class="card-header p-2 p-md-3">
                        <h5 class="mb-0 fs-6">Your Donation Link</h5>
                    </div>
                    <div class="card-body p-3">
                        <form method="POST" action="{{ route('ngo.donation-link.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="external_donation_url" class="form-label">External Donation URL</label>
                                <input type="url"
                                    class="form-control @error('external_donation_url') is-invalid @enderror"
                                    id="external_donation_url" name="external_donation_url"
                                    value="{{ old('external_donation_url', auth()->user()->external_donation_url) }}"
                                    placeholder="https://your-donation-page.com">
                                @error('external_donation_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the URL to your organization's donation page</div>
                            </div>

                            <div class="mb-3">
                                <label for="logo_url" class="form-label">Organization Logo URL</label>
                                <input type="url" class="form-control @error('logo_url') is-invalid @enderror"
                                    id="logo_url" name="logo_url" value="{{ old('logo_url', auth()->user()->logo_url) }}"
                                    placeholder="https://your-logo-image-url.com/logo.png">
                                @error('logo_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter a URL to your organization's logo</div>
                            </div>

                            @if (auth()->user()->logo_url)
                                <div class="mb-3">
                                    <label class="form-label">Logo Preview</label>
                                    <div class="p-3 bg-light rounded text-center">
                                        <img src="{{ auth()->user()->logo_url }}" alt="Organization Logo"
                                            style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->external_donation_url)
                                <div class="mb-3">
                                    <label class="form-label">Shareable Link</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" readonly
                                            value="{{ route('ngo.external-link', auth()->user()->id) }}" id="shareLink"
                                            style="font-size: 0.8rem;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="copyLink()">
                                            <i class="bi bi-clipboard"></i><span class="d-none d-sm-inline ms-1">Copy</span>
                                        </button>
                                    </div>
                                    <div class="form-text">Share this link to track clicks</div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary w-100 w-md-auto">
                                <i class="bi bi-save me-2"></i>Save Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        function copyLink() {
            const input = document.getElementById('shareLink');
            input.select();
            document.execCommand('copy');

            // Better mobile feedback
            const btn = event.currentTarget;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i><span class="d-none d-sm-inline ms-1">Copied!</span>';
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
