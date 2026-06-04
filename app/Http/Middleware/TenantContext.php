<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TenantContext
{
    /**
     * Set the current tenant context for all admin requests.
     * This ensures all queries are scoped to the authenticated admin user.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Determine the tenant owner:
            // - If the user IS an admin, they ARE the tenant
            // - If the user is staff/visitor, find their parent admin (tenant owner)
            if ($user->isAdmin()) {
                $tenant = $user;
            } else {
                // Staff/visitor: their tenant_owner_id points to the admin who created them
                $tenant = $user->tenantOwner ?? $user;
            }

            // Bind tenant to the container so any controller can access it
            app()->instance('tenant', $tenant);
            app()->instance('tenant_id', $tenant->id);

            // Share tenant with all views
            View::share('currentTenant', $tenant);
        }

        return $next($request);
    }
}
