# TABANG - Implementation Change Plan (February 3, 2026)

> Step-by-step implementation guide for the February 2026 updates

---

## Overview

This document outlines the database migrations, model changes, controller updates, and view modifications required to implement the new TABANG features based on client feedback.

**Key Changes:**
1. Donor pledges as whole numbers only (increments of 1)
2. Email OTP verification for new user registration
3. Gray out NGO support buttons for unverified NGOs
4. Visual feedback when NGO supports a drive
5. UI/Text changes (navbar, landing page, about page)
6. NGO registration image upload (replacing URL)
7. NGO Drive Support opt-out feature
8. Remove financial donations (in-kind only)
9. Notify donors when NGO pledges items to their drive

---

## Table of Contents

1. [Database Changes](#1-database-changes)
2. [Model Updates](#2-model-updates)
3. [Middleware Updates](#3-middleware-updates)
4. [Controller Changes](#4-controller-changes)
5. [View Updates](#5-view-updates)
6. [Service Updates](#6-service-updates)
7. [Route Changes](#7-route-changes)
8. [Testing Checklist](#8-testing-checklist)

---

## 1. Database Changes

### 1.1 Create OTP Verification Table

Create migration: `2026_02_03_000001_create_otp_verifications_table.php`

```php
Schema::create('otp_verifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('otp', 6);
    $table->timestamp('expires_at');
    $table->timestamp('verified_at')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'otp']);
});
```

**Purpose:** Store OTP codes for email verification with expiration tracking.

---

### 1.2 Add Email Verified Flag to Users

Create migration: `2026_02_03_000002_add_otp_verified_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('otp_verified')->default(false)->after('email_verified_at');
});
```

**Purpose:** Track whether user has completed OTP verification.

---

### 1.3 Add Logo Path to Users (Replace logo_url)

Create migration: `2026_02_03_000003_add_logo_path_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('logo_path')->nullable()->after('logo_url');
    // We keep logo_url for backward compatibility, but new registrations use logo_path
});
```

**Purpose:** Store uploaded logo image path instead of external URL.

---

### 1.4 Remove Financial Target Type from Drives

Create migration: `2026_02_03_000004_remove_financial_from_drives.php`

```php
// Update existing financial drives to in-kind
DB::table('drives')
    ->where('target_type', 'financial')
    ->update(['target_type' => 'in-kind']);

// Note: Enum change requires raw SQL in MySQL
// ALTER TABLE drives MODIFY target_type ENUM('in-kind') DEFAULT 'in-kind';
```

**Purpose:** Remove financial donation support, keeping only in-kind pledges.

---

## 2. Model Updates

### 2.1 Create OtpVerification Model

Create model: `app/Models/OtpVerification.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Generate a new 6-digit OTP
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
```

---

### 2.2 Update User Model

Add to `app/Models/User.php`:

```php
// Add to $fillable array
'otp_verified',
'logo_path',

// Add relationship
public function otpVerifications()
{
    return $this->hasMany(OtpVerification::class);
}

// Add helper method
public function isOtpVerified(): bool
{
    return (bool) $this->otp_verified;
}

// Add logo accessor (prefer uploaded path over URL)
public function getLogoAttribute(): ?string
{
    if ($this->logo_path) {
        return asset('storage/' . $this->logo_path);
    }
    return $this->logo_url;
}
```

---

### 2.3 Update Pledge Model

Modify quantity validation to be whole numbers:

```php
// In any validation rules, change:
'quantity' => 'required|numeric|min:0.01'
// To:
'quantity' => 'required|integer|min:1'
```

---

### 2.4 Update Drive Model

Remove financial-related methods/scopes if any exist.

---

## 3. Middleware Updates

### 3.1 Create OTP Verification Middleware

Create middleware: `app/Http/Middleware/EnsureOtpVerified.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->isOtpVerified()) {
            // Allow access to OTP verification routes
            if ($request->routeIs('otp.*') || $request->routeIs('logout')) {
                return $next($request);
            }
            
            return redirect()->route('otp.verify')
                ->with('warning', 'Please verify your email to continue.');
        }

        return $next($request);
    }
}
```

### 3.2 Register Middleware

In `bootstrap/app.php`, add middleware alias:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        // ... existing aliases
        'otp.verified' => \App\Http\Middleware\EnsureOtpVerified::class,
    ]);
})
```

---

## 4. Controller Changes

### 4.1 Create OTP Controller

Create controller: `app/Http/Controllers/Auth/OtpController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    /**
     * Show OTP verification form
     */
    public function show()
    {
        return view('auth.otp-verify');
    }

    /**
     * Send OTP to user's email
     */
    public function send(Request $request)
    {
        $user = auth()->user();
        
        // Invalidate previous OTPs
        OtpVerification::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();
        
        // Generate new OTP
        $otp = OtpVerification::generateOtp();
        
        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), // 10 minute expiry
        ]);
        
        // Send email
        Mail::to($user->email)->send(new OtpMail($otp));
        
        return back()->with('success', 'Verification code sent to your email.');
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        
        $otpRecord = OtpVerification::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // Mark OTP as verified
        $otpRecord->update(['verified_at' => now()]);
        
        // Mark user as OTP verified
        $user->update(['otp_verified' => true]);

        // Redirect based on role
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Email verified successfully!'),
            'donor' => redirect()->route('donor.dashboard')->with('success', 'Email verified successfully!'),
            'ngo' => redirect()->route('ngo.dashboard')->with('success', 'Email verified successfully!'),
            default => redirect('/')->with('success', 'Email verified successfully!'),
        };
    }
}
```

---

### 4.2 Update Registration Controller

After user registration, trigger OTP send:

```php
// In RegisteredUserController@store, after creating user:
event(new Registered($user));

