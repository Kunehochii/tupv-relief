<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Relief') }} Admin - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ========================================
           Relief Admin Design System
           Color Palette:
           - Dark Blue: #000167
           - Red: #dd3319
           - Vivid Red: #e51d00
           - Orange: #ffae44
           - Gray Blue: #8a95b6
           - Gray: #e6e6e4
           - Vivid Orange: #ea4f2d
        ======================================== */

        :root {
            --relief-dark-blue: #000167;
            --relief-red: #dd3319;
            --relief-vivid-red: #e51d00;
            --relief-orange: #ffae44;
            --relief-gray-blue: #8a95b6;
            --relief-gray: #e6e6e4;
            --relief-vivid-orange: #ea4f2d;

            --sidebar-width: 240px;
            --topbar-height: 56px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--relief-gray);
            min-height: 100vh;
        }

        /* ========================================
           Sidebar Styles
        ======================================== */

        .admin-sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--relief-dark-blue);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-avatar {
            padding: 24px;
            display: flex;
            justify-content: center;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--relief-dark-blue);
            box-shadow: 0 0 0 3px #ffffff;
        }

        .avatar-circle i {
            font-size: 40px;
            color: var(--relief-dark-blue);
        }

        .avatar-circle.small {
            width: 40px;
            height: 40px;
            border-width: 2px;
            box-shadow: 0 0 0 2px #ffffff;
        }

        .avatar-circle.small i {
            font-size: 20px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
        }

        .sidebar-link {
            color: #ffffff;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 15px;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-link.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15);
            border-left-color: #ffffff;
        }

        .sidebar-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .sidebar-badge {
            margin-left: auto;
            background-color: var(--relief-vivid-red);
            color: #ffffff;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        /* ========================================
           Main Content Area
        ======================================== */

        .admin-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========================================
           Top Navigation Bar
        ======================================== */

        .admin-topbar {
            background-color: var(--relief-red);
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-nav {
            display: flex;
            gap: 32px;
        }

        .topbar-nav a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            transition: opacity 0.2s;
        }

        .topbar-nav a:hover,
        .topbar-nav a.active {
            opacity: 1;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notification-btn {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            position: relative;
        }

        .notification-btn:hover {
            opacity: 0.8;
        }

        .notification-btn .badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10px;
        }

        /* ========================================
           Page Content
        ======================================== */

        .admin-content {
            flex: 1;
            padding: 24px;
        }

        .page-title {
            color: var(--relief-dark-blue);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        /* ========================================
           Stat Cards
        ======================================== */

        .stat-cards-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background-color: var(--relief-dark-blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon i {
            color: #ffffff;
            font-size: 22px;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--relief-dark-blue);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: var(--relief-gray-blue);
            margin-top: 4px;
        }

        /* ========================================
           Content Cards
        ======================================== */

        .content-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .content-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .content-card-header {
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .content-card-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--relief-dark-blue);
            margin: 0;
        }

        .view-all-link {
            font-size: 13px;
            color: var(--relief-dark-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .view-all-link:hover {
            text-decoration: underline;
        }

        .content-card-body {
            padding: 0;
        }

        /* ========================================
           Tables
        ======================================== */

        .admin-table {
            width: 100%;
            font-size: 14px;
            margin-bottom: 0;
        }

        .admin-table thead th {
            background-color: #fafafa;
            padding: 12px 16px;
            font-weight: 600;
            color: var(--relief-gray-blue);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f0f0f0;
        }

        .admin-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f5f5f5;
            color: #333;
        }

        .admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background-color: #fafafa;
        }

        .admin-table a {
            color: var(--relief-dark-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .admin-table a:hover {
            text-decoration: underline;
        }

        /* ========================================
           Progress Bar
        ======================================== */

        .progress-sm {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            min-width: 80px;
        }

        .progress-sm .progress-bar {
            background-color: var(--relief-dark-blue);
            border-radius: 3px;
        }

        /* ========================================
           Alerts
        ======================================== */

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* ========================================
           Buttons
        ======================================== */

        .btn-relief-primary {
            background-color: var(--relief-dark-blue);
            border-color: var(--relief-dark-blue);
            color: #ffffff;
        }

        .btn-relief-primary:hover {
            background-color: #000155;
            border-color: #000155;
            color: #ffffff;
        }

        .btn-relief-red {
            background-color: var(--relief-red);
            border-color: var(--relief-red);
            color: #ffffff;
        }

        .btn-relief-red:hover {
            background-color: var(--relief-vivid-red);
            border-color: var(--relief-vivid-red);
            color: #ffffff;
        }

        /* ========================================
           Empty State
        ======================================== */

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--relief-gray-blue);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* ========================================
           Sidebar Toggle Button
        ======================================== */

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #ffffff;
            font-size: 24px;
            padding: 4px 8px;
            cursor: pointer;
            line-height: 1;
        }

        .topbar-title {
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ========================================
           Sidebar Overlay (mobile)
        ======================================== */

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ========================================
           Responsive Table Wrapper
        ======================================== */

        .table-responsive-mobile {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ========================================
           Responsive
        ======================================== */

        @media (max-width: 1200px) {
            .stat-cards-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .content-row {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                width: 70px;
            }

            .sidebar-link span,
            .sidebar-avatar,
            .sidebar-footer {
                display: none;
            }

            .sidebar-link {
                justify-content: center;
                padding: 16px;
            }

            .sidebar-badge {
                display: none;
            }

            .admin-wrapper {
                margin-left: 70px;
            }

            :root {
                --sidebar-width: 70px;
            }
        }

        @media (max-width: 768px) {

            /* --- Sidebar: off-canvas overlay --- */
            .admin-sidebar {
                width: var(--sidebar-width);
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-sidebar.open .sidebar-link span,
            .admin-sidebar.open .sidebar-avatar,
            .admin-sidebar.open .sidebar-footer {
                display: flex;
            }

            .admin-sidebar.open .sidebar-badge {
                display: inline-block;
            }

            .admin-sidebar.open .sidebar-link {
                justify-content: flex-start;
                padding: 14px 24px;
            }

            :root {
                --sidebar-width: 240px;
            }

            .admin-wrapper {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: inline-flex;
            }

            /* --- Topbar --- */
            .admin-topbar {
                padding: 0 16px;
                gap: 12px;
            }

            /* --- Content --- */
            .admin-content {
                padding: 16px;
            }

            .page-title {
                font-size: 20px;
                margin-bottom: 16px;
            }

            /* --- Stat Cards --- */
            .stat-cards-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 14px;
                gap: 10px;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
            }

            .stat-icon i {
                font-size: 18px;
            }

            .stat-value {
                font-size: 22px;
            }

            .stat-label {
                font-size: 11px;
            }

            /* --- Content Cards --- */
            .content-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            /* --- Tables: scrollable --- */
            .content-card-body {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .admin-table {
                min-width: 560px;
            }

            .admin-table thead th,
            .admin-table tbody td {
                padding: 10px 12px;
                font-size: 13px;
                white-space: nowrap;
            }

            /* --- Header action buttons --- */
            .d-flex.gap-2 {
                flex-wrap: wrap;
            }

            .btn-header-action {
                padding: 8px 12px;
                font-size: 13px;
            }

            /* --- Breadcrumbs --- */
            .breadcrumb {
                font-size: 12px !important;
                flex-wrap: wrap;
            }

            /* --- Alerts --- */
            .alert {
                font-size: 13px;
                padding: 10px 14px;
            }
        }

        @media (max-width: 480px) {
            .stat-cards-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .stat-card {
                flex-direction: row;
                align-items: center;
                padding: 12px 14px;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
            }

            .stat-value {
                font-size: 20px;
            }

            .page-title {
                font-size: 18px;
            }

            .admin-content {
                padding: 12px;
            }

            .content-card-header {
                padding: 12px 14px;
                flex-wrap: wrap;
                gap: 8px;
            }
        }

        @yield('styles')
    </style>
</head>

<body>
    @include('admin.partials.sidebar', ['currentPage' => View::yieldContent('page', '')])

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-wrapper">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title d-md-none">@yield('title', 'Dashboard')</span>
        </header>

        <!-- Main Content -->
        <main class="admin-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Mobile sidebar toggle
        (function() {
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (toggle) toggle.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            // Close sidebar on nav link click (mobile)
            document.querySelectorAll('.sidebar-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) closeSidebar();
                });
            });

            // Close on resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) closeSidebar();
            });
        })();
    </script>

    @yield('scripts')
</body>

</html>
