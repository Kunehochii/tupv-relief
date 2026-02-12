<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\LinkClick;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $linkStats = [
            'total_clicks' => $user->linkClicks()->count(),
            'clicks_this_month' => $user->linkClicks()
                ->where('created_at', '>=', now()->startOfMonth())->count(),
            'clicks_this_week' => $user->linkClicks()
                ->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('ngo.profile.index', compact('linkStats'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'contact_numbers' => ['nullable', 'string', 'max:500'],
            'external_donation_url' => ['nullable', 'url', 'max:500'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'logo_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'qr_uploads' => ['nullable', 'array', 'max:5'],
            'qr_uploads.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'remove_qr' => ['nullable', 'array'],
            'remove_qr.*' => ['string'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Handle logo upload
        if ($request->hasFile('logo_upload')) {
            // Delete old logo if exists
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
            }
            $logoPath = $request->file('logo_upload')->store('ngo-logos', 'public');
            $user->logo_path = $logoPath;
            $user->logo_url = null; // Clear URL when uploading a file
        } elseif (!empty($validated['logo_url'])) {
            // If they provide a URL, clear the uploaded file
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
                $user->logo_path = null;
            }
            $user->logo_url = $validated['logo_url'];
        }

        // Handle QR channel removals
        $existingQrChannels = $user->qr_channels ?? [];
        if (!empty($validated['remove_qr'])) {
            foreach ($validated['remove_qr'] as $qrPath) {
                Storage::disk('public')->delete($qrPath);
                $existingQrChannels = array_filter($existingQrChannels, fn($path) => $path !== $qrPath);
            }
        }

        // Handle new QR uploads
        if ($request->hasFile('qr_uploads')) {
            foreach ($request->file('qr_uploads') as $qrFile) {
                $qrPath = $qrFile->store('ngo-qr-channels', 'public');
                $existingQrChannels[] = $qrPath;
            }
        }

        $user->qr_channels = array_values($existingQrChannels);
        $user->organization_name = $validated['organization_name'] ?? $user->organization_name;
        $user->bio = $validated['bio'] ?? null;
        $user->contact_numbers = $validated['contact_numbers'] ?? null;
        $user->external_donation_url = $validated['external_donation_url'] ?? null;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Public-facing NGO profile page.
     */
    public function show(int $ngoId): View
    {
        $ngo = User::where('id', $ngoId)
            ->where('role', User::ROLE_NGO)
            ->where('verification_status', User::STATUS_VERIFIED)
            ->firstOrFail();

        $supportedDrives = $ngo->supportedDrives()
            ->where('status', 'active')
            ->with('driveItems')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'drives_supported' => $ngo->driveSupports()->where('is_active', true)->count(),
            'total_pledges' => $ngo->pledges()->count(),
            'families_helped' => $ngo->pledges()->sum('families_helped'),
        ];

        return view('ngo.profile.public', compact('ngo', 'supportedDrives', 'stats'));
    }
}