// Log user in
Auth::login($user);

// Send OTP
$otp = OtpVerification::generateOtp();
OtpVerification::create([
    'user_id' => $user->id,
    'otp' => $otp,
    'expires_at' => now()->addMinutes(10),
]);
Mail::to($user->email)->send(new OtpMail($otp));

return redirect()->route('otp.verify')
    ->with('success', 'Please check your email for the verification code.');
```

---

### 4.3 Update NGO Registration Controller

Handle image upload instead of URL:

```php
// In registration validation:
'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

// In store method:
if ($request->hasFile('organization_logo')) {
    $logoPath = $request->file('organization_logo')->store('ngo-logos', 'public');
    $user->logo_path = $logoPath;
    $user->save();
}
```

---

### 4.4 Update Pledge Controllers

Update quantity validation in Donor and NGO pledge controllers:

```php
// Change from:
'quantity' => 'required|numeric|min:0.01',

// To:
'quantity' => 'required|integer|min:1',
```

---

### 4.5 Create NGO Drive Support Controller

Create controller: `app/Http/Controllers/Ngo/DriveSupportController.php`

```php
<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\NgoDriveSupport;
use Illuminate\Http\Request;

class DriveSupportController extends Controller
{
    /**
     * List drives the NGO supports
     */
    public function index()
    {
        $supports = NgoDriveSupport::where('user_id', auth()->id())
            ->where('is_active', true)
            ->with('drive')
            ->latest()
            ->paginate(10);

        return view('ngo.drive-support.index', compact('supports'));
    }

    /**
     * Opt out from supporting a drive
     */
    public function optOut(NgoDriveSupport $support)
    {
        $this->authorize('update', $support);
        
        $support->update(['is_active' => false]);

        return back()->with('success', 'You have opted out from supporting this drive.');
    }
}
```

---

### 4.6 Update NGO Pledge Controller

Notify donors when NGO pledges to a drive:

```php
// In store method, after creating the pledge:
use App\Services\NotificationService;

// Get all donors who have pledged to this drive
$donorPledges = Pledge::where('drive_id', $drive->id)
    ->whereHas('user', fn($q) => $q->where('role', 'donor'))
    ->with('user')
    ->get();

$notificationService = app(NotificationService::class);

