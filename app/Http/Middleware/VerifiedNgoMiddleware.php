<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedNgoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->isNgo() && !$user->isVerified()) {
            return redirect()->route('ngo.dashboard')
                ->with('warning', 'Your account is pending verification. This action is restricted.');
        }

        return $next($request);
    }
}
