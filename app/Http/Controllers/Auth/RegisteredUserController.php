<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'role' => ['required', 'in:donor,ngo'],
        ];

        // Add NGO-specific validation rules before validating
        if ($request->role === User::ROLE_NGO) {
            $rules['organization_name'] = ['required', 'string', 'max:255'];
            $rules['certificate'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            $rules['organization_logo'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'otp_verified' => false,
        ];

        // Handle NGO specific fields
        if ($request->role === User::ROLE_NGO) {

            $userData['organization_name'] = $request->organization_name;
            $userData['verification_status'] = User::STATUS_PENDING;

            // Handle logo upload (new) - prefer file upload over URL
            if ($request->hasFile('organization_logo')) {
                $logoPath = $request->file('organization_logo')->store('ngo-logos', 'public');
                $userData['logo_path'] = $logoPath;
            } else {
                $userData['logo_url'] = 'https://placehold.co/800x800';
            }

            if ($request->hasFile('certificate')) {
                $path = $request->file('certificate')->store('certificates', 'public');
                $userData['certificate_path'] = $path;
            }
        } else {
            $userData['verification_status'] = User::STATUS_VERIFIED;
        }

        $user = User::create($userData);

        Auth::login($user);

        // Send OTP for email verification
        $otp = OtpVerification::generateOtp();
        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('otp.verify')
            ->with('success', 'Please check your email for the verification code.');
    }
}
