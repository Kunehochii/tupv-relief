# Admin Portal Design System

> This document provides design specifications and guidelines for the Admin (DSWD) portal interface.

---

## Color Palette

The admin portal uses the following color scheme from the Relief brand:

| Color         | Hex Code   | Usage                                      |
| ------------- | ---------- | ------------------------------------------ |
| Dark Blue     | `#000167`  | Sidebar background, primary text, headings |
| Red           | `#dd3319`  | Top navbar background, accents             |
| Vivid Red     | `#e51d00`  | Hover states, alerts                       |
| Orange        | `#ffae44`  | Warnings, secondary actions                |
| Gray Blue     | `#8a95b6`  | Inactive sidebar text                      |
| Gray          | `#e6e6e4`  | Page background, borders                   |
| Vivid Orange  | `#ea4f2d`  | Call-to-action buttons                     |

---

## Layout Structure

The admin portal uses a fixed two-column layout:

```
┌─────────────────────────────────────────────────────────────────┐
│                     Top Navigation Bar (Red)                     │
├──────────────┬──────────────────────────────────────────────────┤
│              │                                                   │
│   Sidebar    │              Main Content Area                    │
│  (Dark Blue) │               (Gray Background)                   │
│              │                                                   │
│   240px      │              Fluid Width                          │
│              │                                                   │
└──────────────┴──────────────────────────────────────────────────┘
```

---

## Reusable Sidebar Component

The sidebar is a **reusable partial** located at:

```
resources/views/admin/partials/sidebar.blade.php
```

### Structure

```blade
<aside class="admin-sidebar">
    <!-- User Avatar Section -->
    <div class="sidebar-avatar">
        <div class="avatar-circle">
            <i class="bi bi-person-fill"></i>
        </div>
    </div>
    
    <!-- Navigation Links -->
    <nav class="sidebar-nav">
        <!-- Menu items with icons -->
    </nav>
    
    <!-- Bottom User Section -->
    <div class="sidebar-footer">
        <div class="avatar-circle small">
            <i class="bi bi-person-fill"></i>
        </div>
    </div>
</aside>
```

### Navigation Items

| Menu Item       | Icon               | Route                      | Description                    |
| --------------- | ------------------ | -------------------------- | ------------------------------ |
| Dashboard       | `bi-house-door`    | `admin.dashboard`          | Overview statistics            |
| Create Drive    | `bi-plus-lg`       | `admin.drives.create`      | Create new donation drive      |
| Manage Drives   | `bi-folder`        | `admin.drives.index`       | View/edit all drives           |
| Verify Pledges  | `bi-patch-check`   | `admin.pledges.pending`    | Approve pending pledges        |
| Verify NGOs     | `bi-people`        | `admin.ngos.pending`       | Approve NGO registrations      |
| Map             | `bi-geo-alt`       | `admin.drives.map`         | Interactive drives map         |
| Reports         | `bi-file-text`     | `admin.reports.index`      | Analytics and exports          |

### Active State Logic

The sidebar uses a `$currentPage` variable passed from views to highlight the active menu item:

```blade
<a class="sidebar-link {{ $currentPage === 'dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
```

### Styling

```css
.admin-sidebar {
    width: 240px;
    min-height: 100vh;
    background-color: #000167;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
}

.sidebar-link {
    color: #8a95b6;
    padding: 12px 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: all 0.2s;
}

.sidebar-link:hover,
.sidebar-link.active {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-link.active {
    border-left: 3px solid #ffffff;
}
```

---

## Top Bar

The top bar spans the content area (excludes sidebar) and uses the **Red** (`#dd3319`) background. It serves as a simple accent bar without any navigation items or actions.

### Structure

```blade
<header class="admin-topbar">
    {{-- Empty topbar - just a solid red accent bar --}}
</header>
```

### Styling

```css
.admin-topbar {
    background-color: #dd3319;
    height: 56px;
    position: sticky;
    top: 0;
    z-index: 100;
}
```

---

## Pledge Action Buttons

The pledge verification pages use action buttons that change based on the pledge status. Clicking a button advances the pledge to the next status.

### Button States

| Status      | Button Style      | Action on Click          |
| ----------- | ----------------- | ------------------------ |
| Pending     | Red (solid)       | Verify pledge            |
| Verified    | Green (solid)     | Mark as distributed      |
| Distributed | Dark Blue (solid) | No action (final state)  |

### Structure

