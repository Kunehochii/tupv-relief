<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TABANG - Relief Donation Platform</title>

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
            background-color: #ffffff;
            overflow-x: hidden;
        }

        /* ===== Navigation ===== */
        .navbar-custom {
            background: var(--dark-blue);
            padding: 0.75rem 2rem;
            position: absolute;
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
            border-radius: 999px;
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

        /* ===== Hero Section ===== */
        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/landing-bg2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 1, 103, 0.7) 0%, rgba(0, 1, 103, 0.3) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            padding-top: 120px;
            padding-bottom: 3rem;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero-subtitle {
            font-size: 2rem;
            font-weight: 600;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .hero-desc {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 500px;
            margin-bottom: 2rem;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .btn-hero-primary {
            background: var(--orange);
            color: var(--dark-blue);
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            text-decoration: none;
            border: 2px solid var(--orange);
            transition: all 0.3s;
            text-align: center;
        }

        .btn-hero-primary:hover {
            background: transparent;
            color: var(--orange);
        }

        .btn-hero-outline {
            background: #ffffff;
            color: var(--dark-blue);
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            text-decoration: none;
            border: 2px solid #ffffff;
            transition: all 0.3s;
            text-align: center;
        }

        .btn-hero-outline:hover {
            background: transparent;
            color: #ffffff;
        }

        /* ===== How It Works ===== */
        .how-it-works-section {
            padding: 5rem 0;
            background: #ffffff;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-heading h2 {
            color: var(--dark-blue);
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .section-heading .underline {
            width: 60px;
            height: 4px;
            background: var(--orange);
            margin: 0 auto;
            border-radius: 2px;
        }

        .step-card {
            text-align: center;
            padding: 1.5rem;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e51d00;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .step-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--dark-blue);
            color: var(--orange);
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .step-card h5 {
            color: var(--dark-blue);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .step-card p {
            color: var(--gray-blue);
            font-size: 0.85rem;
            line-height: 1.6;
            max-width: 280px;
            margin: 0 auto;
        }

        /* ===== Our Impact ===== */
        .impact-section {
            padding: 5rem 0;
            background: var(--dark-blue);
        }

        .impact-section .section-heading h2 {
            color: #ffffff;
        }

        .impact-section .section-heading .underline {
            background: var(--orange);
        }

        .impact-section .section-heading p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
        }

        .impact-card {
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.2);
        }

        .impact-card:hover {
            transform: translateY(-5px);
        }

        .impact-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--orange);
            color: var(--dark-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .impact-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--orange);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .impact-label {
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ===== Active Donation Drives ===== */
        .drives-section {
            padding: 5rem 0;
            background: #efefef;
        }

        .drives-section .section-heading .underline {
            background: #e51d00;
        }

        .drives-section .section-heading p {
            color: var(--dark-blue) !important;
        }

        .drive-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: none;
            transition: transform 0.3s;
            border: 1.5px solid rgba(0, 0, 0, 0.45);
            background: #f8f8f8;
        }

        .drive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .drive-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        }

        .drive-card-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--gray-blue) 0%, var(--dark-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drive-card-placeholder i {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .drive-card-body {
            padding: 0.75rem 0.75rem 0.9rem;
            background: #f8f8f8;
        }

        .drive-card-title {
            font-weight: 700;
            font-size: 2rem;
            color: var(--dark-blue);
            line-height: 1.05;
            margin-bottom: 0.45rem;
        }

        .drive-progress-bar {
            height: 10px;
            background: #f1dddd;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .drive-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #e51d00 0%, #e54a3a 100%);
            border-radius: 999px;
            transition: width 0.5s ease;
        }

        .drive-card-info {
            font-size: 0.85rem;
            color: #6d6d73;
        }

        .drive-progress-percent {
            font-size: 1.2rem;
            color: #6d6d73;
            line-height: 1;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .drive-card-title {
                font-size: 1.5rem;
            }

            .drive-progress-percent {
                font-size: 1rem;
            }
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
            color: #ffffff;
            opacity: 1;
        }

        .footer-note {
            font-size: 0.8rem;
            line-height: 1.6;
            color: #ffffff;
            font-weight: 500;
        }

        .footer-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1.5rem 0 1rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.3rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .impact-card {
                margin-bottom: 1rem;
            }

            .navbar-custom {
                padding: 0.75rem 1rem;
            }

            .section-heading h2 {
                font-size: 1.6rem;
            }

            .impact-number {
                font-size: 2rem;
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row">
                <div class="col-lg-7">
                    <h1 class="hero-title">TABANG</h1>
                    <h2 class="hero-subtitle">Make a Difference<br>Today</h2>
                    <p class="hero-desc">
                        Join our mission to create a positive change in communities
                        and worldwide. Donate and pledge, and track your
                        contributions and impact of what you gave.
                    </p>
                    <div class="hero-buttons">
                        @auth
                            @php
                                $heroDashboard = match (auth()->user()->role) {
                                    'admin' => route('admin.dashboard'),
                                    'ngo' => route('ngo.dashboard'),
                                    default => route('donor.dashboard'),
                                };
                            @endphp
                            <a href="{{ $heroDashboard }}" class="btn-hero-primary">Go to Dashboard</a>
                            @if (auth()->user()->isDonor())
                                <a href="{{ route('donor.pledges.create') }}" class="btn-hero-primary">Donate Now</a>
                            @elseif (auth()->user()->isNgo())
                                <a href="{{ route('ngo.supports.index') }}" class="btn-hero-primary">NGO Support Drive</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-primary">Donate Now</a>
                            <a href="{{ route('login') }}" class="btn-hero-outline">Sign In</a>
                            <a href="{{ route('support') }}" class="btn-hero-primary">NGO Support Drive</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works-section">
        <div class="container">
            <div class="section-heading">
                <h2>How It Works</h2>
                <div class="underline"></div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon"><i class="bi bi-search"></i></div>
                        <h5>Browse Drive</h5>
                        <p>Explore various donation drives and find causes that resonate with you</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon"><i class="bi bi-globe2"></i></div>
                        <h5>Make Your Contribution</h5>
                        <p>Support the causes you care about with secure and transparent donations</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon"><i class="bi bi-people-fill"></i></div>
                        <h5>Track Impact</h5>
                        <p>See the impact of your contributions and how they help communities</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Impact -->
    <section class="impact-section">
        <div class="container">
            <div class="section-heading">
                <h2>Our Impact</h2>
                <div class="underline"></div>
                <p class="mt-2">Together, we're creating meaningful change across communities</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="impact-card card-green">
                        <div class="impact-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <div class="impact-number">{{ number_format($stats['drives_created']) }}</div>
                        <div class="impact-label">Total drives created</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="impact-card card-blue">
                        <div class="impact-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="impact-number">
                            {{ number_format($stats['families_helped'] > 0 ? $stats['families_helped'] : $stats['pledges_verified']) }}
                        </div>
                        <div class="impact-label">Family helped</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="impact-card card-teal">
                        <div class="impact-icon"><i class="bi bi-globe2"></i></div>
                        <div class="impact-number">{{ number_format($stats['pledges_verified']) }}</div>
                        <div class="impact-label">Active donors</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Active Donation Drives -->
    <section class="drives-section">
        <div class="container">
            <div class="section-heading">
                <h2>Active Donation Drives</h2>
                <div class="underline"></div>
                <p class="mt-2" style="color: var(--gray-blue);">Support ongoing drives and help communities in need
                </p>
            </div>
            <div class="row g-4">
                @forelse($drives as $drive)
                    <div class="col-md-4">
                        <a href="{{ route('drive.donate', $drive) }}" class="text-decoration-none">
                            <div class="drive-card">
                                @if ($drive->cover_photo)
                                    <img src="{{ asset('storage/' . $drive->cover_photo) }}"
                                        alt="{{ $drive->name }}" class="drive-card-img">
                                @else
                                    <div class="drive-card-placeholder">
                                        <i class="bi bi-heart-fill"></i>
                                    </div>
                                @endif
                                <div class="drive-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="drive-card-title mb-0">{{ Str::limit($drive->name, 25) }}</h5>
                                        <span class="drive-progress-percent">{{ $drive->progress_percentage }}%</span>
                                    </div>
                                    <div class="drive-progress-bar">
                                        <div class="drive-progress-fill"
                                            style="width: {{ $drive->progress_percentage }}%"></div>
                                    </div>
                                    <p class="drive-card-info mb-0">{{ Str::limit($drive->description, 30) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-heart" style="font-size: 3rem; color: var(--gray);"></i>
                        <p class="text-muted mt-2">No active donation drives at the moment. Check back soon!</p>
                    </div>
                @endforelse
            </div>
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
