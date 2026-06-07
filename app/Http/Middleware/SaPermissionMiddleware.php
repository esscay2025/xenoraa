<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SaPermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: sa.permission:customers.view
     */
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasSaPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
