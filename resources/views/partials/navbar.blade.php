<nav class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ url('/') }}" class="navbar-brand-custom">TABANG</a>

        <!-- Mobile Toggle Button (only visible on mobile) -->
        <button class="navbar-toggler-custom d-lg-none" type="button" id="navbarToggleBtn">
            <i class="bi bi-list"></i>
        </button>

        <!-- Desktop Navigation (hidden on mobile) -->
        <div class="d-none d-lg-flex align-items-center">
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link-custom">Dashboard</a>
                    <a href="{{ route('admin.drives.index') }}" class="nav-link-custom">Drives</a>
                    <a href="{{ route('admin.pledges.pending') }}" class="nav-link-custom">Verify Pledges</a>
                    <a href="{{ route('admin.ngos.pending') }}" class="nav-link-custom">Verify NGOs</a>
                    <a href="{{ route('admin.reports.index') }}" class="nav-link-custom">Reports</a>
                @elseif(auth()->user()->isDonor())
                    <a href="{{ route('donor.dashboard') }}" class="nav-link-custom">Dashboard</a>
                    <a href="{{ route('donor.pledges.index') }}" class="nav-link-custom">My Pledges</a>
                    <a href="{{ route('donor.map') }}" class="nav-link-custom">Drive Map</a>
                @elseif(auth()->user()->isNgo())
                    <a href="{{ route('ngo.dashboard') }}" class="nav-link-custom">Dashboard</a>
                    <a href="{{ route('ngo.pledges.index') }}" class="nav-link-custom">Our Pledges</a>
                    <a href="{{ route('ngo.donation-link.index') }}" class="nav-link-custom">Donation Link</a>
                    <a href="{{ route('ngo.map') }}" class="nav-link-custom">Drive Map</a>
                @endif

                {{-- Notifications --}}
                @if (!auth()->user()->isAdmin())
                    <a class="nav-link-custom notification-badge"
                        href="{{ route(auth()->user()->role . '.notifications.index') }}">
                        <i class="bi bi-bell"></i>
                        @if (auth()->user()->unreadNotifications()->count() > 0)
                            <span
                                class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                @endif

                {{-- User Dropdown --}}
                <div class="dropdown dropdown-custom ms-2">
                    <a class="nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle me-1"
                                width="24" height="24">
                        @else
                            <i class="bi bi-person-circle me-1"></i>
                        @endif
                        {{ auth()->user()->display_name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small">{{ ucfirst(auth()->user()->role) }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('about') }}" class="nav-link-custom">About us</a>
                <a href="{{ route('login') }}" class="nav-link-custom">Login</a>
                <a href="{{ route('register') }}" class="nav-link-custom">SIGN UP</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Mobile Navigation Menu (hidden by default, only visible on mobile when toggled) -->
<div class="navbar-mobile-menu d-lg-none" id="navbarMobileMenu">
    @auth
        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link-mobile">Dashboard</a>
            <a href="{{ route('admin.drives.index') }}" class="nav-link-mobile">Drives</a>
            <a href="{{ route('admin.pledges.pending') }}" class="nav-link-mobile">Verify Pledges</a>
            <a href="{{ route('admin.ngos.pending') }}" class="nav-link-mobile">Verify NGOs</a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link-mobile">Reports</a>
        @elseif(auth()->user()->isDonor())
            <a href="{{ route('donor.dashboard') }}" class="nav-link-mobile">Dashboard</a>
            <a href="{{ route('donor.pledges.index') }}" class="nav-link-mobile">My Pledges</a>
            <a href="{{ route('donor.map') }}" class="nav-link-mobile">Drive Map</a>
        @elseif(auth()->user()->isNgo())
            <a href="{{ route('ngo.dashboard') }}" class="nav-link-mobile">Dashboard</a>
            <a href="{{ route('ngo.pledges.index') }}" class="nav-link-mobile">Our Pledges</a>
            <a href="{{ route('ngo.donation-link.index') }}" class="nav-link-mobile">Donation Link</a>
            <a href="{{ route('ngo.map') }}" class="nav-link-mobile">Drive Map</a>
        @endif

        @if (!auth()->user()->isAdmin())
            <a class="nav-link-mobile" href="{{ route(auth()->user()->role . '.notifications.index') }}">
                <i class="bi bi-bell me-2"></i>Notifications
                @if (auth()->user()->unreadNotifications()->count() > 0)
                    <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications()->count() }}</span>
                @endif
            </a>
        @endif

        <hr class="mobile-divider">
        <div class="nav-link-mobile user-info">
            @if (auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle me-2" width="24"
                    height="24">
            @else
                <i class="bi bi-person-circle me-2"></i>
            @endif
            {{ auth()->user()->display_name }} ({{ ucfirst(auth()->user()->role) }})
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link-mobile logout-btn">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    @else
        <a href="{{ route('about') }}" class="nav-link-mobile">About us</a>
        <a href="{{ route('login') }}" class="nav-link-mobile">Login</a>
        <a href="{{ route('register') }}" class="nav-link-mobile">SIGN UP</a>
    @endauth
</div>

<style>
    /* Mobile Menu Styles */
    .navbar-mobile-menu {
        display: none;
        background: var(--vivid-orange);
        padding: 1rem;
    }

    .navbar-mobile-menu.show {
        display: block;
    }

    .nav-link-mobile {
        display: block;
        color: #ffffff;
        font-weight: 500;
        padding: 0.75rem 0;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .nav-link-mobile:hover {
        opacity: 0.8;
        color: #ffffff;
    }

    .mobile-divider {
        border-color: rgba(255, 255, 255, 0.3);
        margin: 0.5rem 0;
    }

    .nav-link-mobile.user-info {
        opacity: 0.7;
    }

    .nav-link-mobile.logout-btn {
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
        font-size: 1rem;
        font-family: inherit;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('navbarToggleBtn');
        const mobileMenu = document.getElementById('navbarMobileMenu');

        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('show');
            });
        }
    });
</script>
