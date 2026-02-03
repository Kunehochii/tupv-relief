<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>About Us - TABANG Relief Donation Platform</title>

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
            --vivid-orange: #ea4f2d;
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
            background: var(--vivid-orange);
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

        /* Hero Header Section */
        .hero-header {
            position: relative;
            width: 100%;
            min-height: 300px;
        }

        .hero-header img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Content Sections */
        .about-section {
            padding: 4rem 0;
            background: #ffffff;
        }

        .section-title {
            color: var(--dark-blue);
            font-weight: 700;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .section-text {
            color: var(--gray-blue);
            font-size: 1rem;
            line-height: 1.8;
            text-align: justify;
            max-width: 800px;
            margin: 0 auto;
        }

        /* What Can You Do Section */
        .what-section {
            padding: 4rem 0;
            background: #ffffff;
        }

        .feature-card {
            border: 2px solid var(--dark-blue);
            border-radius: 8px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 1, 103, 0.1);
        }

        .feature-title {
            color: var(--dark-blue);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .feature-text {
            color: var(--gray-blue);
            font-size: 0.9rem;
            line-height: 1.7;
            text-align: justify;
        }

        /* How to Help Section */
        .help-section {
            padding: 4rem 0;
            background: #ffffff;
        }

        .help-card {
            border: 2px solid var(--dark-blue);
            border-radius: 8px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .help-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 1, 103, 0.1);
        }

        .help-label {
            color: var(--dark-blue);
            font-weight: 700;
            font-size: 1rem;
            display: inline;
        }

        .help-text {
            color: var(--gray-blue);
            font-size: 0.9rem;
            line-height: 1.7;
            text-align: justify;
            display: inline;
        }

        /* Footer */
        .footer-custom {
            background: var(--vivid-orange);
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

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-custom {
                padding: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .section-text {
                padding: 0 1rem;
            }

            .feature-card,
            .help-card {
                margin-bottom: 1rem;
            }
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

    <!-- Hero Header Image -->
    <section class="hero-header">
        <img src="/aboutus-header.png" alt="Every peso counts - About Us">
    </section>

    <!-- About Us Section -->
    <section class="about-section">
        <div class="container">
            <h2 class="section-title">About us</h2>
            <p class="section-text">
                We are a web-based disaster relief coordination platform designed to make donations and relief
                distribution faster, more transparent, and more effective. Built in response to recurring disaster
                challenges in the Philippines, our system connects government agencies, NGOs, and donors through a
                single, centralized platform. By transforming manual, fragmented processes into a data-driven digital
                system, we help ensure that aid reaches the right people, at the right time, with minimal waste and
                duplication.
            </p>

            <!-- TABANG Acronym Section -->
            <div class="acronym-section text-center py-4 bg-light rounded my-4">
                <h4 class="mb-3" style="color: var(--dark-blue);">
                    <i class="bi bi-heart-fill" style="color: var(--vivid-red);"></i>
                    What is TABANG?
                </h4>
                <p class="lead mb-0" style="color: var(--dark-blue);">
                    <strong>T</strong>imely <strong>A</strong>ssistance <strong>B</strong>ringing
                    <strong>A</strong>id to <strong>N</strong>eedy <strong>G</strong>roups
                </p>
            </div>
        </div>
    </section>

    <!-- What Can You Do Section -->
    <section class="what-section">
        <div class="container">
            <h2 class="section-title">What can you do?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <h3 class="feature-title">See Verified Disaster Needs</h3>
                        <p class="feature-text">
                            View real-time, verified information on affected areas and required relief goods. This helps
                            you understand exactly what communities need. No guesswork, only data-backed needs.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <h3 class="feature-title">Donate or Pledge Easily</h3>
                        <p class="feature-text">
                            Pledge goods or support based on identified requirements. Your contribution is recorded and
                            tracked within the system. This ensures donations are purposeful and accountable.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <h3 class="feature-title">Track Relief Distribution</h3>
                        <p class="feature-text">
                            Follow donations from pledge to distribution through a centralized system. Inventory and
                            deliveries are monitored to reduce duplication and waste. Transparency is built into every
                            step.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How to Help Section -->
    <section class="help-section">
        <div class="container">
            <h2 class="section-title">How to help?</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="help-card">
                        <p>
                            <span class="help-label">DONATE</span>
                            <span class="help-text">Support disaster-affected communities by donating goods or monetary
                                assistance based on verified needs. Your donation is recorded and monitored to ensure it
                                reaches the right beneficiaries. Every contribution directly supports timely and fair
                                relief distribution.</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="help-card">
                        <p>
                            <span class="help-label">PLEDGE</span>
                            <span class="help-text">Commit specific goods or support in advance through the pledge
                                system. Pledges help responders plan, prevent oversupply, and prioritize urgent needs.
                                Each pledge is tracked to promote accountability and transparency.</span>
                        </p>
                    </div>
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
