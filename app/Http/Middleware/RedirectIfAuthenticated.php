<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * If the user is already authenticated, redirect them to the correct dashboard
     * based on their role. This prevents the "auto-redirect after logout+login" bug
     * where stale session state sends users to the wrong dashboard.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $target = RouteServiceProvider::homeForUser($user);

                // If target is an absolute URL (custom domain), redirect directly
                if (str_starts_with($target, 'http')) {
                    return redirect()->away($target);
                }

                return redirect($target);
            }
        }

        return $next($request);
    }
}
