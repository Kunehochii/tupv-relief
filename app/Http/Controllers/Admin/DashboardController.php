<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\Pledge;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_drives' => Drive::count(),
            'active_drives' => Drive::active()->count(),
            'total_donations' => Pledge::count(),
            'pending_verifications' => Pledge::pending()->count(),
            'pending_ngos' => User::where('role', User::ROLE_NGO)
                ->where('verification_status', User::STATUS_PENDING)
                ->count(),
        ];

        $activeDrives = Drive::active()
            ->with('creator')
            ->latest()
            ->take(5)
            ->get();

        $pendingPledges = Pledge::pending()
            ->with(['user', 'drive'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('metrics', 'activeDrives', 'pendingPledges'));
    }
}
