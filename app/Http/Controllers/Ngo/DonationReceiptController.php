<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\DonationReceipt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonationReceiptController extends Controller
{
    /**
     * List all receipts submitted to the authenticated NGO.
     */
    public function index(Request $request): View
    {
        /** @var User $ngo */
        $ngo = Auth::user();

        $query = $ngo->receivedReceipts()->with('user')->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $receipts = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => $ngo->receivedReceipts()->count(),
            'pending' => $ngo->receivedReceipts()->where('status', 'pending')->count(),
            'verified' => $ngo->receivedReceipts()->where('status', 'verified')->count(),
            'rejected' => $ngo->receivedReceipts()->where('status', 'rejected')->count(),
        ];

        return view('ngo.receipts.index', compact('receipts', 'counts'));
    }

    /**
     * Show a single receipt with lightbox for the image.
     */
    public function show(DonationReceipt $receipt): View
    {
        // Ensure the NGO owns this receipt
        if ($receipt->ngo_id !== Auth::id()) {
            abort(403);
        }

        $receipt->load('user');

        return view('ngo.receipts.show', compact('receipt'));
    }

    /**
     * Verify (approve) a donation receipt.
     */
    public function verify(DonationReceipt $receipt): RedirectResponse
    {
        if ($receipt->ngo_id !== Auth::id()) {
            abort(403);
        }

        if ($receipt->status !== DonationReceipt::STATUS_PENDING) {
            return back()->with('warning', 'This receipt has already been processed.');
        }

        $receipt->update([
            'status' => DonationReceipt::STATUS_VERIFIED,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Donation receipt has been verified.');
    }

    /**
     * Reject a donation receipt.
     */
    public function reject(Request $request, DonationReceipt $receipt): RedirectResponse
    {
        if ($receipt->ngo_id !== Auth::id()) {
            abort(403);
        }

        if ($receipt->status !== DonationReceipt::STATUS_PENDING) {
            return back()->with('warning', 'This receipt has already been processed.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $receipt->update([
            'status' => DonationReceipt::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Donation receipt has been rejected.');
    }
}
