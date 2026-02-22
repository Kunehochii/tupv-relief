{{-- Admin Sidebar Component --}}
{{-- Usage: @include('admin.partials.sidebar', ['currentPage' => 'dashboard']) --}}

<aside class="admin-sidebar">
    <!-- User Avatar Section -->
    <div class="sidebar-avatar">
        <div class="avatar-circle">
            <i class="bi bi-person-fill"></i>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="sidebar-nav">
        <a class="sidebar-link {{ ($currentPage ?? '') === 'dashboard' ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>

        <a class="sidebar-link {{ ($currentPage ?? '') === 'create-drive' ? 'active' : '' }}"
            href="{{ route('admin.drives.create') }}">
            <i class="bi bi-plus-lg"></i>
            <span>Create Donation Drive</span>
        </a>

        <a class="sidebar-link {{ ($currentPage ?? '') === 'drives' ? 'active' : '' }}"
            href="{{ route('admin.drives.index') }}">
            <i class="bi bi-folder-fill"></i>
            <span>Manage Donation Drives</span>
        </a>

        <a class="sidebar-link {{ ($currentPage ?? '') === 'pledges' ? 'active' : '' }}"
            href="{{ route('admin.pledges.pending') }}">
            <i class="bi bi-patch-check-fill"></i>
            <span>Receive Pledges</span>
            @if (isset($pendingPledgesCount) && $pendingPledgesCount > 0)
                <span class="sidebar-badge">{{ $pendingPledgesCount }}</span>
            @endif
        </a>

        <a class="sidebar-link {{ ($currentPage ?? '') === 'ngos' ? 'active' : '' }}"
            href="{{ route('admin.ngos.pending') }}">
            <i class="bi bi-people-fill"></i>
            <span>Verify NGOs</span>
            @if (isset($pendingNgosCount) && $pendingNgosCount > 0)
                <span class="sidebar-badge">{{ $pendingNgosCount }}</span>
            @endif
        </a>

        <a class="sidebar-link {{ ($currentPage ?? '') === 'map' ? 'active' : '' }}"
            href="{{ route('admin.drives.map') }}">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Map</span>
        </a>

        <a class="sidebar-link {{ ($currentPage ?? '') === 'reports' ? 'active' : '' }}"
            href="{{ route('admin.reports.index') }}">
            <i class="bi bi-file-text-fill"></i>
            <span>Reports</span>
        </a>
    </nav>

    <!-- Bottom User Section -->
    <div class="sidebar-footer">
        <a href="#" class="sidebar-user" data-bs-toggle="dropdown">
            <div class="avatar-circle small">
                <i class="bi bi-person-fill"></i>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark">
            <li><span class="dropdown-item-text text-light">{{ auth()->user()->name }}</span></li>
            <li><span class="dropdown-item-text text-muted small">Administrator</span></li>
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
</aside>
