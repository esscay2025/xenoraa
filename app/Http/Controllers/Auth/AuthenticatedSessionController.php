<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

        // Custom domain or gopi.blog — show portfolio login
        return view('auth.login');
    }

    /**
     * Handle login — after login redirect based on role AND domain.
     *
     * Role Hierarchy:
     * - superadmin (support@xenoraa.com): ONLY gets superadmin dashboard on xenoraa.com
     *   On tenant domains (gopi.blog), treated as regular admin of that tenant
     * - admin (gopi@outlook.in): Xenoraa subscriber / tenant owner → admin dashboard
     * - staff: Sub-user created by tenant admin → staff dashboard
     * - visitor: Regular registered visitor → user dashboard
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
        $isMainDomain = ($host === $mainDomain || $host === 'www.' . $mainDomain);

        // Super admin ONLY redirects to superadmin dashboard when logging in on xenoraa.com
        // If they log in on gopi.blog or any tenant domain, treat as tenant admin
        if ($user->isSuperAdmin() && $isMainDomain) {
            return redirect()->route('superadmin.dashboard');
        }

        // Tenant admin (Xenoraa subscriber) → admin dashboard
        if ($user->isAdmin()) {
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
     * Logout and redirect to appropriate home.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Always redirect to current domain's home
        return redirect('/');
    }
}
