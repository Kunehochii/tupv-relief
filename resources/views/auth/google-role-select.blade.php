@extends('layouts.auth')

@section('title', 'Select Your Role')

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">Almost There!</h1>
        <p class="auth-subtitle">
            Welcome, {{ session('google_user')['name'] }}! Please select your role to continue.
        </p>

        <form method="POST" action="{{ route('auth.google.role-select') }}" enctype="multipart/form-data" id="roleForm">
            @csrf

            <div class="mb-4">
                <label class="form-label">I want to join as</label>
                <div class="d-flex gap-3">
                    <div class="role-card flex-fill selected" onclick="selectRole('donor')" id="donorCard">
                        <input type="radio" name="role" value="donor" id="roleDonor" class="d-none" checked>
                        <i class="bi bi-gift"></i>
                        <h6>Individual Donor</h6>
                        <p>Make pledges and track impact</p>
                    </div>
                    <div class="role-card flex-fill" onclick="selectRole('ngo')" id="ngoCard">
                        <input type="radio" name="role" value="ngo" id="roleNgo" class="d-none">
                        <i class="bi bi-building"></i>
                        <h6>NGO Partner</h6>
                        <p>Partner with verified status</p>
                    </div>
                </div>
            </div>

            <!-- NGO Fields -->
            <div id="ngoFields" style="display: none;">
                <div class="mb-3">
                    <label for="organization_name" class="form-label">Organization Name</label>
                    <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                        id="organization_name" name="organization_name" value="{{ old('organization_name') }}"
                        placeholder="Your organization name">
                    @error('organization_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="certificate" class="form-label">Certificate of Authenticity</label>
                    <input type="file" class="form-control @error('certificate') is-invalid @enderror" id="certificate"
                        name="certificate" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Upload PDF or image (max 5MB)</div>
                    @error('certificate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert-auth alert-auth-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    NGO accounts require admin verification before full access.
                </div>
            </div>

            <button type="submit" class="btn-auth-primary">Complete Registration</button>
        </form>
    </div>

@section('scripts')
    <script>
        function selectRole(role) {
            document.getElementById('roleDonor').checked = (role === 'donor');
            document.getElementById('roleNgo').checked = (role === 'ngo');

            document.getElementById('donorCard').classList.toggle('selected', role === 'donor');
            document.getElementById('ngoCard').classList.toggle('selected', role === 'ngo');

            const ngoFields = document.getElementById('ngoFields');
            ngoFields.style.display = (role === 'ngo') ? 'block' : 'none';

            document.getElementById('organization_name').required = (role === 'ngo');
            document.getElementById('certificate').required = (role === 'ngo');
        }

        // Initial state
        selectRole('donor');
    </script>
@endsection
@endsection
