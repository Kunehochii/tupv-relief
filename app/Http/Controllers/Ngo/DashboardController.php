<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Drive;
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
        ];

        $activeDrives = Drive::active()
            ->with('creator')
            ->latest()
            ->paginate(10);

        $linkClicks = $user->linkClicks()->count();

        return view('ngo.dashboard', compact('stats', 'activeDrives', 'linkClicks'));
    }

    public function map(): View
    {
        $drives = Drive::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('ngo.map', compact('drives'));
    }
}
