<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\Pledge;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $stats = [
            'total_pledges' => $user->pledges()->count(),
            'verified_count' => $user->pledges()->where('status', Pledge::STATUS_VERIFIED)->count(),
            'pending_count' => $user->pledges()->where('status', Pledge::STATUS_PENDING)->count(),
            'distributed_count' => $user->pledges()->where('status', Pledge::STATUS_DISTRIBUTED)->count(),
            'families_helped' => $user->pledges()->sum('families_helped'),
            'items_distributed' => $user->pledges()->sum('items_distributed'),
        ];

        // Get initial drives for carousel (featured / hero)
        $activeDrives = Drive::active()
            ->with(['creator', 'driveItems'])
            ->latest()
            ->take(10)
            ->get();

        // Ending soon – active drives ordered by end_date ascending (soonest first)
        $endingSoonDrives = Drive::active()
            ->with(['creator', 'driveItems'])
            ->orderBy('end_date', 'asc')
            ->take(10)
            ->get();

        // Recently added – newest first
        $recentDrives = Drive::active()
            ->with(['creator', 'driveItems'])
            ->latest()
            ->take(10)
            ->get();

        // Most needed – lowest progress percentage (most help needed)
        $mostNeededDrives = Drive::active()
            ->with(['creator', 'driveItems'])
            ->get()
            ->sortBy('progress_percentage')
            ->take(10)
            ->values();

        // Get completed drives for the completed section
        $completedDrives = Drive::completed()
            ->with(['creator', 'driveItems'])
            ->latest()
            ->take(10)
            ->get();

        return view('donor.dashboard', compact(
            'stats',
            'activeDrives',
            'endingSoonDrives',
            'recentDrives',
            'mostNeededDrives',
            'completedDrives',
        ));
    }

    /**
     * Fetch more drives for infinite carousel
     */
    public function fetchDrives(Request $request): JsonResponse
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 5);

        $drives = Drive::active()
            ->with(['creator', 'driveItems', 'supportingNgos'])
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($drive) {
                return [
                    'id' => $drive->id,
                    'name' => $drive->name,
                    'description' => $drive->description,
                    'cover_photo_url' => $drive->cover_photo_url ?? null,
                    'latitude' => $drive->latitude,
                    'longitude' => $drive->longitude,
                    'address' => $drive->address,
                    'progress_percentage' => $drive->progress_percentage,
                    'pledge_url' => route('donor.pledges.create', ['drive' => $drive->id]),
                    'preview_url' => route('drive.preview', $drive),
                ];
            });

        $hasMore = Drive::active()->count() > ($offset + $limit);

        return response()->json([
            'drives' => $drives,
            'hasMore' => $hasMore,
        ]);
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
