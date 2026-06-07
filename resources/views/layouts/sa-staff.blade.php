<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Portal') — Xenoraa</title>
    <link rel="icon" type="image/png" href="/images/xenoraa/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --st-dark: #080808; --st-sidebar: #0a0a0a; --st-card: #111;
            --st-border: #1a1a1a; --st-border2: #222;
            --st-blue: #3b82f6; --st-blue-glow: rgba(59,130,246,0.12);
            --st-white: #fff; --st-gray: #a1a1aa; --st-gray2: #71717a;
            --st-red: #ef4444; --st-yellow: #f59e0b; --st-green: #22c55e;
            --st-purple: #7c3aed;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--st-dark); color: var(--st-white); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .st-sidebar { width: 240px; flex-shrink: 0; background: var(--st-sidebar); border-right: 1px solid var(--st-border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto; z-index: 100; }
        .st-sidebar-logo { padding: 1.25rem 1.5rem 1rem; border-bottom: 1px solid var(--st-border); display: flex; align-items: center; gap: 0.75rem; }
        .st-logo-text { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; }
        .st-logo-badge { font-size: 0.55rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; padding: 0.15rem 0.5rem; border-radius: 4px; }

        /* NAV */
        .st-nav-section { padding: 1.25rem 0 0.5rem; }
        .st-nav-label { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #3f3f46; padding: 0 1.5rem; margin-bottom: 0.4rem; }
        .st-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.5rem; color: var(--st-gray2); text-decoration: none; font-size: 0.825rem; font-weight: 500; transition: all 0.2s; border-left: 2px solid transparent; }
        .st-nav-item:hover { color: var(--st-white); background: rgba(255,255,255,0.03); }
        .st-nav-item.active { color: var(--st-white); border-left-color: var(--st-blue); background: rgba(59,130,246,0.08); }
        .st-nav-item i { width: 16px; text-align: center; font-size: 0.8rem; }

        /* SIDEBAR BOTTOM */
        .st-sidebar-profile { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid var(--st-border); display: flex; align-items: center; gap: 0.75rem; }
        .st-sidebar-avatar { width: 34px; height: 34px; flex-shrink: 0; background: var(--st-blue-glow); border: 1px solid rgba(59,130,246,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: var(--st-blue); }
        .st-sidebar-profile-info { flex: 1; min-width: 0; }
        .st-sidebar-profile-name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .st-sidebar-profile-role { font-size: 0.65rem; color: var(--st-gray2); }
        .st-logout-btn { width: 28px; height: 28px; border: 1px solid var(--st-border2); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--st-gray2); cursor: pointer; transition: all 0.2s; background: transparent; font-size: 0.75rem; }
        .st-logout-btn:hover { border-color: var(--st-red); color: var(--st-red); }

        /* MAIN */
        .st-main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* TOPBAR */
        .st-topbar { height: 60px; background: var(--st-sidebar); border-bottom: 1px solid var(--st-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 50; }
        .st-topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; }

        /* CONTENT */
        .st-content { padding: 2rem; flex: 1; }

        /* CARDS */
        .st-card { background: var(--st-card); border: 1px solid var(--st-border); border-radius: 12px; overflow: hidden; }
        .st-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--st-border); display: flex; align-items: center; justify-content: space-between; }
        .st-card-title { font-size: 0.875rem; font-weight: 700; color: var(--st-white); }

        @media(max-width:768px){ .st-sidebar{display:none;} .st-main{margin-left:0;} }
    </style>
    @yield('styles')
</head>
<body>

