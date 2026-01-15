<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:donor,ngo'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ];

        // Handle NGO specific fields
        if ($request->role === User::ROLE_NGO) {
            $request->validate([
                'organization_name' => ['required', 'string', 'max:255'],
                'certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ]);

            $userData['organization_name'] = $request->organization_name;
            $userData['verification_status'] = User::STATUS_PENDING;
            
            if ($request->hasFile('certificate')) {
                $path = $request->file('certificate')->store('certificates', 'public');
                $userData['certificate_path'] = $path;
            }
        } else {
            $userData['verification_status'] = User::STATUS_VERIFIED;
        }

        $user = User::create($userData);

        Auth::login($user);

        if ($user->isNgo() && $user->isPending()) {
            return redirect()->route('ngo.dashboard')
                ->with('warning', 'Your account is pending verification. Some features may be limited.');
        }

        return redirect()->route($user->role . '.dashboard');
    }
}
