@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join the relief coordination platform</p>

        @if (session('error'))
            <div class="alert-auth alert-auth-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
            @csrf

            <div class="mb-3">
                <label class="form-label">Account Type</label>
                <select class="form-select" name="role" id="roleSelect">
                    <option value="donor" {{ old('role', 'donor') == 'donor' ? 'selected' : '' }}>Individual Donor</option>
                    <option value="ngo" {{ old('role') == 'ngo' ? 'selected' : '' }}>NGO/ Organization</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" placeholder="Rhemjohn Dave Pitong" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                    <div class="form-text">Upload PDF or image (max 5MB). Required for NGO verification.</div>
                    @error('certificate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert-auth alert-auth-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    NGO accounts require admin verification. You'll have limited access until verified.
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="your@gmail.com" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="••••••" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="••••••" required>
            </div>

            <button type="submit" class="btn-auth-primary mb-3">Create Account</button>
        </form>

        <div class="auth-divider">
            <span>or</span>
        </div>

        <a href="{{ route('auth.google') }}" class="btn-auth-google d-flex align-items-center justify-content-center">
            <i class="bi bi-google me-2"></i>Continue with Google
        </a>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}" class="auth-link auth-link-highlight">Login here.</a>
        </div>
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const ngoFields = document.getElementById('ngoFields');

            function toggleNgoFields() {
                const isNgo = roleSelect.value === 'ngo';
                ngoFields.style.display = isNgo ? 'block' : 'none';

                document.getElementById('organization_name').required = isNgo;
                document.getElementById('certificate').required = isNgo;
            }

            roleSelect.addEventListener('change', toggleNgoFields);

            // Initial check
            toggleNgoFields();
        });
    </script>
@endsection
@endsection