foreach ($donorPledges->unique('user_id') as $donorPledge) {
    $notificationService->notify(
        $donorPledge->user,
        'ngo_pledge_added',
        'NGO Support Added to Drive',
        auth()->user()->organization_name . ' has pledged items to "' . $drive->name . '" - a drive you\'re supporting!',
        route('donor.pledges.show', $donorPledge)
    );
}
```

---

## 5. View Updates

### 5.1 Create OTP Verification View

Create: `resources/views/auth/otp-verify.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4">Verify Your Email</h4>
                    
                    <p class="text-center text-muted mb-4">
                        Enter the 6-digit code sent to <strong>{{ auth()->user()->email }}</strong>
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" 
                                   name="otp" 
                                   class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                                   placeholder="000000"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   required
                                   autofocus>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Verify
                        </button>
                    </form>

                    <form method="POST" action="{{ route('otp.send') }}" class="text-center">
                        @csrf
                        <button type="submit" class="btn btn-link">
                            Resend verification code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

### 5.2 Create OTP Email Template

Create: `resources/views/emails/otp.blade.php`

```blade
@component('mail::message')
# Email Verification Code

Your verification code is:

@component('mail::panel')
<h1 style="text-align: center; font-size: 32px; letter-spacing: 8px;">{{ $otp }}</h1>
@endcomponent

This code will expire in **10 minutes**.

If you did not request this code, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
```

---

### 5.3 Update Navbar - Change "Our Pledges" to "Your Pledges"

File: `resources/views/partials/navbar.blade.php`

```blade
{{-- Change --}}
<a href="{{ route('ngo.pledges.index') }}" class="nav-link-custom">Our Pledges</a>
{{-- To --}}
<a href="{{ route('ngo.pledges.index') }}" class="nav-link-custom">Your Pledges</a>

{{-- Also in mobile nav --}}
<a href="{{ route('ngo.pledges.index') }}" class="nav-link-mobile">Your Pledges</a>
```

Add "Drive Support" link for NGO navbar:

```blade
{{-- After pledges link --}}
<a href="{{ route('ngo.drive-support.index') }}" class="nav-link-custom">Drive Support</a>
```

Remove "Contacts" link from navbar.

---

### 5.4 Update Landing Page

File: `resources/views/welcome.blade.php`

**Changes:**
1. Remove two greyed-out buttons
2. Keep "Donate" button
3. Add "Sign In" button
4. Update bottom card text
5. Update footer text

```blade
{{-- Hero Buttons - Replace existing --}}
<div class="hero-buttons">
    <a href="{{ route('login') }}" class="btn-outline-custom">Sign In</a>
    <a href="{{ route('register') }}" class="btn-primary-custom">Donate Now</a>
</div>

{{-- Bottom Card - Update text --}}
<div class="info-card">
    <p>
        TABANG brings donors, NGOs, and responders together in one transparent
        platform for disaster relief coordination. By showing verified needs and tracking
        donations and pledges, it helps ensure that every contribution is purposeful,
        accountable, and directed where it is needed most.
    </p>
</div>

{{-- Footer - Update text --}}
<footer>
    <p>
        This website was developed as an academic project in coordination with the
        Department of Social Welfare and Development (DSWD) to support disaster relief
        donation and distribution processes.
    </p>
</footer>
```

---

### 5.5 Update About Page

File: `resources/views/public/about.blade.php`

**Changes:**
1. Change any "BULIG" to "TABANG"
2. Add acronym explanation section

```blade
{{-- Add this section where appropriate --}}
<div class="acronym-section text-center py-4 bg-light rounded my-4">
    <h4 class="text-primary mb-3">
        <i class="bi bi-heart-fill text-danger"></i>
        What is TABANG?
    </h4>
    <p class="lead mb-0" style="color: var(--dark-blue);">
        <strong>T</strong>imely <strong>A</strong>ssistance <strong>B</strong>ringing 
        <strong>A</strong>id to <strong>N</strong>eedy <strong>G</strong>roups
    </p>
</div>
```

---

### 5.6 Update Drive Support Button Views

For NGO drive listing, gray out support button for unverified NGOs:

```blade
@if(auth()->user()->isVerified())
    {{-- Already supporting check --}}
    @if($drive->isSuportedByNgo(auth()->id()))
        <button class="btn btn-secondary" disabled>
            <i class="bi bi-check-circle"></i> Supporting
        </button>
    @else
        <form action="{{ route('ngo.drives.support', $drive) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-hand-thumbs-up"></i> Support Drive
            </button>
        </form>
    @endif
@else
    <button class="btn btn-secondary" disabled title="NGO verification required">
        <i class="bi bi-lock"></i> Support Drive
    </button>
    <small class="text-muted d-block mt-1">Verification required</small>
@endif
```

