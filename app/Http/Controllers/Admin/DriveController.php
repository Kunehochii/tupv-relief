<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DriveController extends Controller
{
    public function index(): View
    {
        $drives = Drive::with('creator')
            ->withCount('pledges')
            ->latest()
            ->paginate(15);

        return view('admin.drives.index', compact('drives'));
    }

    public function create(): View
    {
        return view('admin.drives.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'collected_amount' => ['nullable', 'numeric', 'min:0'],
            'target_type' => ['required', 'in:financial,quantity'],
            'items_needed' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['required', 'date', 'after:today'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert comma-separated items_needed string to array
        if (isset($validated['items_needed']) && !empty($validated['items_needed'])) {
            $validated['items_needed'] = array_map('trim', explode(',', $validated['items_needed']));
        } else {
            $validated['items_needed'] = null;
        }

        $validated['created_by'] = Auth::id();
        $validated['status'] = Drive::STATUS_ACTIVE;
        $validated['collected_amount'] = $validated['collected_amount'] ?? 0;
        $validated['start_date'] = $validated['start_date'] ?? now();

        Drive::create($validated);

        return redirect()->route('admin.drives.index')
            ->with('success', 'Drive created successfully.');
    }

    public function show(Drive $drive): View
    {
        $drive->load(['creator', 'pledges.user']);
        
        return view('admin.drives.show', compact('drive'));
    }

    public function edit(Drive $drive): View
    {
        return view('admin.drives.edit', compact('drive'));
    }

    public function update(Request $request, Drive $drive): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'collected_amount' => ['nullable', 'numeric', 'min:0'],
            'target_type' => ['required', 'in:financial,quantity'],
            'items_needed' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,completed,closed'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert comma-separated items_needed string to array
        if (isset($validated['items_needed']) && !empty($validated['items_needed'])) {
            $validated['items_needed'] = array_map('trim', explode(',', $validated['items_needed']));
        } else {
            $validated['items_needed'] = null;
        }

        $drive->update($validated);

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
}
