@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Create an Account</h3>
                    
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">I am a...</label>
                            <div class="d-flex gap-3">
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="role" id="roleDonor" 
                                        value="donor" {{ old('role', 'donor') == 'donor' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="roleDonor">
                                        <i class="bi bi-gift me-1"></i>Individual Donor
                                    </label>
                                </div>
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="role" id="roleNgo" 
                                        value="ngo" {{ old('role') == 'ngo' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="roleNgo">
                                        <i class="bi bi-building me-1"></i>NGO Partner
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- NGO Fields -->
                        <div id="ngoFields" style="display: none;">
                            <div class="mb-3">
                                <label for="organization_name" class="form-label">Organization Name</label>
                                <input type="text" class="form-control @error('organization_name') is-invalid @enderror" 
                                    id="organization_name" name="organization_name" value="{{ old('organization_name') }}">
                                @error('organization_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="certificate" class="form-label">Certificate of Authenticity</label>
                                <input type="file" class="form-control @error('certificate') is-invalid @enderror" 
                                    id="certificate" name="certificate" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">Upload PDF or image (max 5MB). Required for NGO verification.</div>
                                @error('certificate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                NGO accounts require admin verification. You'll have limited access until verified.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number (optional)</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>
                    </form>
                    
                    <div class="text-center mb-3">
                        <span class="text-muted">or</span>
                    </div>
                    
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100">
                        <i class="bi bi-google me-2"></i>Continue with Google
                    </a>
                    
                    <hr>
                    
                    <p class="text-center mb-0">
                        Already have an account? <a href="{{ route('login') }}">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleRadios = document.querySelectorAll('input[name="role"]');
        const ngoFields = document.getElementById('ngoFields');
        
        function toggleNgoFields() {
            const isNgo = document.getElementById('roleNgo').checked;
            ngoFields.style.display = isNgo ? 'block' : 'none';
            
            // Toggle required
            document.getElementById('organization_name').required = isNgo;
            document.getElementById('certificate').required = isNgo;
        }
        
        roleRadios.forEach(radio => {
            radio.addEventListener('change', toggleNgoFields);
        });
        
        // Initial check
        toggleNgoFields();
    });
</script>
@endsection
@endsection
