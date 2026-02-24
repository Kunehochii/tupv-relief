<?php

namespace App\Http\Controllers;

use App\Models\DonationReceipt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonationReceiptUploadController extends Controller
{
    /**
     * Show the upload receipt form for a specific NGO.
     */
    public function create(int $ngoId): View
    {
        $ngo = User::where('id', $ngoId)
            ->where('role', User::ROLE_NGO)
            ->where('verification_status', User::STATUS_VERIFIED)
            ->firstOrFail();

        // Cannot upload receipt to your own profile
        if (Auth::id() === $ngo->id) {
            abort(403, 'You cannot upload a receipt to your own profile.');
        }

        return view('receipts.create', compact('ngo'));
    }

    /**
     * Store the uploaded receipt.
     */
    public function store(Request $request, int $ngoId): RedirectResponse
    {
        $ngo = User::where('id', $ngoId)
            ->where('role', User::ROLE_NGO)
            ->where('verification_status', User::STATUS_VERIFIED)
            ->firstOrFail();

        // Cannot upload receipt to your own profile
        if (Auth::id() === $ngo->id) {
            abort(403, 'You cannot upload a receipt to your own profile.');
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'message' => ['nullable', 'string', 'max:1000'],
            'receipt_image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $receiptPath = $request->file('receipt_image')->store('donation-receipts', 'public');

        DonationReceipt::create([
            'ngo_id' => $ngo->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'message' => $request->message,
            'receipt_path' => $receiptPath,
            'status' => DonationReceipt::STATUS_PENDING,
        ]);

        return redirect()->route('ngo.profile.public', $ngo->id)
            ->with('success', 'Your donation receipt has been submitted successfully! The NGO will review it shortly.');
    }
}
