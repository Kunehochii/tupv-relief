<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NgoVerificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(): View
    {
        $ngos = User::where('role', User::ROLE_NGO)
            ->latest()
            ->paginate(15);

        return view('admin.ngos.pending', compact('ngos'));
    }

    public function show(User $user): View
    {
        if ($user->role !== User::ROLE_NGO) {
            abort(404);
        }

        return view('admin.ngos.show', compact('user'));
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_NGO) {
            abort(404);
        }

        $user->update([
            'verification_status' => User::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $this->notificationService->sendNgoVerified($user);

        return redirect()->route('admin.ngos.pending')
            ->with('success', 'NGO verified successfully.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_NGO) {
            abort(404);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $user->update([
            'verification_status' => User::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $this->notificationService->sendNgoRejected($user);

        return redirect()->route('admin.ngos.pending')
            ->with('success', 'NGO registration rejected.');
    }

    public function downloadCertificate(User $user)
    {
        if (!$user->certificate_path || !Storage::disk('public')->exists($user->certificate_path)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($user->certificate_path);

        return response()->download($path, basename($user->certificate_path));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_NGO) {
            abort(404);
        }

        // Delete certificate file if exists
        if ($user->certificate_path && Storage::disk('public')->exists($user->certificate_path)) {
            Storage::disk('public')->delete($user->certificate_path);
        }

        $organizationName = $user->organization_name ?? $user->name;
        $user->delete();

        return redirect()->route('admin.ngos.pending')
            ->with('success', "NGO '{$organizationName}' has been deleted.");
    }
}
