<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\DriveItem;
use App\Models\ReliefPackItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DriveController extends Controller
{
    public function index(Request $request): View
    {
        $query = Drive::with('creator')->withCount('pledges');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drives = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.drives.index', compact('drives'));
    }

    public function create(): View
    {
        $packTypes = ReliefPackItem::PACK_TYPES;
        $reliefItems = ReliefPackItem::getAllGroupedByType();

        return view('admin.drives.create', compact('packTypes', 'reliefItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max
            'target_amount' => ['required', 'numeric', 'min:0'],
            'collected_amount' => ['nullable', 'numeric', 'min:0'],
            'target_type' => ['required', 'in:financial,quantity'],
            'pack_types' => ['required', 'array', 'min:1'],
            'pack_types.*' => ['string', 'in:food,kitchen,hygiene,sleeping,clothing'],
            'families_affected' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['required', 'date', 'after:today'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
            'custom_items' => ['nullable', 'array'],
            'custom_items.*.name' => ['required_with:custom_items', 'string', 'max:255'],
            'custom_items.*.quantity' => ['required_with:custom_items', 'numeric', 'min:0'],
            'custom_items.*.unit' => ['required_with:custom_items', 'string', 'max:50'],
        ]);

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $request->file('cover_photo')->store('drive-covers', 'public');
        }

        // Map pack_types to pack_types_needed (consistent with update method)
        $validated['pack_types_needed'] = $validated['pack_types'];
        unset($validated['pack_types']);

        $validated['created_by'] = Auth::id();
        $validated['status'] = Drive::STATUS_ACTIVE;
        $validated['collected_amount'] = $validated['collected_amount'] ?? 0;
        $validated['start_date'] = $validated['start_date'] ?? now();

        // Remove custom_items from validated data before creating drive
        $customItems = $validated['custom_items'] ?? [];
        unset($validated['custom_items']);

        $drive = Drive::create($validated);

        // Generate items from families affected if provided
        if ($drive->families_affected && !empty($drive->pack_types_needed)) {
            $drive->generateItemsFromFamilies();
        }

        // Add custom items
        foreach ($customItems as $item) {
            if (!empty($item['name']) && !empty($item['quantity']) && !empty($item['unit'])) {
                $drive->driveItems()->create([
                    'item_name' => $item['name'],
                    'quantity_needed' => $item['quantity'],
                    'unit' => $item['unit'],
                    'is_custom' => true,
                ]);
            }
        }

        return redirect()->route('admin.drives.index')
            ->with('success', 'Drive created successfully.');
    }

    public function show(Drive $drive): View
    {
        $drive->load(['creator', 'pledges.user', 'pledges.pledgeItems', 'driveItems', 'supportingNgos']);

        return view('admin.drives.show', compact('drive'));
    }

    public function edit(Drive $drive): View
    {
        $drive->load('driveItems');
        $packTypes = ReliefPackItem::PACK_TYPES;

        return view('admin.drives.edit', compact('drive', 'packTypes'));
    }

    public function update(Request $request, Drive $drive): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'collected_amount' => ['nullable', 'numeric', 'min:0'],
            'target_type' => ['required', 'in:financial,quantity'],
            'pack_types' => ['required', 'array', 'min:1'],
            'pack_types.*' => ['string'],
            'families_affected' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,completed,closed'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($drive->cover_photo) {
                Storage::disk('public')->delete($drive->cover_photo);
            }
            $validated['cover_photo'] = $request->file('cover_photo')->store('drive-covers', 'public');
        }

        // Check if pack types or families affected changed
        $shouldRegenerateItems =
            $drive->families_affected !== $validated['families_affected'] ||
            $drive->pack_types_needed !== $validated['pack_types'];

        // Map pack_types to pack_types_needed
        $validated['pack_types_needed'] = $validated['pack_types'];
        unset($validated['pack_types']);

        $drive->update($validated);

        // Regenerate items if families affected or pack types changed
        if ($shouldRegenerateItems) {
            $drive->generateItemsFromFamilies();
        }

        return redirect()->route('admin.drives.index')
            ->with('success', 'Drive updated successfully.');
    }

    public function complete(Drive $drive): RedirectResponse
    {
        $drive->update(['status' => Drive::STATUS_COMPLETED]);

        return redirect()->route('admin.drives.show', $drive)
            ->with('success', 'Drive marked as completed successfully.');
    }

    public function close(Drive $drive): RedirectResponse
    {
        $drive->update(['status' => Drive::STATUS_CLOSED]);

        return redirect()->route('admin.drives.index')
            ->with('success', 'Drive closed successfully.');
    }

    public function map(): View
    {
        $drives = Drive::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('admin.drives.map', compact('drives'));
    }

    /**
     * Recalculate drive progress from pledges
     */
    public function recalculateProgress(Drive $drive): RedirectResponse
    {
        $drive->recalculateProgress();

        return redirect()->back()
            ->with('success', 'Drive progress recalculated successfully.');
    }
}
