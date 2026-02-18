@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">Forgot Password</h1>
        <p class="auth-subtitle">Enter your email and we will send a password reset link.</p>

        @if (session('status'))
            <div class="alert-auth alert-auth-info mb-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-auth-primary">Email Password Reset Link</button>
        </form>

        <div class="auth-footer mt-3">
            Remember your password? <a href="{{ route('login') }}" class="auth-link auth-link-highlight">Sign in</a>
        </div>
    </div>
@endsection
