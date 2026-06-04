<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     * Resolves the current tenant (user) based on:
     * 1. Custom domain (e.g., gopi.blog → user with custom_domain = 'gopi.blog')
     * 2. Subdomain/path username (e.g., xenoraa.com/gopi → user with username = 'gopi')
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Check if this is a custom domain (not xenoraa.com and not www.xenoraa.com)
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            // Look up user by custom domain
            $tenant = User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();

            if ($tenant) {
                $this->setTenant($tenant);
                $request->attributes->set('tenant', $tenant);
                $request->attributes->set('tenant_mode', 'custom_domain');
                return $next($request);
            }
        }

        return $next($request);
    }

    private function setTenant(User $tenant): void
    {
        // Share tenant data with all views
        View::share('tenant', $tenant);
        View::share('tenant_name', $tenant->name);
        View::share('tenant_username', $tenant->username);

        // Override site settings with tenant's settings if they have custom ones
        if ($tenant->site_title) {
            Config::set('app.name', $tenant->site_title);
        }
    }
}
