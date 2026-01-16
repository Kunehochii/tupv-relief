# RELIEF - Agent Context Document

> This document provides AI coding agents with essential context about the Relief codebase structure, patterns, and conventions.

---

## Project Overview

**Relief** is a donation drive management system for the Department of Social Welfare and Development (DSWD) in the Philippines. It connects DSWD administrators, individual donors, and NGO partners to coordinate disaster relief efforts.

### Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8.0
- **Frontend:** Blade templates + Bootstrap 5
- **Maps:** Leaflet.js with OpenStreetMap
- **Auth:** Laravel Breeze + Socialite (Google OAuth)
- **Email:** SendGrid SMTP

---

## Directory Structure

```
relief/
├── app/
│   ├── Console/Commands/       # Artisan commands (ExpirePledges, SendExpiryWarnings)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # DSWD admin controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   ├── Donor/          # Donor portal controllers
│   │   │   └── Ngo/            # NGO portal controllers
│   │   └── Middleware/         # Role & verification middleware
│   ├── Mail/                   # Mailable classes
│   ├── Models/                 # Eloquent models
│   ├── Policies/               # Authorization policies
│   └── Services/               # Business logic services
├── bootstrap/
├── config/                     # Laravel configuration
├── database/
│   ├── migrations/             # Database schema
│   └── seeders/                # Data seeders
├── docs/                       # Documentation
├── resources/views/
│   ├── admin/                  # Admin portal views
│   ├── auth/                   # Authentication views
│   ├── donor/                  # Donor portal views
│   ├── emails/                 # Email templates
│   ├── layouts/                # Base layouts
│   ├── ngo/                    # NGO portal views
│   └── public/                 # Public pages
└── routes/                     # Route definitions
```

---

## User Roles & Access

### Role Constants (User model)

```php
const ROLE_ADMIN = 'admin';
const ROLE_DONOR = 'donor';
const ROLE_NGO = 'ngo';
```

### Middleware

| Middleware     | Purpose                      | Applied To        |
| -------------- | ---------------------------- | ----------------- |
| `admin`        | Restricts to admin users     | `admin/*` routes  |
| `donor`        | Restricts to donor users     | `donor/*` routes  |
| `ngo`          | Restricts to NGO users       | `ngo/*` routes    |
| `verified.ngo` | Requires verified NGO status | NGO pledge routes |

### NGO Verification States

```php
const VERIFICATION_PENDING = 'pending';
const VERIFICATION_VERIFIED = 'verified';
const VERIFICATION_REJECTED = 'rejected';
```

---

## Database Schema

### Core Tables

#### users

| Column                | Type    | Notes                       |
| --------------------- | ------- | --------------------------- |
| id                    | bigint  | Primary key                 |
| name                  | string  | User's full name            |
| email                 | string  | Unique, used for login      |
| role                  | enum    | admin, donor, ngo           |
| organization_name     | string? | NGO only                    |
| certificate_path      | string? | NGO certificate file        |
| verification_status   | string? | pending, verified, rejected |
| external_donation_url | string? | NGO external link           |
| google_id             | string? | OAuth identifier            |

#### drives

| Column               | Type     | Notes                       |
| -------------------- | -------- | --------------------------- |
| id                   | bigint   | Primary key                 |
| name                 | string   | Drive title                 |
| description          | text     | Full description            |
| target_type          | enum     | financial, in-kind          |
| target_amount        | decimal  | Goal amount/quantity        |
| collected_amount     | decimal  | Current progress            |
| status               | enum     | upcoming, active, completed |
| start_date, end_date | date     | Drive duration              |
| latitude, longitude  | decimal? | Map coordinates             |
| address              | string?  | Human-readable location     |
| items_needed         | json?    | Array of needed items       |

#### pledges

| Column            | Type       | Notes                                   |
| ----------------- | ---------- | --------------------------------------- |
| id                | bigint     | Primary key                             |
| reference_number  | string     | Auto-generated (REL-XXXXXXXX)           |
| user_id           | foreignId  | Donor/NGO who pledged                   |
| drive_id          | foreignId  | Target drive                            |
| items             | json?      | Array of items being pledged            |
| quantity          | integer    | Number of items (default: 1)            |
| details           | text?      | Additional details about the pledge     |
| contact_number    | string?    | Donor contact number                    |
| notes             | text?      | Additional notes                        |
| status            | enum       | pending, verified, expired, distributed |
| verified_at       | timestamp? | When pledge was verified                |
| verified_by       | foreignId? | Admin who verified                      |
| expired_at        | timestamp? | When pledge expired                     |
| distributed_at    | timestamp? | When donation was distributed           |
| families_helped   | integer?   | Impact metric (admin fills)             |
| relief_packages   | integer?   | Impact metric (admin fills)             |
| items_distributed | integer?   | Impact metric (admin fills)             |
| admin_feedback    | text?      | Distribution notes                      |