---

### 5.7 Update NGO Registration Form

File: `resources/views/auth/register.blade.php` (NGO section)

```blade
{{-- Replace logo URL input with file upload --}}
<div class="mb-3">
    <label for="organization_logo" class="form-label">Organization Logo</label>
    <input type="file" 
           class="form-control @error('organization_logo') is-invalid @enderror" 
           id="organization_logo" 
           name="organization_logo"
           accept="image/jpeg,image/png,image/jpg,image/gif">
    <div class="form-text">Upload your organization logo (JPEG, PNG, GIF - max 2MB)</div>
    @error('organization_logo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

Also add `enctype="multipart/form-data"` to the form tag.

---

### 5.8 Create NGO Drive Support Index View

Create: `resources/views/ngo/drive-support/index.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Drive Support')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Drives You Support</h2>

    @if($supports->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            You haven't supported any drives yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Drive Name</th>
                        <th>Status</th>
                        <th>End Date</th>
                        <th>Supported Since</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supports as $support)
                        <tr>
                            <td>
                                <a href="{{ route('drive.preview', $support->drive) }}">
                                    {{ $support->drive->name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-{{ $support->drive->status_color }}">
                                    {{ ucfirst($support->drive->status) }}
                                </span>
                            </td>
                            <td>{{ $support->drive->end_date->format('M d, Y') }}</td>
                            <td>{{ $support->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('ngo.drive-support.opt-out', $support) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to stop supporting this drive?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Opt Out
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $supports->links() }}
    @endif
</div>
@endsection
```

---

### 5.9 Remove Financial Drive Options

Update admin drive creation/edit forms:
- Remove financial target type option
- Remove collected_amount field for financial

Update drive listing views to not show financial-specific columns.

---

## 6. Service Updates

### 6.1 Update NotificationService

Add new notification type for NGO pledges:

```php
// Add constant to Notification model
const TYPE_NGO_PLEDGE_ADDED = 'ngo_pledge_added';

// In NotificationService, add method:
public function notifyDonorsOfNgoPledge(Pledge $ngoPledge): void
{
    $drive = $ngoPledge->drive;
    $ngo = $ngoPledge->user;
    
    // Get unique donors who pledged to this drive
    $donors = Pledge::where('drive_id', $drive->id)
        ->whereHas('user', fn($q) => $q->where('role', 'donor'))
        ->with('user')
        ->get()
        ->pluck('user')
        ->unique('id');

    foreach ($donors as $donor) {
        $this->notify(
            $donor,
            Notification::TYPE_NGO_PLEDGE_ADDED,
            'NGO Support for ' . $drive->name,
            $ngo->organization_name . ' has pledged items to support this drive!',
            route('donor.pledges.index')
        );
    }
}
```

---

## 7. Route Changes

### 7.1 Add OTP Routes

```php
// In routes/web.php, add after auth middleware group:

Route::middleware(['auth'])->group(function () {
    Route::get('/verify-email', [OtpController::class, 'show'])->name('otp.verify');
    Route::post('/verify-email', [OtpController::class, 'verify']);
    Route::post('/verify-email/resend', [OtpController::class, 'send'])->name('otp.send');
});
```

### 7.2 Add OTP Middleware to Protected Routes

```php
// Add 'otp.verified' middleware to all protected route groups:

Route::middleware(['auth', 'otp.verified', 'admin'])->prefix('admin')->group(function () {
    // Admin routes
});

Route::middleware(['auth', 'otp.verified', 'donor'])->prefix('donor')->group(function () {
    // Donor routes
});

Route::middleware(['auth', 'otp.verified', 'ngo'])->prefix('ngo')->group(function () {
    // NGO routes
});
```

### 7.3 Add NGO Drive Support Routes

```php
Route::middleware(['auth', 'otp.verified', 'ngo'])->prefix('ngo')->group(function () {
    // ... existing routes
    
    Route::get('/drive-support', [DriveSupportController::class, 'index'])
        ->name('ngo.drive-support.index');
    Route::delete('/drive-support/{support}', [DriveSupportController::class, 'optOut'])
        ->name('ngo.drive-support.opt-out');
});
```

---

## 8. Testing Checklist

### 8.1 OTP Verification
- [ ] User registration sends OTP email
- [ ] OTP verification form displays correctly
- [ ] Valid OTP verifies user and redirects to dashboard
- [ ] Expired OTP shows error
- [ ] Invalid OTP shows error
- [ ] Resend OTP works and invalidates previous codes
- [ ] Unverified users are redirected to OTP page from all protected routes

### 8.2 Pledge Quantity
- [ ] Donor pledge form only accepts whole numbers
- [ ] NGO pledge form only accepts whole numbers
- [ ] Decimal quantities are rejected with validation error

### 8.3 NGO Support Features
- [ ] Unverified NGO sees grayed-out support button
- [ ] Verified NGO can support drives
- [ ] Support button changes to "Supporting" after supporting
- [ ] Drive Support page shows all supported drives
- [ ] Opt-out button removes NGO from drive support
- [ ] Opt-out confirmation works

### 8.4 NGO Registration
- [ ] Logo file upload works
- [ ] Uploaded logo displays correctly
- [ ] Invalid file types are rejected
- [ ] File size limit enforced

### 8.5 UI/Text Changes
- [ ] Landing page shows "Sign In" and "Donate" buttons only
- [ ] Landing page bottom card has updated text
- [ ] Footer has updated text
- [ ] About page shows "TABANG" instead of "BULIG"
- [ ] About page shows acronym explanation
- [ ] Navbar shows "Your Pledges" for NGO
- [ ] Navbar shows "Drive Support" for NGO
- [ ] Contacts button removed from navbar

### 8.6 Notifications
- [ ] When NGO pledges to drive, donors of that drive receive notification
- [ ] Notification links work correctly

### 8.7 Financial Removal
- [ ] Admin cannot create financial drives
- [ ] Existing financial drives converted to in-kind
- [ ] No financial-related UI elements visible

---

## Migration Order

Run migrations in this order:

```bash
php artisan migrate
```

Files to create:
1. `2026_02_03_000001_create_otp_verifications_table.php`
2. `2026_02_03_000002_add_otp_verified_to_users_table.php`
3. `2026_02_03_000003_add_logo_path_to_users_table.php`
4. `2026_02_03_000004_remove_financial_from_drives.php`

---

## Files to Create

1. **Migration:** `database/migrations/2026_02_03_000001_create_otp_verifications_table.php`
2. **Migration:** `database/migrations/2026_02_03_000002_add_otp_verified_to_users_table.php`
3. **Migration:** `database/migrations/2026_02_03_000003_add_logo_path_to_users_table.php`
4. **Migration:** `database/migrations/2026_02_03_000004_remove_financial_from_drives.php`
5. **Model:** `app/Models/OtpVerification.php`
6. **Mail:** `app/Mail/OtpMail.php`
7. **Middleware:** `app/Http/Middleware/EnsureOtpVerified.php`
8. **Controller:** `app/Http/Controllers/Auth/OtpController.php`
9. **Controller:** `app/Http/Controllers/Ngo/DriveSupportController.php`
10. **View:** `resources/views/auth/otp-verify.blade.php`
11. **View:** `resources/views/emails/otp.blade.php`
12. **View:** `resources/views/ngo/drive-support/index.blade.php`

---

## Files to Modify

1. `app/Models/User.php` - Add OTP relationship and helpers
2. `app/Models/Notification.php` - Add new notification type
3. `app/Services/NotificationService.php` - Add NGO pledge notification
4. `app/Http/Controllers/Auth/RegisteredUserController.php` - Send OTP on registration
5. `app/Http/Controllers/Donor/PledgeController.php` - Integer quantity validation
6. `app/Http/Controllers/Ngo/PledgeController.php` - Integer quantity, notify donors
7. `bootstrap/app.php` - Register OTP middleware
8. `routes/web.php` - Add OTP routes, add drive support routes
9. `resources/views/partials/navbar.blade.php` - Update NGO nav items
10. `resources/views/welcome.blade.php` - Update buttons and text
11. `resources/views/public/about.blade.php` - TABANG acronym section
12. `resources/views/auth/register.blade.php` - Logo file upload

---

_This document should be used as the implementation guide. AI agents should reference this for understanding the specific changes required._
