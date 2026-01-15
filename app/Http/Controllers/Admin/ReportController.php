<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\Pledge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index');
    }

    public function donationSummary(Request $request): View
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $pledges = Pledge::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'drive'])
            ->get();

        $summary = [
            'total_pledges' => $pledges->count(),
            'verified_pledges' => $pledges->where('status', Pledge::STATUS_VERIFIED)->count(),
            'distributed_pledges' => $pledges->where('status', Pledge::STATUS_DISTRIBUTED)->count(),
            'expired_pledges' => $pledges->where('status', Pledge::STATUS_EXPIRED)->count(),
            'total_families_helped' => $pledges->sum('families_helped'),
            'total_relief_packages' => $pledges->sum('relief_packages'),
        ];

        return view('admin.reports.donation-summary', compact('pledges', 'summary', 'startDate', 'endDate'));
    }

    public function drivePerformance(): View
    {
        $drives = Drive::withCount(['pledges', 'pledges as verified_pledges_count' => function ($query) {
            $query->where('status', Pledge::STATUS_VERIFIED);
        }])
        ->get();

        return view('admin.reports.drive-performance', compact('drives'));
    }

    public function donorStatistics(): View
    {
        $topDonors = User::whereIn('role', [User::ROLE_DONOR, User::ROLE_NGO])
            ->withCount('pledges')
            ->orderByDesc('pledges_count')
            ->take(20)
            ->get();

        $stats = [
            'total_donors' => User::where('role', User::ROLE_DONOR)->count(),
            'total_ngos' => User::where('role', User::ROLE_NGO)->count(),
            'active_donors' => User::whereHas('pledges', function ($query) {
                $query->where('created_at', '>=', now()->subMonth());
            })->count(),
        ];

        return view('admin.reports.donor-statistics', compact('topDonors', 'stats'));
    }
}
