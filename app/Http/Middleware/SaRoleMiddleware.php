<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SaRoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: sa.role:superadmin|staff   (pipe-separated list of allowed roles)
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // SuperAdmin always passes
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user's SA role is in the allowed list
        $userRoleName = $user->saRole?->name;
        if ($userRoleName && in_array($userRoleName, $roles)) {
            return $next($request);
        }

        abort(403, 'Access denied. You do not have the required role to access this area.');
    }
}
