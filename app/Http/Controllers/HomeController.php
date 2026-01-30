<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Models\Pledge;
use Illuminate\Http\JsonResponse;
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

    public function about(): View
    {
        return view('public.about');
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
        // Total relief packs and items distributed (from verified/distributed pledges)
        $reliefPacksDistributed = Pledge::where('status', Pledge::STATUS_DISTRIBUTED)
            ->sum('relief_packages') ?? 0;

        $itemsDistributed = Pledge::where('status', Pledge::STATUS_DISTRIBUTED)
            ->sum('items_distributed') ?? 0;

        $totalDistributed = $reliefPacksDistributed + $itemsDistributed;

        // Total donation drives created
        $totalDrives = Drive::count();

        // Total pledges made and verified
        $totalPledges = Pledge::whereIn('status', [
            Pledge::STATUS_VERIFIED,
            Pledge::STATUS_DISTRIBUTED
        ])->count();

        return [
            'relief_distributed' => $totalDistributed > 0 ? $totalDistributed : 1000,
            'drives_created' => $totalDrives > 0 ? $totalDrives : 13252,
            'pledges_verified' => $totalPledges > 0 ? $totalPledges : 4568,
        ];
    }
}
