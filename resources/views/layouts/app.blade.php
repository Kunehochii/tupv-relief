<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TABANG - @yield('title', 'Relief Donation Platform')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --dark-blue: #000167;
            --red: #dd3319;
            --vivid-red: #e51d00;
            --orange: #ffae44;
            --gray-blue: #8a95b6;
            --gray: #e6e6e4;
            --vivid-orange: #ea4f2d;
            /* Legacy aliases for compatibility */
            --relief-primary: #000167;
            --relief-secondary: #8a95b6;
            --relief-success: #16a34a;
            --relief-danger: #dd3319;
            --relief-warning: #ffae44;
            --relief-info: #0891b2;
            --relief-purple: #7c3aed;
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

        /* Custom Navbar - TABANG Style */
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

        .navbar-brand-custom:hover span {
            color: #ffffff;
        }

        .nav-link-custom {
            color: #ffffff !important;
            font-weight: 500;
            margin-left: 1.5rem;
            text-decoration: none;
            transition: opacity 0.3s;
            display: inline-flex;
            align-items: center;
        }

        .nav-link-custom:hover {
            opacity: 0.8;
            color: var(--orange) !important;
        }

        .nav-link-custom .badge {
            margin-left: 0.25rem;
        }

        .navbar-toggler-custom {
            border: 2px solid #ffffff;
            padding: 0.5rem;
            background: transparent;
        }

        .navbar-toggler-custom .bi {
            color: #ffffff;
            font-size: 1.5rem;
        }

        /* Dropdown for user menu */
        .dropdown-custom .dropdown-toggle::after {
            display: none;
        }

        .dropdown-custom .dropdown-menu {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }

        .btn-primary {
            background-color: var(--dark-blue);
            border-color: var(--dark-blue);
        }

        .btn-primary:hover {
            background-color: #000050;
            border-color: #000050;
        }

        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: transform 0.3s;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--gray);
        }

        .section-title {
            color: var(--vivid-red);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-blue);
            line-height: 1;
        }

        .stat-label {
            color: var(--gray-blue);
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge .badge {
            position: absolute;
            top: -5px;
            right: -10px;
        }

        .status-pending {
            color: var(--orange);
        }

        .status-verified {
            color: var(--relief-success);
        }

        .status-expired {
            color: var(--red);
        }

        .status-distributed {
            color: var(--relief-purple);
        }

        .bg-status-pending {
            background-color: #fff3e0;
        }

        .bg-status-verified {
            background-color: #dcfce7;
        }

        .bg-status-expired {
            background-color: #fee2e2;
        }

        .bg-status-distributed {
            background-color: #f3e8ff;
        }

        #map {
            height: 400px;
            border-radius: 8px;
        }

        /* Quick Stats Bar */
        .quick-stats-bar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .quick-stat {
            text-align: center;
            padding: 10px;
        }

        .quick-stat .stat-value {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-blue);
        }

        .quick-stat .stat-label {
            display: block;
            font-size: 0.85rem;
            color: var(--gray-blue);
            text-transform: uppercase;
        }

        /* Progress Bar - TABANG Style */
        .progress {
            height: 8px;
            background: var(--gray);
            border-radius: 4px;
        }

        .progress-bar {
            background: var(--vivid-red);
            border-radius: 4px;
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #fff;
            border-right: 1px solid var(--gray);
        }

        .sidebar .nav-link {
            color: var(--gray-blue);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin: 0.125rem 0;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--dark-blue);
        }

        .sidebar .nav-link.active {
            background-color: rgba(0, 1, 103, 0.1);
            color: var(--dark-blue);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 24px;
        }

        .verification-banner {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-left: 4px solid var(--orange);
        }

        /* Mobile Navigation - handled by navbar partial */
        @media (max-width: 991.98px) {
            .navbar-custom {
                padding: 0.75rem 1rem;
            }
        }

        /* ===== Mobile-First Responsive Utilities ===== */

        /* Container padding adjustments for mobile */
        @media (max-width: 767.98px) {

            .container,
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .container-fluid.px-md-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* Tighter vertical spacing on mobile */
            .py-4 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            /* Page headings */
            h4,
            .h4 {
                font-size: 1.15rem;
            }

            .section-title {
                font-size: 1.35rem;
                margin-bottom: 1.25rem;
            }

            /* Stat cards - compact on mobile */
            .stat-number {
                font-size: 1.75rem;
            }

            .stat-card .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            /* Cards - reduce shadow & hover effect on mobile (no hover on touch) */
            .card {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .card:hover {
                transform: none;
            }

            /* Tables: tighter cells on mobile */
            .table th,
            .table td {
                padding: 0.5rem 0.5rem;
                font-size: 0.85rem;
            }

            /* Buttons in flex headers */
            .d-flex.justify-content-between.align-items-center.mb-4 {
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-bottom: 1rem !important;
            }

            /* Quick stats bar */
            .quick-stats-bar {
                padding: 12px;
            }

            .quick-stat .stat-value {
                font-size: 1.5rem;
            }

            .quick-stat .stat-label {
                font-size: 0.75rem;
            }

            /* Alert compact on mobile */
            .alert {
                padding: 0.65rem 1rem;
                font-size: 0.9rem;
            }

            /* Map height - shorter on mobile */
            #map {
                height: 300px !important;
            }
        }

        /* Small phone adjustments (375px and below) */
        @media (max-width: 375px) {

            .container,
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .btn {
                font-size: 0.85rem;
                padding: 0.35rem 0.75rem;
            }

            .badge {
                font-size: 0.7rem;
            }
        }

        @yield('styles')
    </style>
</head>

<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @yield('scripts')
</body>

</html>
