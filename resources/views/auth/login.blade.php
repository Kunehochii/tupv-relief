@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">Log In</h1>
        <p class="auth-subtitle">Access your dashboard</p>

        @if (session('error'))
            <div class="alert-auth alert-auth-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert-auth alert-auth-info mb-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="dswd@gmail.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-auth-primary mb-3">Sign In</button>
        </form>

        <div class="auth-divider">
            <span>or</span>
        </div>

        <a href="{{ route('auth.google') }}" class="btn-auth-google d-flex align-items-center justify-content-center">
            <i class="bi bi-google me-2"></i>Continue with Google
        </a>

        <div class="auth-footer">
            Don't have an account yet? <a href="{{ route('register') }}" class="auth-link auth-link-highlight">Sign up
                here.</a>
        </div>
    </div>
@endsection
