<?php

namespace App\Http\Controllers\Donor;

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
            'items_distributed' => $user->pledges()->sum('items_distributed'),
        ];

        $activeDrives = Drive::active()
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('donor.dashboard', compact('stats', 'activeDrives'));
    }

    public function map(): View
    {
        $drives = Drive::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('donor.map', compact('drives'));
    }
}
