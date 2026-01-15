<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedNgoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();

        if ($user->isNgo() && !$user->isVerified()) {
            return redirect()->route('ngo.dashboard')
                ->with('warning', 'Your account is pending verification. This action is restricted.');
        }

        return $next($request);
    }
}
