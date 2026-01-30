<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Relief') }} - @yield('title', 'Authentication')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --auth-dark-blue: #000167;
            --auth-gray-blue: #8a95b6;
            --auth-gray: #e6e6e4;
            --auth-orange: #ffae44;
            --auth-red: #dd3319;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--auth-dark-blue);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            background: linear-gradient(145deg, rgba(138, 149, 182, 0.6), rgba(138, 149, 182, 0.4));
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .auth-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: #ffffff;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(255, 174, 68, 0.3);
            outline: none;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-control.is-invalid {
            border: 2px solid var(--auth-red);
        }

        .invalid-feedback {
            color: #ffae44;
            font-size: 0.8rem;
        }

        .btn-auth-primary {
            background-color: var(--auth-dark-blue);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-auth-primary:hover {
            background-color: #000180;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-auth-google {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-auth-google:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            margin: 1.25rem 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .auth-divider span {
            padding: 0 1rem;
        }

        .auth-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .auth-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .auth-link-highlight {
            color: #ffae44;
            font-weight: 600;
        }

        .auth-link-highlight:hover {
            color: #ffc670;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
        }

        .form-check-input:checked {
            background-color: var(--auth-dark-blue);
            border-color: var(--auth-dark-blue);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
        }

        .alert-auth {
            background-color: rgba(255, 174, 68, 0.2);
            border: 1px solid rgba(255, 174, 68, 0.5);
            color: #ffffff;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .alert-auth-danger {
            background-color: rgba(221, 51, 25, 0.3);
            border-color: rgba(221, 51, 25, 0.5);
        }

        .alert-auth-info {
            background-color: rgba(138, 149, 182, 0.3);
            border-color: rgba(138, 149, 182, 0.5);
        }

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }

        .forgot-password a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            text-decoration: none;
        }

        .forgot-password a:hover {
            color: #ffffff;
        }

        /* Role Selection Cards */
        .role-card {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .role-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .role-card.selected {
            border-color: var(--auth-orange);
            background: rgba(255, 174, 68, 0.15);
        }

        .role-card i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }

        .role-card h6 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .role-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            margin-bottom: 0;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        /* Logo */
        .auth-logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .auth-logo img {
            height: 50px;
        }

        .auth-logo-text {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        @yield('styles')
    </style>
</head>

<body>
    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