{{-- STAFF SIDEBAR --}}
<aside class="st-sidebar">
    <div class="st-sidebar-logo">
        <img src="/images/xenoraa/logo.png" alt="Xenoraa" style="height:22px;filter:brightness(0) invert(1);">
        <div>
            <div class="st-logo-text">xenoraa</div>
            <div class="st-logo-badge">Staff Portal</div>
        </div>
    </div>

    @php $saUser = auth()->user(); @endphp

    {{-- Always visible: Dashboard --}}
    <div class="st-nav-section">
        <div class="st-nav-label">Overview</div>
        <a href="{{ route('superadmin.dashboard') }}" class="st-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
    </div>

    {{-- Customers --}}
    @if($saUser->hasSaPermission('customers.view') || $saUser->hasSaPermission('customers.create'))
    <div class="st-nav-section">
        <div class="st-nav-label">Customers</div>
        @if($saUser->hasSaPermission('customers.view'))
        <a href="{{ route('superadmin.customers') }}" class="st-nav-item {{ request()->routeIs('superadmin.customers*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Customers
        </a>
        @endif
        @if($saUser->hasSaPermission('subscriptions.view') || $saUser->hasSaPermission('subscriptions.manage'))
        <a href="{{ route('superadmin.subscriptions') }}" class="st-nav-item {{ request()->routeIs('superadmin.subscriptions*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Subscriptions
        </a>
        @endif
        @if($saUser->hasSaPermission('revenue.view'))
        <a href="{{ route('superadmin.revenue') }}" class="st-nav-item {{ request()->routeIs('superadmin.revenue') ? 'active' : '' }}">
            <i class="fas fa-rupee-sign"></i> Revenue
        </a>
        @endif
    </div>
    @endif

    {{-- Agents --}}
    @if($saUser->hasSaPermission('agents.view') || $saUser->hasSaPermission('agents.create'))
    <div class="st-nav-section">
        <div class="st-nav-label">Agents</div>
        @if($saUser->hasSaPermission('agents.view'))
        <a href="{{ route('superadmin.agents.index') }}" class="st-nav-item {{ request()->routeIs('superadmin.agents*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i> Agents
        </a>
        @endif
    </div>
    @endif

    {{-- Staff --}}
    @if($saUser->hasSaPermission('staff.view') || $saUser->hasSaPermission('staff.create'))
    <div class="st-nav-section">
        <div class="st-nav-label">Staff</div>
        @if($saUser->hasSaPermission('staff.view'))
        <a href="{{ route('superadmin.staff.index') }}" class="st-nav-item {{ request()->routeIs('superadmin.staff*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i> Staff Members
        </a>
        @endif
    </div>
    @endif

    {{-- Domains --}}
    @if($saUser->hasSaPermission('domains.view') || $saUser->hasSaPermission('domains.manage'))
    <div class="st-nav-section">
        <div class="st-nav-label">Domains</div>
        <a href="{{ route('superadmin.domains') }}" class="st-nav-item {{ request()->routeIs('superadmin.domains') ? 'active' : '' }}">
            <i class="fas fa-globe"></i> Custom Domains
        </a>
    </div>
    @endif

    {{-- Content --}}
    @if($saUser->hasSaPermission('blog.view') || $saUser->hasSaPermission('blog.manage') || $saUser->hasSaPermission('showcase.view'))
    <div class="st-nav-section">
        <div class="st-nav-label">Content</div>
        @if($saUser->hasSaPermission('blog.view') || $saUser->hasSaPermission('blog.manage'))
        <a href="{{ route('superadmin.blog') }}" class="st-nav-item {{ request()->routeIs('superadmin.blog*') ? 'active' : '' }}">
            <i class="fas fa-pen-nib"></i> Blog Posts
        </a>
        @endif
        @if($saUser->hasSaPermission('showcase.view'))
        <a href="{{ route('superadmin.showcase') }}" class="st-nav-item {{ request()->routeIs('superadmin.showcase') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Showcase
        </a>
        @endif
    </div>
    @endif

    {{-- Themes --}}
    @if($saUser->hasSaPermission('themes.view') || $saUser->hasSaPermission('themes.manage'))
    <div class="st-nav-section">
        <div class="st-nav-label">Theme Store</div>
        <a href="{{ route('superadmin.themes.index') }}" class="st-nav-item {{ request()->routeIs('superadmin.themes*') ? 'active' : '' }}">
            <i class="fas fa-palette"></i> Themes
        </a>
    </div>
    @endif

    {{-- Analytics --}}
    @if($saUser->hasSaPermission('analytics.view'))
    <div class="st-nav-section">
        <div class="st-nav-label">Analytics</div>
        <a href="{{ route('superadmin.analytics') }}" class="st-nav-item {{ request()->routeIs('superadmin.analytics') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Analytics
        </a>
    </div>
    @endif

    {{-- Settings --}}
    @if($saUser->hasSaPermission('settings.view') || $saUser->hasSaPermission('settings.manage'))
    <div class="st-nav-section">
        <div class="st-nav-label">System</div>
        <a href="{{ route('superadmin.settings') }}" class="st-nav-item {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Settings
        </a>
        @if($saUser->hasSaPermission('logs.view'))
        <a href="{{ route('superadmin.logs') }}" class="st-nav-item {{ request()->routeIs('superadmin.logs') ? 'active' : '' }}">
            <i class="fas fa-list-alt"></i> Activity Logs
        </a>
        @endif
    </div>
    @endif

    {{-- Sidebar Profile --}}
    <div class="st-sidebar-profile">
        <div class="st-sidebar-avatar">{{ strtoupper(substr($saUser->name ?? 'S', 0, 1)) }}</div>
        <div class="st-sidebar-profile-info">
            <div class="st-sidebar-profile-name">{{ $saUser->name ?? 'Staff' }}</div>
            <div class="st-sidebar-profile-role">Xenoraa Staff</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="st-logout-btn" title="Sign Out">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="st-main">
    {{-- TOPBAR --}}
    <div class="st-topbar">
        <div class="st-topbar-title">@yield('title', 'Staff Portal')</div>
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;background:#111;border:1px solid #27272a;border-radius:8px;padding:0.4rem 0.875rem;">
                <i class="fas fa-user-shield" style="color:#3b82f6;font-size:0.75rem;"></i>
                <span style="font-size:0.775rem;color:#a1a1aa;">Xenoraa Staff</span>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div style="margin:1rem 2rem 0;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:0.875rem 1.25rem;display:flex;align-items:center;gap:0.75rem;">
        <i class="fas fa-check-circle" style="color:#22c55e;"></i>
        <span style="font-size:0.875rem;color:#22c55e;">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div style="margin:1rem 2rem 0;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:0.875rem 1.25rem;display:flex;align-items:center;gap:0.75rem;">
        <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
        <span style="font-size:0.875rem;color:#ef4444;">{{ session('error') }}</span>
    </div>
    @endif

    {{-- CONTENT --}}
    <div class="st-content">
        @yield('content')
    </div>
</div>

@yield('scripts')
</body>
</html>
