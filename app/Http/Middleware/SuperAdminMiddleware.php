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
        $superAdminEmails = config('xenoraa.superadmin_emails', []);

        // Check if user is superadmin by email or role
        if (!in_array($user->email, $superAdminEmails) && $user->role !== 'superadmin') {
            abort(403, 'Super Admin access required.');
        }

        return $next($request);
    }
}
