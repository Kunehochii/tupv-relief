<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                Auth::login($user);
                return redirect()->intended(route($user->role . '.dashboard'));
            }

            // Store Google user data in session for role selection
            session([
                'google_user' => [
                    'id' => $googleUser->id,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                ]
            ]);

            return redirect()->route('auth.google.role-select');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }

    public function showRoleSelect()
    {
        if (!session('google_user')) {
            return redirect()->route('login');
        }

        return view('auth.google-role-select');
    }

    public function storeWithRole()
    {
        $googleUser = session('google_user');
        
        if (!$googleUser) {
            return redirect()->route('login');
        }

        request()->validate([
            'role' => ['required', 'in:donor,ngo'],
        ]);

        $userData = [
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'google_id' => $googleUser['id'],
            'avatar' => $googleUser['avatar'],
            'role' => request('role'),
            'email_verified_at' => now(),
        ];

        if (request('role') === User::ROLE_NGO) {
            request()->validate([
                'organization_name' => ['required', 'string', 'max:255'],
                'certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ]);

            $userData['organization_name'] = request('organization_name');
            $userData['verification_status'] = User::STATUS_PENDING;
            
            if (request()->hasFile('certificate')) {
                $path = request()->file('certificate')->store('certificates', 'public');
                $userData['certificate_path'] = $path;
            }
        } else {
            $userData['verification_status'] = User::STATUS_VERIFIED;
        }

        $user = User::create($userData);
        
        session()->forget('google_user');
        
        Auth::login($user);

        if ($user->isNgo() && $user->isPending()) {
            return redirect()->route('ngo.dashboard')
                ->with('warning', 'Your account is pending verification. Some features may be limited.');
        }

        return redirect()->route($user->role . '.dashboard');
    }
}
