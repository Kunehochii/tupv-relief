<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pledge;
use App\Models\PledgeItem;
use App\Models\ReliefPackItem;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PledgeController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(Request $request): View
    {
        $query = Pledge::with(['user', 'drive', 'pledgeItems']);

        if ($request->filled('drive_id')) {
            $query->where('drive_id', $request->drive_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pledges = $query->latest()->paginate(20)->appends($request->query());

        $drives = \App\Models\Drive::orderBy('name')->get();

        return view('admin.pledges.index', compact('pledges', 'drives'));
    }

    public function pending(): View
    {
        $pledges = Pledge::pending()
            ->with(['user', 'drive', 'pledgeItems'])
            ->latest()
            ->paginate(20);

        return view('admin.pledges.pending', compact('pledges'));
    }

    public function show(Pledge $pledge): View
    {
        $pledge->load(['user', 'drive', 'verifier', 'pledgeItems.driveItem']);

        return view('admin.pledges.show', compact('pledge'));
    }

    public function verify(Pledge $pledge): RedirectResponse
    {
        DB::transaction(function () use ($pledge) {
            $pledge->update([
                'status' => Pledge::STATUS_VERIFIED,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Update drive items quantity_pledged
            foreach ($pledge->pledgeItems as $pledgeItem) {
                if ($pledgeItem->driveItem) {
                    $pledgeItem->driveItem->increment('quantity_pledged', $pledgeItem->quantity);
                }
            }

            // Update drive pledged_amount
            $pledge->drive->increment('pledged_amount', $pledge->total_quantity);
        });

        $this->notificationService->sendPledgeVerified($pledge);

        return redirect()->route('admin.pledges.pending')
            ->with('success', 'Pledge verified successfully.');
    }

    public function distribute(Request $request, Pledge $pledge): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:pledge_items,id'],
            'items.*.quantity_distributed' => ['required', 'numeric', 'min:0'],
            'admin_feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $pledge) {
            $totalDistributed = 0;
            $totalFamiliesHelped = 0;

            // Save admin feedback if provided
            if (isset($validated['admin_feedback'])) {
                $pledge->update(['admin_feedback' => $validated['admin_feedback']]);
            }

            foreach ($validated['items'] as $itemData) {
                $pledgeItem = PledgeItem::find($itemData['id']);

                if ($pledgeItem && $pledgeItem->pledge_id === $pledge->id) {
                    $quantityDistributed = min($itemData['quantity_distributed'], $pledgeItem->remaining_to_distribute);

                    // Calculate families helped using mother formula
                    $reliefItem = ReliefPackItem::where('item_name', $pledgeItem->item_name)
                        ->where('unit', $pledgeItem->unit)
                        ->first();

                    $familiesHelped = $reliefItem
                        ? $reliefItem->calculateFamiliesHelped($quantityDistributed)
                        : 0;

                    $pledgeItem->update([
                        'quantity_distributed' => $pledgeItem->quantity_distributed + $quantityDistributed,
                        'families_helped' => $pledgeItem->families_helped + $familiesHelped,
                        'distributed_at' => now(),
                    ]);

                    // Update drive item
                    if ($pledgeItem->driveItem) {
                        $pledgeItem->driveItem->increment('quantity_distributed', $quantityDistributed);
                    }

                    $totalDistributed += $quantityDistributed;
                    $totalFamiliesHelped += $familiesHelped;

                    // Send item-level notification (system only, no email)
                    $this->notificationService->notifyItemDistributed($pledge->user, $pledgeItem);
                }
            }

            // Update drive distributed_amount
            $pledge->drive->increment('distributed_amount', $totalDistributed);

            // Update pledge if fully distributed
            if ($pledge->fresh()->isFullyDistributed()) {
                $pledge->update([
                    'status' => Pledge::STATUS_DISTRIBUTED,
                    'distributed_at' => now(),
                    'families_helped' => $pledge->total_families_helped,
                ]);

                // Send distribution notification with email
                $this->notificationService->sendDonationDistributed($pledge);
            }
        });

        return redirect()->back()
            ->with('success', 'Distribution recorded successfully.');
    }

    public function feedback(Request $request, Pledge $pledge): RedirectResponse
    {
        $validated = $request->validate([
            'admin_feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $pledge->update($validated);

        return redirect()->back()
            ->with('success', 'Feedback saved successfully.');
    }

    public function reject(Request $request, Pledge $pledge): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($pledge, $validated) {
            $pledge->update([
                'status' => Pledge::STATUS_EXPIRED,
                'expired_at' => now(),
                'admin_feedback' => $validated['rejection_reason'],
            ]);
        });

        $this->notificationService->sendPledgeRejected($pledge, $validated['rejection_reason']);

        return redirect()->route('admin.pledges.pending')
            ->with('success', 'Pledge rejected successfully.');
    }
}
