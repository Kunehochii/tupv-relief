<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Support NGOs - TABANG Relief Donation Platform</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --dark-blue: #000167;
            --orange: #ffae44;
            --gray-blue: #8a95b6;
            --gray: #e6e6e4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            overflow-x: hidden;
        }

        /* ===== Navigation ===== */
        .navbar-custom {
            background: var(--dark-blue);
            padding: 0.75rem 2rem;
            width: 100%;
            z-index: 100;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .navbar-brand-custom img {
            height: 40px;
        }

        .navbar-brand-custom span {
            font-weight: 800;
            font-size: 1.5rem;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .nav-link-custom {
            color: #ffffff !important;
            font-weight: 500;
            margin-left: 1.5rem;
            text-decoration: none;
            transition: opacity 0.3s;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-link-custom:hover {
            opacity: 0.8;
            color: var(--orange) !important;
        }

        .btn-signup {
            background: var(--orange);
            color: var(--dark-blue);
            font-weight: 600;
            padding: 0.4rem 1.25rem;
            border-radius: 20px;
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.9rem;
            transition: all 0.3s;
            border: 2px solid var(--orange);
        }

        .btn-signup:hover {
            background: transparent;
            color: var(--orange);
        }

        /* ===== Page Header ===== */
        .page-header {
            background: var(--dark-blue);
            padding: 4rem 0 3rem;
            text-align: center;
        }

        .page-header h1 {
            color: #ffffff;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-header .underline {
            width: 60px;
            height: 4px;
            background: var(--orange);
            margin: 0 auto 1rem;
            border-radius: 2px;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ===== NGO Cards ===== */
        .ngo-grid {
            padding: 3rem 0 5rem;
        }

        .ngo-card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #eee;
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .ngo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .ngo-card-header {
            background: linear-gradient(135deg, var(--dark-blue) 0%, #000299 100%);
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .ngo-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ffffff;
            margin-bottom: 0.75rem;
        }

        .ngo-logo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            border: 3px solid #ffffff;
        }

        .ngo-logo-placeholder i {
            font-size: 2rem;
            color: var(--dark-blue);
        }

        .ngo-card-header h5 {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 0;
        }

        .ngo-card-body {
            padding: 1.25rem 1.5rem;
        }

        .ngo-bio {
            color: var(--gray-blue);
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .ngo-stats {
            display: flex;
            justify-content: space-around;
            padding-top: 0.75rem;
            border-top: 1px solid var(--gray);
        }

        .ngo-stat {
            text-align: center;
        }

        .ngo-stat-number {
            font-weight: 700;
            color: var(--dark-blue);
            font-size: 1.1rem;
            display: block;
        }

        .ngo-stat-label {
            color: var(--gray-blue);
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .btn-view-profile {
            display: block;
            background: var(--orange);
            color: var(--dark-blue);
            font-weight: 600;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-view-profile:hover {
            background: var(--dark-blue);
            color: #ffffff;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gray);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--gray-blue);
            font-size: 1rem;
        }

        /* ===== Footer ===== */
        .footer-custom {
            background: var(--dark-blue);
            padding: 2.5rem 0 1.5rem;
            color: #ffffff;
        }

        .footer-logo {
            height: 45px;
            margin-right: 0.75rem;
        }

        .footer-text {
            font-size: 0.8rem;
            margin: 0;
            opacity: 0.7;
        }

        .footer-note {
            font-size: 0.8rem;
            line-height: 1.6;
            color: var(--orange);
            font-weight: 500;
        }

        .footer-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1.5rem 0 1rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .navbar-custom {
                padding: 0.75rem 1rem;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .ngo-grid {
                padding: 2rem 0 3rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="navbar-brand-custom">
                <img src="/logos/logo.png" alt="TABANG Logo">
                <span>TABANG</span>
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="nav-link-custom">Home</a>
                <a href="{{ route('about') }}" class="nav-link-custom">About Us</a>
                <a href="{{ route('support') }}" class="nav-link-custom">Contact</a>
                @auth
                    @php
                        $dashboardRoute = match (auth()->user()->role) {
                            'admin' => route('admin.dashboard'),
                            'ngo' => route('ngo.dashboard'),
                            default => route('donor.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="btn-signup">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn-signup">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Support Our NGO Partners</h1>
            <div class="underline"></div>
            <p>Browse verified NGO organizations working on the ground to deliver relief to communities in need. Click
                on any NGO to view their profile and supported drives.</p>
        </div>
    </section>

    <!-- NGO Grid -->
    <section class="ngo-grid">
        <div class="container">
            @if ($ngos->count() > 0)
                <div class="row g-4">
                    @foreach ($ngos as $ngo)
                        <div class="col-md-4 col-sm-6">
                            <div class="ngo-card">
                                <div class="ngo-card-header">
                                    @if ($ngo->logo)
                                        <img src="{{ $ngo->logo }}" alt="{{ $ngo->display_name }}"
                                            class="ngo-logo d-block mx-auto">
                                    @else
                                        <div class="ngo-logo-placeholder">
                                            <i class="bi bi-building"></i>
                                        </div>
                                    @endif
                                    <h5>{{ $ngo->display_name }}</h5>
                                </div>
                                <div class="ngo-card-body">
                                    <p class="ngo-bio">
                                        {{ $ngo->bio ? Str::limit($ngo->bio, 100) : 'This organization is a verified partner committed to helping communities in need.' }}
                                    </p>
                                    <div class="ngo-stats">
                                        <div class="ngo-stat">
                                            <span
                                                class="ngo-stat-number">{{ $ngo->supported_drives_count ?? 0 }}</span>
                                            <span class="ngo-stat-label">Drives</span>
                                        </div>
                                        <div class="ngo-stat">
                                            <span class="ngo-stat-number">{{ $ngo->pledges_count ?? 0 }}</span>
                                            <span class="ngo-stat-label">Pledges</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('ngo.profile.public', $ngo->id) }}" class="btn-view-profile">
                                        View Profile <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-building d-block"></i>
                    <p>No verified NGO partners at the moment. Check back soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-md-4 d-flex align-items-center mb-3 mb-md-0">
                    <img src="/logos/tupvlogo.png" alt="TUPV Logo" class="footer-logo">
                    <img src="/logos/dswdlogo.png" alt="DSWD Logo" class="footer-logo">
                </div>
                <div class="col-md-8">
                    <p class="footer-note mb-0">This website was developed as an academic project in coordination with
                        the Department of Social Welfare and Development (DSWD) to support disaster relief
                        donation and distribution processes.</p>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="text-center">
                <p class="footer-text mb-0">Copyright 2026. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
