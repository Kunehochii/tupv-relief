<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="{{ url('/') }}">
            <i class="bi bi-heart-pulse-fill me-2"></i>Relief
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.drives.index') }}">Drives</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.pledges.pending') }}">Verify Pledges</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.ngos.pending') }}">Verify NGOs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a>
                        </li>
                    @elseif(auth()->user()->isDonor())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donor.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donor.pledges.create') }}">Make a Pledge</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donor.pledges.index') }}">My Pledges</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donor.map') }}">Drive Map</a>
                        </li>
                    @elseif(auth()->user()->isNgo())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ngo.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ngo.pledges.create') }}">Make a Pledge</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ngo.pledges.index') }}">Our Pledges</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ngo.donation-link.index') }}">Donation Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ngo.map') }}">Drive Map</a>
                        </li>
                    @endif
                @endauth
            </ul>
            
            <ul class="navbar-nav">
                @auth
                    @if(!auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link notification-badge" href="{{ route(auth()->user()->role . '.notifications.index') }}">
                                <i class="bi bi-bell"></i>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications()->count() }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle me-1" width="24" height="24">
                            @else
                                <i class="bi bi-person-circle me-1"></i>
                            @endif
                            {{ auth()->user()->display_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small">{{ ucfirst(auth()->user()->role) }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
