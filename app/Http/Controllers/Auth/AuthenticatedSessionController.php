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
     * Handle login — after login redirect based on role and domain.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Super admin → superadmin dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('superadmin.dashboard'));
        }

        // Admin → admin dashboard
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Staff → staff dashboard
        if ($user->isStaff()) {
            return redirect()->intended(route('staff.dashboard'));
        }

        // Regular user → user dashboard
        return redirect()->intended(route('user.dashboard'));
    }

    /**
     * Logout and redirect to appropriate home.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // If on xenoraa.com, redirect to xenoraa home
        if ($host === $mainDomain || $host === 'www.' . $mainDomain) {
            return redirect('/');
        }

        // Custom domain → redirect to that domain's home
        return redirect('/');
    }
}
