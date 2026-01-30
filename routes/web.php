<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/drive/{drive}/preview', [HomeController::class, 'drivePreview'])->name('drive.preview');
Route::get('/api/statistics', [HomeController::class, 'statistics'])->name('api.statistics');

// NGO External Link Tracking
Route::get('/ngo/{ngoId}/donate', [\App\Http\Controllers\Ngo\DonationLinkController::class, 'trackClick'])
    ->name('ngo.external-link');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Google OAuth
    Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'callback']);
    Route::get('auth/google/role-select', [GoogleController::class, 'showRoleSelect'])->name('auth.google.role-select');
    Route::post('auth/google/role-select', [GoogleController::class, 'storeWithRole']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Drive Management
    Route::resource('drives', \App\Http\Controllers\Admin\DriveController::class);
    Route::patch('drives/{drive}/complete', [\App\Http\Controllers\Admin\DriveController::class, 'complete'])->name('drives.complete');
    Route::post('drives/{drive}/close', [\App\Http\Controllers\Admin\DriveController::class, 'close'])->name('drives.close');
    Route::post('drives/{drive}/recalculate', [\App\Http\Controllers\Admin\DriveController::class, 'recalculateProgress'])->name('drives.recalculate');
    Route::get('drives-map', [\App\Http\Controllers\Admin\DriveController::class, 'map'])->name('drives.map');

    // Pledge Management
    Route::get('pledges', [\App\Http\Controllers\Admin\PledgeController::class, 'index'])->name('pledges.index');
    Route::get('pledges/pending', [\App\Http\Controllers\Admin\PledgeController::class, 'pending'])->name('pledges.pending');
    Route::get('pledges/{pledge}', [\App\Http\Controllers\Admin\PledgeController::class, 'show'])->name('pledges.show');
    Route::post('pledges/{pledge}/verify', [\App\Http\Controllers\Admin\PledgeController::class, 'verify'])->name('pledges.verify');
    Route::post('pledges/{pledge}/distribute', [\App\Http\Controllers\Admin\PledgeController::class, 'distribute'])->name('pledges.distribute');
    Route::post('pledges/{pledge}/feedback', [\App\Http\Controllers\Admin\PledgeController::class, 'feedback'])->name('pledges.feedback');

    // NGO Verification
    Route::get('ngos/pending', [\App\Http\Controllers\Admin\NgoVerificationController::class, 'index'])->name('ngos.pending');
    Route::get('ngos/{user}', [\App\Http\Controllers\Admin\NgoVerificationController::class, 'show'])->name('ngos.show');
    Route::post('ngos/{user}/approve', [\App\Http\Controllers\Admin\NgoVerificationController::class, 'approve'])->name('ngos.approve');
    Route::post('ngos/{user}/reject', [\App\Http\Controllers\Admin\NgoVerificationController::class, 'reject'])->name('ngos.reject');
    Route::get('ngos/{user}/certificate', [\App\Http\Controllers\Admin\NgoVerificationController::class, 'downloadCertificate'])->name('ngos.certificate');

    // Reports
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/donations', [\App\Http\Controllers\Admin\ReportController::class, 'donationSummary'])->name('reports.donations');
    Route::get('reports/drives', [\App\Http\Controllers\Admin\ReportController::class, 'drivePerformance'])->name('reports.drives');
    Route::get('reports/donors', [\App\Http\Controllers\Admin\ReportController::class, 'donorStatistics'])->name('reports.donors');
});

// Donor Routes
Route::middleware(['auth', 'donor'])->prefix('donor')->name('donor.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Donor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('map', [\App\Http\Controllers\Donor\DashboardController::class, 'map'])->name('map');

    // Pledges
    Route::get('pledges', [\App\Http\Controllers\Donor\PledgeController::class, 'index'])->name('pledges.index');
    Route::get('pledges/create', [\App\Http\Controllers\Donor\PledgeController::class, 'create'])->name('pledges.create');
    Route::post('pledges', [\App\Http\Controllers\Donor\PledgeController::class, 'store'])->name('pledges.store');
    Route::get('pledges/{pledge}', [\App\Http\Controllers\Donor\PledgeController::class, 'show'])->name('pledges.show');

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Donor\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [\App\Http\Controllers\Donor\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\Donor\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// NGO Routes
Route::middleware(['auth', 'ngo'])->prefix('ngo')->name('ngo.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Ngo\DashboardController::class, 'index'])->name('dashboard');
    Route::get('map', [\App\Http\Controllers\Ngo\DashboardController::class, 'map'])->name('map');

    // Donation Link
    Route::get('donation-link', [\App\Http\Controllers\Ngo\DonationLinkController::class, 'index'])->name('donation-link.index');
    Route::put('donation-link', [\App\Http\Controllers\Ngo\DonationLinkController::class, 'update'])->name('donation-link.update');

    // Drive Support (verified NGOs only)
    Route::middleware('verified.ngo')->group(function () {
        Route::post('drives/{drive}/support', [\App\Http\Controllers\Ngo\DriveSupportController::class, 'toggle'])->name('drives.support');
        Route::get('supports', [\App\Http\Controllers\Ngo\DriveSupportController::class, 'index'])->name('supports.index');
    });

    // Pledges (same as donor, but for NGO)
    Route::middleware('verified.ngo')->group(function () {
        Route::get('pledges', [\App\Http\Controllers\Ngo\PledgeController::class, 'index'])->name('pledges.index');
        Route::get('pledges/create', [\App\Http\Controllers\Ngo\PledgeController::class, 'create'])->name('pledges.create');
        Route::post('pledges', [\App\Http\Controllers\Ngo\PledgeController::class, 'store'])->name('pledges.store');
        Route::get('pledges/{pledge}', [\App\Http\Controllers\Ngo\PledgeController::class, 'show'])->name('pledges.show');
    });

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Donor\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [\App\Http\Controllers\Donor\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\Donor\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
