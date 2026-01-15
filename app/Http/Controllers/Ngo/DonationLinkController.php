<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\LinkClick;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonationLinkController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        $linkStats = [
            'total_clicks' => $user->linkClicks()->count(),
            'clicks_this_month' => $user->linkClicks()
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
            'clicks_this_week' => $user->linkClicks()
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
        ];

        return view('ngo.donation-link.index', compact('linkStats'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'external_donation_url' => ['required', 'url', 'max:500'],
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Donation link updated successfully.');
    }

    public function trackClick(Request $request, int $ngoId): RedirectResponse
    {
        $ngo = \App\Models\User::where('id', $ngoId)
            ->where('role', \App\Models\User::ROLE_NGO)
            ->firstOrFail();

        if (!$ngo->external_donation_url) {
            abort(404);
        }

        LinkClick::create([
            'user_id' => $ngoId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);

        return redirect()->away($ngo->external_donation_url);
    }
}
