@extends('layouts.app')

@section('title', 'Manage Donation Link')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Manage External Donation Link</h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Your Donation Link</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('ngo.donation-link.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="external_donation_url" class="form-label">External Donation URL</label>
                            <input type="url" class="form-control @error('external_donation_url') is-invalid @enderror" 
                                id="external_donation_url" name="external_donation_url" 
                                value="{{ old('external_donation_url', auth()->user()->external_donation_url) }}"
                                placeholder="https://your-donation-page.com">
                            @error('external_donation_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Enter the URL to your organization's donation page</div>
                        </div>
                        
                        @if(auth()->user()->external_donation_url)
                            <div class="mb-3">
                                <label class="form-label">Shareable Link</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly 
                                        value="{{ route('ngo.external-link', auth()->user()->id) }}" id="shareLink">
                                    <button type="button" class="btn btn-outline-secondary" onclick="copyLink()">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </div>
                                <div class="form-text">Share this link to track clicks</div>
                            </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Link Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="display-4 text-primary">{{ $linkStats['total_clicks'] }}</h1>
                        <span class="text-muted">Total Clicks</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center">
                                <h4 class="mb-0">{{ $linkStats['clicks_this_month'] }}</h4>
                                <small class="text-muted">This Month</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center">
                                <h4 class="mb-0">{{ $linkStats['clicks_this_week'] }}</h4>
                                <small class="text-muted">This Week</small>
                            </div>
                        </div>
                    </div>
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
        alert('Link copied to clipboard!');
    }
</script>
@endsection
@endsection
