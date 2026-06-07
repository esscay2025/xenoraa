<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login view — domain-aware:
     * - xenoraa.com → Xenoraa branded login
     * - gopi.blog or any custom domain → Portfolio login
     */
    public function create(Request $request): View
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        if ($host === $mainDomain || $host === 'www.' . $mainDomain) {
            return view('auth.xenoraa-login');
        }

        // Custom domain or tenant domain — resolve tenant and show branded login
        $tenant = User::where('custom_domain', $host)
            ->orWhere('custom_domain', 'www.' . $host)
            ->first();

        if ($tenant) {
            return view('auth.tenant-login', compact('tenant'));
        }

        // Fallback
        return view('auth.login');
    }

    /**
     * Handle login — after login redirect based on role AND domain.
     *
     * Role Hierarchy:
     * - superadmin: ONLY gets superadmin dashboard on xenoraa.com
     * - admin (tenant owner): → admin dashboard
     * - staff: Sub-user → staff dashboard
     * - visitor: Regular registered visitor → user dashboard
     *
     * If login came from a tenant-specific login page (tenant_username in form),
     * verify the user belongs to that tenant before redirecting.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
        $isMainDomain = ($host === $mainDomain || $host === 'www.' . $mainDomain);

        // Check if login came from a tenant-specific login page
        $tenantUsername = $request->input('tenant_username');
        if ($tenantUsername) {
            $tenant = User::where('username', $tenantUsername)->first();
            if ($tenant) {
                // Verify: user must be the tenant admin OR a sub-user of this tenant
                $isOwner = ($user->id === $tenant->id);
                $isSubUser = ($user->tenant_owner_id === $tenant->id);

                if (!$isOwner && !$isSubUser && !$user->isSuperAdmin()) {
                    // Wrong tenant — log out and redirect back with error
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    return redirect()->route('tenant.login', $tenantUsername)
                        ->withErrors(['email' => 'These credentials do not belong to ' . $tenant->name . '\'s account.']);
                }
            }
        }

        // Super admin → superadmin dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        // SA Staff → superadmin dashboard (with permission-gated sidebar)
        if ($user->isSaStaff()) {
            return redirect()->route('superadmin.dashboard');
        }

        // SA Agent → dedicated agent dashboard
        if ($user->isSaAgent()) {
            return redirect()->route('agent.dashboard');
        }

        // Tenant admin (Xenoraa subscriber) → admin dashboard
        // If the tenant has a custom domain, redirect to that domain's admin dashboard
        if ($user->isAdmin()) {
            $adminUser = $user->tenant_owner_id ? User::find($user->tenant_owner_id) : $user;
            if ($adminUser && $adminUser->custom_domain && $isMainDomain) {
                // Redirect to custom domain admin dashboard
                return redirect()->away('https://' . $adminUser->custom_domain . '/admin/dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        // Staff sub-user → staff dashboard
        if ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }

        // Regular visitor/sub-user → user dashboard
        return redirect()->route('user.dashboard');
    }

    /**
     * Logout and redirect to the correct login page.
     * Clears session, remember token, and all auth cookies.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
        $isMainDomain = ($host === $mainDomain || $host === 'www.' . $mainDomain);

        // Determine redirect URL before logging out
        $redirectUrl = '/';
        if ($user) {
            if ($user->isSuperAdmin() || $user->isSaStaff() || $user->isSaAgent()) {
                $redirectUrl = route('login');
            } elseif ($user->isAdmin()) {
                // If on custom domain, redirect to custom domain login
                if (!$isMainDomain && ($user->custom_domain || ($user->tenant_owner_id && ($owner = User::find($user->tenant_owner_id)) && $owner->custom_domain))) {
                    $domain = $user->custom_domain ?? ($owner->custom_domain ?? null);
                    if ($domain) {
                        $redirectUrl = 'https://' . $domain . '/login';
                    } else {
                        $redirectUrl = route('login');
                    }
                } else {
                    // On xenoraa.com — redirect to tenant login page
                    $username = $user->username;
                    $redirectUrl = $username ? route('tenant.login', $username) : route('login');
                }
            } else {
                $redirectUrl = route('login');
            }
        }

        // Clear remember token
        if ($user && method_exists($user, 'forceFill')) {
            $user->forceFill(['remember_token' => null])->save();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectUrl);
    }
}
