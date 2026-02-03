<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpController extends Controller
{
    /**
     * Show OTP verification form
     */
    public function show(): View|RedirectResponse
    {
        $user = auth()->user();

        // If already verified, redirect to dashboard
        if ($user->isOtpVerified()) {
            return redirect()->route($user->role . '.dashboard');
        }

        return view('auth.otp-verify');
    }

    /**
     * Send OTP to user's email
     */
    public function send(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Invalidate previous OTPs
        OtpVerification::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        // Generate new OTP
        $otp = OtpVerification::generateOtp();

        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), // 10 minute expiry
        ]);

        // Send email
        Mail::to($user->email)->send(new OtpMail($otp));

        return back()->with('success', 'Verification code sent to your email.');
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        $otpRecord = OtpVerification::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // Mark OTP as verified
        $otpRecord->update(['verified_at' => now()]);

        // Mark user as OTP verified
        $user->update(['otp_verified' => true]);

        // Redirect based on role
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Email verified successfully!'),
            'donor' => redirect()->route('donor.dashboard')->with('success', 'Email verified successfully!'),
            'ngo' => redirect()->route('ngo.dashboard')->with('success', 'Email verified successfully!'),
            default => redirect('/')->with('success', 'Email verified successfully!'),
        };
    }
}
