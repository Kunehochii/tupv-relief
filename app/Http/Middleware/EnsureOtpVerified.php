<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     *
     * Ensure the user has completed OTP verification before accessing protected routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (Auth::check() && $user && !$user->isOtpVerified()) {
            // Allow access to OTP verification routes and logout
            if ($request->routeIs('otp.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('otp.verify')
                ->with('warning', 'Please verify your email to continue.');
        }

        return $next($request);
    }
}
