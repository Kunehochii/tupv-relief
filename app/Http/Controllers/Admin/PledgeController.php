<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pledge;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PledgeController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(): View
    {
        $pledges = Pledge::with(['user', 'drive'])
            ->latest()
            ->paginate(20);

        return view('admin.pledges.index', compact('pledges'));
    }

    public function pending(): View
    {
        $pledges = Pledge::pending()
            ->with(['user', 'drive'])
            ->latest()
            ->paginate(20);

        return view('admin.pledges.pending', compact('pledges'));
    }

    public function show(Pledge $pledge): View
    {
        $pledge->load(['user', 'drive', 'verifier']);
        
        return view('admin.pledges.show', compact('pledge'));
    }

    public function verify(Pledge $pledge): RedirectResponse
    {
        $pledge->update([
            'status' => Pledge::STATUS_VERIFIED,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        $this->notificationService->sendPledgeVerified($pledge);

        return redirect()->route('admin.pledges.pending')
            ->with('success', 'Pledge verified successfully.');
    }

    public function distribute(Pledge $pledge): RedirectResponse
    {
        $pledge->update([
            'status' => Pledge::STATUS_DISTRIBUTED,
            'distributed_at' => now(),
        ]);

        $this->notificationService->sendDonationDistributed($pledge);

        return redirect()->back()
            ->with('success', 'Pledge marked as distributed.');
    }

    public function feedback(Request $request, Pledge $pledge): RedirectResponse
    {
        $validated = $request->validate([
            'families_helped' => ['nullable', 'integer', 'min:0'],
            'relief_packages' => ['nullable', 'integer', 'min:0'],
            'items_distributed' => ['nullable', 'integer', 'min:0'],
            'admin_feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $pledge->update($validated);

        $this->notificationService->sendImpactFeedback($pledge);

        return redirect()->back()
            ->with('success', 'Feedback saved successfully.');
    }
}
