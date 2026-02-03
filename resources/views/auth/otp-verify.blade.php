@extends('layouts.auth')

@section('title', 'Verify Your Email')

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">Verify Your Email</h1>
        <p class="auth-subtitle">
            Enter the 6-digit code sent to <strong>{{ auth()->user()->email }}</strong>
        </p>

        @if (session('success'))
            <div class="alert-auth alert-auth-success mb-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert-auth alert-auth-warning mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-auth alert-auth-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf
            <div class="mb-4">
                <label for="otp" class="form-label">Verification Code</label>
                <input type="text" name="otp" id="otp"
                    class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" placeholder="000000"
                    maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" required autofocus>
                @error('otp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text text-center">This code will expire in 10 minutes.</div>
            </div>

            <button type="submit" class="btn-auth-primary mb-3">
                <i class="bi bi-shield-check me-2"></i>Verify
            </button>
        </form>

        <div class="auth-divider">
            <span>didn't receive the code?</span>
        </div>

        <form method="POST" action="{{ route('otp.send') }}">
            @csrf
            <button type="submit" class="btn-auth-secondary mb-3">
                <i class="bi bi-arrow-clockwise me-2"></i>Resend Verification Code
            </button>
        </form>

        <div class="auth-footer">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="auth-link">
                    <i class="bi bi-box-arrow-left me-1"></i>Sign out and use a different account
                </button>
            </form>
        </div>
    </div>

    <style>
        #otp {
            font-size: 1.5rem;
            letter-spacing: 0.5rem;
            font-weight: 600;
        }

        .btn-auth-secondary {
            display: block;
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: transparent;
            border: 2px solid var(--gray-blue);
            color: var(--gray-blue);
            font-weight: 600;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-auth-secondary:hover {
            background: var(--gray-blue);
            color: #ffffff;
        }

        .auth-footer button.auth-link {
            background: none;
            border: none;
            cursor: pointer;
            font-size: inherit;
        }
    </style>
@endsection