```blade
@if($pledge->status === 'pending')
    <form method="POST" action="{{ route('admin.pledges.verify', $pledge) }}">
        @csrf
        <button type="submit" class="btn-action btn-action-pending">
            Pending
        </button>
    </form>
@elseif($pledge->status === 'verified')
    <form method="POST" action="{{ route('admin.pledges.distribute', $pledge) }}">
        @csrf
        <button type="submit" class="btn-action btn-action-verified">
            <i class="bi bi-check-circle-fill me-1"></i>Verified
        </button>
    </form>
@elseif($pledge->status === 'distributed')
    <span class="btn-action btn-action-distributed">
        <i class="bi bi-box-seam me-1"></i>Distributed
    </span>
@endif
```

### Styling

```css
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 100px;
}

.btn-action-pending {
    background-color: #dd3319;
    color: #ffffff;
}

.btn-action-pending:hover {
    background-color: #e51d00;
}

.btn-action-verified {
    background-color: #198754;
    color: #ffffff;
}

.btn-action-verified:hover {
    background-color: #157347;
}

.btn-action-distributed {
    background-color: #000167;
    color: #ffffff;
}
```

---

## NGO Verification (Account Verifications)

The NGO verification page displays all NGO accounts with their verification status and allows admins to approve pending NGOs or delete existing ones.

### Table Structure

| Column        | Description                              |
| ------------- | ---------------------------------------- |
| Name          | Organization name (or user name)         |
| Email         | NGO contact email                        |
| Submitted     | Registration date and time               |
| Attached File | Certificate file link (if uploaded)      |
| Actions       | Status button and delete option          |

### Action States

| Status   | Display                                  | Action on Click               |
| -------- | ---------------------------------------- | ----------------------------- |
| Pending  | Red "Pending" button                     | Approves the NGO              |
| Verified | Trash icon + Green "Verified" badge     | Trash deletes the NGO         |
| Rejected | Trash icon + Gray "Rejected" badge      | Trash deletes the NGO         |

### Structure

```blade
@if ($ngo->verification_status === 'pending')
    <form method="POST" action="{{ route('admin.ngos.approve', $ngo) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn-status btn-status-pending">
            Pending
        </button>
    </form>
@elseif ($ngo->verification_status === 'verified')
    <form method="POST" action="{{ route('admin.ngos.destroy', $ngo) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete">
            <i class="bi bi-trash3"></i>
        </button>
    </form>
    <span class="btn-status btn-status-verified">
        <i class="bi bi-check-circle-fill me-1"></i>Verified
    </span>
@endif
```

### Styling

```css
.btn-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    min-width: 100px;
}

.btn-status-pending {
    background-color: #dd3319;
    color: #ffffff;
    cursor: pointer;
}

.btn-status-pending:hover {
    background-color: #e51d00;
}

.btn-status-verified {
    background-color: #198754;
    color: #ffffff;
    cursor: default;
}

.btn-delete {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    background-color: #ffffff;
    color: #6c757d;
    cursor: pointer;
}

.btn-delete:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #ffffff;
}
```

---

## Stat Cards

Dashboard stat cards display key metrics with icons.

### Structure

```blade
<div class="stat-card">
    <div class="stat-icon">
        <i class="bi bi-folder-fill"></i>
    </div>
    <div class="stat-content">
        <span class="stat-value">{{ $value }}</span>
        <span class="stat-label">Label</span>
    </div>
</div>
```

### Icons for Stats

| Metric              | Icon                 |
| ------------------- | -------------------- |
| Total Drives        | `bi-folder-fill`     |
| Active Drives       | `bi-play-circle`     |
| Total Pledges       | `bi-handshake`       |
| Pending Pledges     | `bi-file-earmark`    |

---

## Admin Layout Usage

To use the admin layout in a view:

```blade
@extends('layouts.admin')

@section('title', 'Page Title')

@section('page', 'page-identifier')  {{-- For sidebar active state --}}

@section('content')
    <!-- Page content goes here -->
@endsection
```

---

## Files Structure

```
resources/views/
├── layouts/
│   └── admin.blade.php          # Admin-specific layout with sidebar
├── admin/
│   ├── partials/
│   │   └── sidebar.blade.php    # Reusable sidebar component
│   ├── dashboard.blade.php      # Dashboard overview
│   ├── drives/                  # Drive management views
│   ├── pledges/                 # Pledge management views
│   ├── ngos/                    # NGO verification views
│   └── reports/                 # Reports views
```

---

## Responsive Behavior

- **Desktop (≥992px):** Full sidebar visible, fixed position
- **Tablet (768-991px):** Sidebar collapses to icons only
- **Mobile (<768px):** Sidebar hidden, hamburger menu in topbar

---

## Implementation Checklist

- [x] Define color variables
- [x] Create admin layout (`layouts/admin.blade.php`)
- [x] Create sidebar partial (`admin/partials/sidebar.blade.php`)
- [x] Update Dashboard view
- [x] Update Drives views
- [x] Update Pledges views
- [x] Update NGOs views
- [x] Update Reports views
