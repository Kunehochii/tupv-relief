<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\NgoDriveSupport;
use App\Models\Pledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        $stats = [
            'total_pledges' => $user->pledges()->count(),
            'verified_count' => $user->pledges()->where('status', Pledge::STATUS_VERIFIED)->count(),
            'pending_count' => $user->pledges()->where('status', Pledge::STATUS_PENDING)->count(),
            'distributed_count' => $user->pledges()->where('status', Pledge::STATUS_DISTRIBUTED)->count(),
            'families_helped' => $user->pledges()->sum('families_helped'),
            'relief_packages' => $user->pledges()->sum('relief_packages'),
            'drives_participated' => $user->pledges()->distinct('drive_id')->count('drive_id'),
            'drives_supported' => $user->driveSupports()->where('is_active', true)->count(),
        ];

        // NGOs can see exact item quantities
        $activeDrives = Drive::active()
            ->with(['creator', 'driveItems'])
            ->latest()
            ->paginate(10);

        // Get IDs of drives this NGO supports
        $supportedDriveIds = $user->driveSupports()
            ->where('is_active', true)
            ->pluck('drive_id')
            ->toArray();

        $linkClicks = $user->linkClicks()->count();

        return view('ngo.dashboard', compact('stats', 'activeDrives', 'linkClicks', 'supportedDriveIds'));
    }

    public function map(): View
    {
        $drives = Drive::active()
            ->with('driveItems')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('ngo.map', compact('drives'));
    }
}
