<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get initial drives for carousel
        $activeDrives = Drive::active()
            ->with(['creator', 'driveItems', 'supportingNgos'])
            ->latest()
            ->take(10)
            ->get();

        return view('donor.dashboard', compact('activeDrives'));
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
                    'donate_url' => route('drive.donate', $drive),
                    'detail_url' => route('donor.drives.show', $drive),
                ];
            });

        $hasMore = Drive::active()->count() > ($offset + $limit);

        return response()->json([
            'drives' => $drives,
            'hasMore' => $hasMore,
        ]);
    }

    /**
     * Show drive detail page (intermediate between dashboard and pledge/donate)
     */
    public function showDrive(Drive $drive): View|\Illuminate\Http\RedirectResponse
    {
        if (!$drive->isActive()) {
            return redirect()->route('donor.dashboard')
                ->with('warning', 'This drive is no longer active.');
        }

        $drive->load(['driveItems', 'supportingNgos', 'photos']);

        return view('donor.drives.show', compact('drive'));
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
