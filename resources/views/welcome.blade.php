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
            --red: #dd3319;
            --vivid-red: #e51d00;
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

        /* Navigation */
        .navbar-custom {
            background: #ea4f2d;
            padding: 1rem 2rem;
            position: absolute;
            width: 100%;
            z-index: 100;
        }

        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--dark-blue) !important;
            text-decoration: none;
        }

        .nav-link-custom {
            color: #ffffff !important;
            font-weight: 500;
            margin-left: 1.5rem;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .nav-link-custom:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 100vh;
            background: #ffffff;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/landing-bg.png');
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            padding-top: calc(100vh - 600px);
            min-height: 100vh;
            display: flex;
            align-items: flex-end;
            padding-bottom: 3rem;
        }

        .hero-content .row {
            width: 100%;
        }

        .hero-logo {
            max-width: 400px;
            margin-bottom: 2rem;
        }

        .hero-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            max-width: 420px;
            margin-left: auto;
            margin-top: 0;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid var(--gray-blue);
            color: var(--gray-blue);
            padding: 1.25rem 3rem;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-outline-custom:hover {
            background: var(--gray-blue);
            color: #ffffff;
        }

        .btn-primary-custom {
            background: var(--dark-blue);
            border: 2px solid var(--dark-blue);
            color: #ffffff;
            padding: 1.25rem 3rem;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary-custom:hover {
            background: #000050;
            color: #ffffff;
        }

        /* Section Titles */
        .section-title {
            color: var(--vivid-red);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }

        /* Drives Section */
        .drives-section {
            padding: 2rem 0 4rem 0;
            background: #ffffff;
        }

        .drive-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .drive-card:hover {
            transform: translateY(-5px);
        }

        .drive-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .drive-card-placeholder {
            width: 100%;
            height: 180px;
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
            padding: 1rem;
            background: #ffffff;
        }

        .drive-card-title {
            font-weight: 700;
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .drive-progress-bar {
            height: 8px;
            background: var(--gray);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .drive-progress-fill {
            height: 100%;
            background: var(--vivid-red);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .drive-card-info {
            font-size: 0.75rem;
            color: var(--gray-blue);
        }

        .drive-progress-percent {
            font-size: 0.75rem;
            color: var(--vivid-red);
            font-weight: 600;
        }

        /* Accomplishments Section */
        .accomplishments-section {
            padding: 4rem 0;
            background: #ffffff;
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 800;
            color: var(--dark-blue);
            line-height: 1;
        }

        .stat-label {
            color: var(--gray-blue);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Quote Section */
        .quote-section {
            padding: 3rem 0;
            background: #ffffff;
        }

        .quote-card {
            background: var(--vivid-red);
            border-radius: 20px;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            overflow: hidden;
        }

        .quote-image {
            width: 200px;
            height: 150px;
            border-radius: 15px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .quote-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quote-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #87CEEB 0%, #90EE90 50%, #98FB98 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .quote-placeholder::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, #228B22 0%, transparent 100%);
            border-radius: 0 0 15px 15px;
        }

        .quote-text {
            color: #ffffff;
            font-style: italic;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Footer */
        .footer-custom {
            background: var(--vivid-red);
            padding: 1.5rem 0;
            color: #ffffff;
        }

        .footer-logo {
            height: 40px;
            margin-right: 1rem;
        }

        .footer-text {
            font-size: 0.85rem;
            margin: 0;
        }

        .footer-note {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .scroll-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gray);
            cursor: pointer;
            transition: background 0.3s;
        }

        .scroll-dot.active {
            background: var(--vivid-red);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-buttons {
                margin-right: auto;
                margin-left: auto;
                margin-top: 40px;
            }

            .hero-logo {
                max-width: 200px;
            }

            .quote-card {
                flex-direction: column;
                text-align: center;
            }

            .quote-image {
                width: 100%;
                max-width: 300px;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .navbar-custom {
                padding: 1rem;
            }
        }

        /* Carousel navigation arrows */
        .carousel-nav {
            position: absolute;
            right: -40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .carousel-arrow {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--gray);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s;
        }

        .carousel-arrow:hover {
            background: var(--vivid-red);
            color: #ffffff;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="navbar-brand-custom">TABANG</a>
            <div class="d-flex align-items-center">
                <a href="{{ route('about') }}" class="nav-link-custom">About us</a>
                <a href="{{ route('login') }}" class="nav-link-custom">Sign In</a>
                <a href="{{ route('register') }}" class="nav-link-custom">SIGN UP</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="container hero-content">
            <div class="row align-items-end justify-content-between">
                <div class="col-lg-5">
                    <img src="/logos/tabang.png" alt="TABANG Logo" class="hero-logo">
                </div>
                <div class="col-lg-6">
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn-outline-custom">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary-custom">Donate Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- On-going Donation Drives -->
    <section class="drives-section">
        <div class="container">
            <h2 class="section-title">On-going donation drives</h2>
            <div class="position-relative">
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
                                            <span
                                                class="drive-progress-percent">{{ $drive->progress_percentage }}%</span>
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
                        <div class="col-md-4">
                            <div class="drive-card">
                                <div class="drive-card-placeholder">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                                <div class="drive-card-body">
                                    <h5 class="drive-card-title">Manapla Flooding</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="drive-progress-bar flex-grow-1 me-2">
                                            <div class="drive-progress-fill" style="width: 55%"></div>
                                        </div>
                                        <span class="drive-progress-percent">55%</span>
                                    </div>
                                    <p class="drive-card-info mb-0">Short information</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="drive-card">
                                <div class="drive-card-placeholder">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                                <div class="drive-card-body">
                                    <h5 class="drive-card-title">Manapla Flooding</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="drive-progress-bar flex-grow-1 me-2">
                                            <div class="drive-progress-fill" style="width: 55%"></div>
                                        </div>
                                        <span class="drive-progress-percent">55%</span>
                                    </div>
                                    <p class="drive-card-info mb-0">Short information</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="drive-card">
                                <div class="drive-card-placeholder">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                                <div class="drive-card-body">
                                    <h5 class="drive-card-title">Manapla Flooding</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="drive-progress-bar flex-grow-1 me-2">
                                            <div class="drive-progress-fill" style="width: 55%"></div>
                                        </div>
                                        <span class="drive-progress-percent">55%</span>
                                    </div>
                                    <p class="drive-card-info mb-0">Short information</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Accomplishments -->
    <section class="accomplishments-section">
        <div class="container">
            <h2 class="section-title">Accomplishments</h2>
            <div class="row">
                <div class="col-md-4 text-center text-md-start mb-4 mb-md-0">
                    <div class="stat-number">{{ number_format($stats['relief_distributed']) }}</div>
                    <p class="stat-label">relief packs and hot meals distributed</p>
                </div>
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="stat-number">{{ number_format($stats['drives_created']) }}</div>
                    <p class="stat-label">donation drives created</p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="stat-number">{{ number_format($stats['pledges_verified']) }}</div>
                    <p class="stat-label">total pledges made and verified</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quote Section -->
    <section class="quote-section">
        <div class="container">
            <div class="quote-card">
                <div class="quote-image">
                    @if ($featuredDrive && $featuredDrive->cover_photo)
                        <img src="{{ asset('storage/' . $featuredDrive->cover_photo) }}" alt="Featured Drive">
                    @else
                        <div class="quote-placeholder">
                            <i class="bi bi-image"
                                style="font-size: 3rem; color: rgba(255,255,255,0.5); z-index: 1;"></i>
                        </div>
                    @endif
                </div>
                <div class="quote-text">
                    TABANG brings donors, NGOs, and responders together in one transparent
                    platform for disaster relief coordination. By showing verified needs and tracking
                    donations and pledges, it helps ensure that every contribution is purposeful,
                    accountable, and directed where it is needed most.
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 d-flex align-items-center mb-3 mb-md-0">
                    <img src="/logos/tupvlogo.png" alt="TUPV Logo" class="footer-logo">
                    <img src="/logos/dswdlogo.png" alt="DSWD Logo" class="footer-logo">
                    <p class="footer-text mb-0">Copyright 2026. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="footer-note mb-0">This website was developed as an academic project in coordination with
                        the
                        Department of Social Welfare and Development (DSWD) to support disaster relief
                        donation and distribution processes.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
