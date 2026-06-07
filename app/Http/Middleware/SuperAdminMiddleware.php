<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow: full super admin, SA staff, and SA agents (Xenoraa internal team)
        if ($user->isSuperAdmin() || $user->isSaStaff() || $user->isSaAgent()) {
            return $next($request);
        }

        abort(403, 'Super Admin access required.');
    }
}
