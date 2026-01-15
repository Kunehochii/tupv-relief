@extends('layouts.app')

@section('title', 'Select Your Role')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-2">Almost There!</h3>
                    <p class="text-center text-muted mb-4">
                        Welcome, {{ session('google_user')['name'] }}! Please select your role to continue.
                    </p>
                    
                    <form method="POST" action="{{ route('auth.google.role-select') }}" enctype="multipart/form-data" id="roleForm">
                        @csrf
                        
                        <div class="mb-4">
                            <div class="d-flex gap-3">
                                <div class="card flex-fill cursor-pointer" onclick="selectRole('donor')" id="donorCard">
                                    <div class="card-body text-center p-4">
                                        <input type="radio" name="role" value="donor" id="roleDonor" class="d-none" checked>
                                        <i class="bi bi-gift fs-1 text-primary mb-2"></i>
                                        <h5>Individual Donor</h5>
                                        <p class="text-muted small mb-0">Make pledges and track impact</p>
                                    </div>
                                </div>
                                <div class="card flex-fill cursor-pointer" onclick="selectRole('ngo')" id="ngoCard">
                                    <div class="card-body text-center p-4">
                                        <input type="radio" name="role" value="ngo" id="roleNgo" class="d-none">
                                        <i class="bi bi-building fs-1 text-success mb-2"></i>
                                        <h5>NGO Partner</h5>
                                        <p class="text-muted small mb-0">Partner with verified status</p>
                                    </div>
                                </div>
                            </div>
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
                                <div class="form-text">Upload PDF or image (max 5MB)</div>
                                @error('certificate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                NGO accounts require admin verification before full access.
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Complete Registration</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function selectRole(role) {
        document.getElementById('roleDonor').checked = (role === 'donor');
        document.getElementById('roleNgo').checked = (role === 'ngo');
        
        document.getElementById('donorCard').classList.toggle('border-primary', role === 'donor');
        document.getElementById('ngoCard').classList.toggle('border-success', role === 'ngo');
        
        const ngoFields = document.getElementById('ngoFields');
        ngoFields.style.display = (role === 'ngo') ? 'block' : 'none';
        
        document.getElementById('organization_name').required = (role === 'ngo');
        document.getElementById('certificate').required = (role === 'ngo');
    }
    
    // Initial state
    selectRole('donor');
</script>
<style>
    .cursor-pointer { cursor: pointer; }
    .card { transition: all 0.2s; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
</style>
@endsection
@endsection