#### notifications

| Column     | Type       | Notes                 |
| ---------- | ---------- | --------------------- |
| id         | bigint     | Primary key           |
| user_id    | foreignId  | Recipient             |
| type       | string     | Notification category |
| title      | string     | Short heading         |
| message    | text       | Full message          |
| link       | string?    | Action URL            |
| read_at    | timestamp? | When user read it     |
| emailed_at | timestamp? | When email was sent   |

#### link_clicks

| Column     | Type      | Notes                      |
| ---------- | --------- | -------------------------- |
| id         | bigint    | Primary key                |
| user_id    | foreignId | NGO whose link was clicked |
| ip_address | string?   | Visitor IP                 |
| user_agent | string?   | Browser info               |

---

## Key Models & Relationships

### User

```php
// Relationships
$user->pledges()        // HasMany Pledge
$user->notifications()  // HasMany Notification
$user->linkClicks()     // HasMany LinkClick (NGO)

// Helpers
$user->isAdmin()
$user->isDonor()
$user->isNgo()
$user->isVerifiedNgo()
```

### Drive

```php
// Relationships
$drive->pledges()       // HasMany Pledge

// Attributes
$drive->progress_percentage  // Computed accessor
$drive->is_active           // Status check
```

### Pledge

```php
// Relationships
$pledge->user()    // BelongsTo User
$pledge->drive()   // BelongsTo Drive

// Constants
const STATUS_PENDING = 'pending';
const STATUS_VERIFIED = 'verified';
const STATUS_DISTRIBUTED = 'distributed';
const STATUS_EXPIRED = 'expired';

// Helpers
$pledge->generateReferenceNumber()  // PLG-XXXXX format
$pledge->status_color              // Bootstrap color accessor
```

### Notification

```php
// Types
const TYPE_PLEDGE_VERIFIED = 'pledge_verified';
const TYPE_PLEDGE_DISTRIBUTED = 'pledge_distributed';
const TYPE_PLEDGE_EXPIRED = 'pledge_expired';
const TYPE_PLEDGE_EXPIRING = 'pledge_expiring';
const TYPE_NEW_DRIVE = 'new_drive';
const TYPE_NGO_VERIFIED = 'ngo_verified';
const TYPE_NGO_REJECTED = 'ngo_rejected';
```

---

## Services

### NotificationService

Located at `app/Services/NotificationService.php`

**Purpose:** Centralized notification creation and email sending.

**Key Methods:**

```php
notify(User $user, string $type, string $title, string $message, ?string $link = null)
notifyPledgeVerified(Pledge $pledge)
notifyPledgeDistributed(Pledge $pledge)
notifyPledgeExpiring(Pledge $pledge)
notifyPledgeExpired(Pledge $pledge)
notifyNgoVerified(User $ngo)
notifyNgoRejected(User $ngo, string $reason)
notifyNewDrive(Drive $drive)  // Broadcasts to all donors
```

---

## Controller Patterns

### Namespace Convention

- `App\Http\Controllers\Admin\*` - DSWD admin actions
- `App\Http\Controllers\Auth\*` - Authentication
- `App\Http\Controllers\Donor\*` - Donor portal
- `App\Http\Controllers\Ngo\*` - NGO portal

### Common Controller Structure

```php
class DriveController extends Controller
{
    public function index()     // List all
    public function create()    // Show create form
    public function store()     // Handle create
    public function show()      // View single
    public function edit()      // Show edit form
    public function update()    // Handle edit
    public function destroy()   // Delete
}
```

### Admin-Specific Actions

```php
// PledgeController
public function verify(Pledge $pledge)      // Approve pledge
public function distribute(Pledge $pledge)  // Mark distributed
public function reject(Pledge $pledge)      // Decline pledge

// NgoVerificationController
public function approve(User $ngo)
public function reject(User $ngo)
```

---

## Route Structure

### Public Routes

```
GET  /                          welcome (landing)
GET  /drive/{drive}/preview     drive.preview (shareable)
GET  /ngo/{user}/donate         ngo.external-link (tracked redirect)
```

### Auth Routes

```
GET  /login                     login form
POST /login                     authenticate
GET  /register                  registration form
POST /register                  create account
GET  /auth/google               OAuth redirect
GET  /auth/google/callback      OAuth callback
GET  /auth/google/select-role   role selection (new OAuth)
POST /auth/google/set-role      save role choice
```

