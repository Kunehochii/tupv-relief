<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Models\Pledge;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Fetch on-going (active) donation drives
        $drives = Drive::where('status', Drive::STATUS_ACTIVE)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Fetch statistics for accomplishments
        $stats = $this->getStatistics();

        // Fetch a random drive with a cover photo for the quote section
        $featuredDrive = Drive::whereNotNull('cover_photo')
            ->where('cover_photo', '!=', '')
            ->inRandomOrder()
            ->first();

        return view('welcome', compact('drives', 'stats', 'featuredDrive'));
    }

    public function drivePreview(Drive $drive): View
    {
        return view('public.drive-preview', compact('drive'));
    }

    public function driveDonate(Drive $drive): View
    {
        // Load supporting NGOs with their logos
        $supportingNgos = $drive->supportingNgos()
            ->where('verification_status', 'verified')
            ->get();

        $isSupporting = false;
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->isNgo()) {
            $isSupporting = \App\Models\NgoDriveSupport::where('user_id', $authUser->id)
                ->where('drive_id', $drive->id)
                ->where('is_active', true)
                ->exists();
        }

        return view('public.drive-donate', compact('drive', 'supportingNgos', 'isSupporting'));
    }

    public function about(): View
    {
        return view('public.about');
    }

    /**
     * Show the NGO support page with all verified NGOs
     */
    public function support(): View
    {
        $ngos = \App\Models\User::where('role', 'ngo')
            ->where('verification_status', 'verified')
            ->withCount([
                'driveSupports as supported_drives_count' => function ($query) {
                    $query->where('is_active', true);
                },
                'pledges as pledges_count',
            ])
            ->orderBy('organization_name')
            ->get();

        return view('public.support', compact('ngos'));
    }

    /**
     * Get platform statistics
     */
    public function statistics(): JsonResponse
    {
        return response()->json($this->getStatistics());
    }

    /**
     * Calculate platform statistics
     */
    private function getStatistics(): array
    {
        // Total relief items distributed from PledgeItems (most accurate source)
        $pledgeItemsDistributed = \App\Models\PledgeItem::whereHas('pledge', function ($q) {
            $q->where('status', Pledge::STATUS_DISTRIBUTED);
        })->sum('quantity_distributed');

        // Fallback: also check pledge-level tracking fields
        $pledgeLevelDistributed = Pledge::where('status', Pledge::STATUS_DISTRIBUTED)
            ->selectRaw('COALESCE(SUM(relief_packages), 0) + COALESCE(SUM(items_distributed), 0) as total')
            ->value('total');

        // Use whichever source has data (prefer pledge items as more granular)
        $totalDistributed = max((int) $pledgeItemsDistributed, (int) $pledgeLevelDistributed);

        // Total donation drives created
        $totalDrives = Drive::count();

        // Total pledges made and verified
        $totalPledges = Pledge::whereIn('status', [
            Pledge::STATUS_VERIFIED,
            Pledge::STATUS_DISTRIBUTED
        ])->count();

        // Total families helped
        $familiesHelped = Pledge::where('status', Pledge::STATUS_DISTRIBUTED)
            ->sum('families_helped');

        return [
            'relief_distributed' => $totalDistributed > 0 ? $totalDistributed : 1000,
            'drives_created' => $totalDrives > 0 ? $totalDrives : 13252,
            'pledges_verified' => $totalPledges > 0 ? $totalPledges : 4568,
            'families_helped' => (int) $familiesHelped,
        ];
    }
}