### Admin Routes (prefix: admin, middleware: admin)

```
GET  /admin/dashboard
Resource: /admin/drives
Resource: /admin/pledges (+ verify, distribute, reject)
GET  /admin/ngos/pending
POST /admin/ngos/{ngo}/approve
POST /admin/ngos/{ngo}/reject
GET  /admin/reports
GET  /admin/reports/export
```

### Donor Routes (prefix: donor, middleware: donor)

```
GET  /donor/dashboard
Resource: /donor/pledges
GET  /donor/map
GET  /donor/notifications
PATCH /donor/notifications/{id}/read
```

### NGO Routes (prefix: ngo, middleware: ngo)

```
GET  /ngo/dashboard
Resource: /ngo/pledges (middleware: verified.ngo)
GET  /ngo/map
GET  /ngo/donation-link
POST /ngo/donation-link
```

---

## View Conventions

### Layout

All pages extend `layouts.app` which provides:

- Bootstrap 5 CSS/JS
- Leaflet CSS/JS (for maps)
- Navigation bar (via `partials.navbar`)
- Flash message handling
- `@yield('content')` main content area
- `@yield('scripts')` for page-specific JS

### Blade Sections

```blade
@extends('layouts.app')
@section('title', 'Page Title')
@section('content')
    <!-- Page content -->
@endsection
@section('scripts')
    <!-- Page-specific JavaScript -->
@endsection
```

### Component Patterns

- Cards for data display
- Tables with `table-hover` for lists
- Badges for status indicators
- Progress bars for drive completion
- Alerts for flash messages

---

## Map Integration

### Frontend Setup

```javascript
// Initialize map
const map = L.map("map").setView([lat, lng], zoom);

// Add OpenStreetMap tiles
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution: "© OpenStreetMap contributors",
}).addTo(map);

// Add marker
L.marker([lat, lng]).addTo(map).bindPopup("Content");
```

### Common Use Cases

1. **Admin Create Drive:** Click to set location, reverse geocode for address
2. **Drive Preview:** Show single marker with info popup
3. **Donor/NGO Map:** Multiple markers for all active drives

---

## Scheduled Commands

### ExpirePledges

```bash
php artisan pledges:expire
```

Marks pledges as expired if pending for more than 7 days.

### SendExpiryWarnings

```bash
php artisan pledges:warn-expiring
```

Notifies users of pledges expiring within 3 days.

### Scheduler (console.php)

```php
Schedule::command('pledges:warn-expiring')->dailyAt('08:00');
Schedule::command('pledges:expire')->dailyAt('00:00');
```

---

## Environment Variables

### Critical Configuration

```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=relief
DB_USERNAME=root
DB_PASSWORD=

# Mail (SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

---

## Common Tasks for Agents

### Adding a New Notification Type

1. Add constant to `Notification` model
2. Add color mapping in `getColorAttribute()`
3. Add method to `NotificationService`
4. Update email template type handling

### Adding a New Admin Feature

1. Create controller in `App\Http\Controllers\Admin`
2. Add routes in `routes/web.php` under admin group
3. Create views in `resources/views/admin/`
4. Update navigation in `partials/navbar.blade.php`

### Adding a New Donor/NGO Feature

1. Create controller in appropriate namespace
2. Add routes with role middleware
3. Create views in appropriate folder
4. Ensure mobile-friendly (Bootstrap responsive)

### Modifying Database Schema

1. Create migration: `php artisan make:migration`
2. Update relevant model with new columns/relationships
3. Update affected views and controllers
4. Run migration: `php artisan migrate`

---

## Testing Checklist

When implementing features, verify:

- [ ] Route accessible with correct middleware
- [ ] Form validation works (server-side)
- [ ] Flash messages display on success/error
- [ ] Notifications created where appropriate
- [ ] Email sent for important events
- [ ] Mobile responsive layout
- [ ] Proper authorization checks

---

## Quick Reference

### Artisan Commands

```bash
php artisan migrate              # Run migrations
php artisan db:seed              # Seed database
php artisan make:model Name -m   # Create model + migration
php artisan make:controller Name # Create controller
php artisan route:list           # Show all routes
php artisan tinker               # Interactive shell
```

### Common Queries

```php
// Active drives
Drive::where('status', 'active')->get();

// Pending pledges
Pledge::where('status', 'pending')->with('user', 'drive')->get();

// Unread notifications
$user->notifications()->whereNull('read_at')->get();

// Verified NGOs
User::where('role', 'ngo')->where('verification_status', 'verified')->get();
```

---

_This document should be kept updated as the codebase evolves. AI agents should reference this for understanding project conventions and patterns._
