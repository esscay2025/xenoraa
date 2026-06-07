<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — Xenoraa</title>
    <link rel="icon" type="image/png" href="/images/xenoraa/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --sa-black: #000; --sa-dark: #080808; --sa-sidebar: #0a0a0a;
            --sa-card: #111; --sa-border: #1a1a1a; --sa-border2: #222;
            --sa-purple: #7c3aed; --sa-purple-light: #a855f7; --sa-purple-glow: rgba(124,58,237,0.12);
            --sa-white: #fff; --sa-gray: #a1a1aa; --sa-gray2: #71717a;
            --sa-red: #ef4444; --sa-green: #22c55e; --sa-yellow: #eab308;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--sa-dark); color: var(--sa-white); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sa-sidebar { width: 260px; flex-shrink: 0; background: var(--sa-sidebar); border-right: 1px solid var(--sa-border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto; z-index: 100; }
        .sa-sidebar-logo { padding: 1.25rem 1.5rem 1rem; border-bottom: 1px solid var(--sa-border); display: flex; align-items: center; gap: 0.75rem; }
        .sa-logo-text { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; }
        .sa-logo-badge { font-size: 0.55rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.3); color: #a855f7; padding: 0.15rem 0.5rem; border-radius: 4px; }

        /* COLLAPSIBLE NAV GROUPS */
        .sa-nav-group { border-bottom: 1px solid rgba(255,255,255,0.03); }
        .sa-nav-group-header { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 1.5rem; cursor: pointer; user-select: none; transition: background 0.2s; }
        .sa-nav-group-header:hover { background: rgba(255,255,255,0.02); }
        .sa-nav-group-label { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #52525b; }
        .sa-nav-group-chevron { font-size: 0.65rem; color: #3f3f46; transition: transform 0.25s; }
        .sa-nav-group.open .sa-nav-group-chevron { transform: rotate(180deg); }
        .sa-nav-group-body { overflow: hidden; max-height: 0; transition: max-height 0.3s ease; }
        .sa-nav-group.open .sa-nav-group-body { max-height: 600px; }

        .sa-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.5rem; color: var(--sa-gray2); text-decoration: none; font-size: 0.825rem; font-weight: 500; transition: all 0.2s; border-left: 2px solid transparent; }
        .sa-nav-item:hover { color: var(--sa-white); background: rgba(255,255,255,0.03); }
        .sa-nav-item.active { color: var(--sa-white); border-left-color: var(--sa-purple); background: rgba(124,58,237,0.08); }
        .sa-nav-item i { width: 16px; text-align: center; font-size: 0.8rem; }
        .sa-nav-badge { margin-left: auto; background: var(--sa-purple); color: #fff; font-size: 0.6rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 100px; }

        /* SIDEBAR BOTTOM PROFILE */
        .sa-sidebar-profile { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid var(--sa-border); display: flex; align-items: center; gap: 0.75rem; }
        .sa-sidebar-avatar { width: 34px; height: 34px; flex-shrink: 0; background: var(--sa-purple-glow); border: 1px solid rgba(124,58,237,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: var(--sa-purple-light); }
        .sa-sidebar-profile-info { flex: 1; min-width: 0; }
        .sa-sidebar-profile-name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sa-sidebar-profile-role { font-size: 0.65rem; color: var(--sa-gray2); }
        .sa-sidebar-profile-actions { display: flex; gap: 0.4rem; }
        .sa-sidebar-profile-btn { width: 28px; height: 28px; border: 1px solid var(--sa-border2); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--sa-gray2); cursor: pointer; transition: all 0.2s; background: transparent; text-decoration: none; font-size: 0.75rem; }
        .sa-sidebar-profile-btn:hover { border-color: var(--sa-purple); color: var(--sa-purple-light); }
        .sa-sidebar-profile-btn.logout:hover { border-color: var(--sa-red); color: var(--sa-red); }

        /* MAIN */
        .sa-main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* TOPBAR */
        .sa-topbar { height: 60px; background: var(--sa-sidebar); border-bottom: 1px solid var(--sa-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 50; }
        .sa-topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; }
        .sa-topbar-actions { display: flex; align-items: center; gap: 0.75rem; }
        .sa-topbar-btn { width: 36px; height: 36px; border: 1px solid var(--sa-border2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--sa-gray2); cursor: pointer; transition: all 0.2s; background: transparent; position: relative; }
        .sa-topbar-btn:hover { border-color: var(--sa-purple); color: var(--sa-purple-light); }
        .sa-notif-dot { position: absolute; top: 6px; right: 6px; width: 7px; height: 7px; background: var(--sa-red); border-radius: 50%; border: 1.5px solid var(--sa-sidebar); }
        .sa-topbar-btn-wrap { position: relative; }

        /* NOTIFICATION PANEL */
        .sa-notif-panel { position: absolute; top: 48px; right: 0; width: 340px; background: #111; border: 1px solid var(--sa-border2); border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.8); z-index: 200; display: none; }
        .sa-notif-panel.open { display: block; }
        .sa-notif-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--sa-border); display: flex; align-items: center; justify-content: space-between; }
        .sa-notif-title { font-size: 0.875rem; font-weight: 700; }
        .sa-notif-mark { font-size: 0.7rem; color: var(--sa-purple-light); cursor: pointer; text-decoration: none; }
        .sa-notif-item { padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--sa-border); display: flex; gap: 0.75rem; }
        .sa-notif-item:last-child { border-bottom: none; }
        .sa-notif-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.75rem; }
        .sa-notif-icon.user { background: rgba(124,58,237,0.15); color: var(--sa-purple-light); }
        .sa-notif-icon.payment { background: rgba(34,197,94,0.15); color: var(--sa-green); }
        .sa-notif-icon.domain { background: rgba(234,179,8,0.15); color: var(--sa-yellow); }
        .sa-notif-text { flex: 1; }
        .sa-notif-text strong { font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.15rem; }
        .sa-notif-text span { font-size: 0.72rem; color: var(--sa-gray2); }
        .sa-notif-time { font-size: 0.65rem; color: #3f3f46; white-space: nowrap; margin-top: 0.15rem; }
        .sa-notif-footer { padding: 0.75rem 1.25rem; text-align: center; }
        .sa-notif-footer a { font-size: 0.775rem; color: var(--sa-purple-light); text-decoration: none; }

        /* PROFILE DROPDOWN */
        .sa-profile-dropdown { position: absolute; top: 48px; right: 0; width: 220px; background: #111; border: 1px solid var(--sa-border2); border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.8); z-index: 200; display: none; }
        .sa-profile-dropdown.open { display: block; }
        .sa-profile-dd-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--sa-border); }
        .sa-profile-dd-name { font-size: 0.875rem; font-weight: 700; }
        .sa-profile-dd-email { font-size: 0.72rem; color: var(--sa-gray2); margin-top: 0.2rem; }
        .sa-profile-dd-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 1.25rem; color: var(--sa-gray2); text-decoration: none; font-size: 0.8rem; font-weight: 500; transition: all 0.2s; width: 100%; border: none; background: none; cursor: pointer; font-family: inherit; text-align: left; }
        .sa-profile-dd-item:hover { color: var(--sa-white); background: rgba(255,255,255,0.03); }
        .sa-profile-dd-item i { width: 14px; text-align: center; font-size: 0.75rem; }
        .sa-profile-dd-divider { height: 1px; background: var(--sa-border); margin: 0.25rem 0; }
        .sa-profile-dd-item.logout:hover { color: var(--sa-red); }

        /* CONTENT */
        .sa-content { padding: 2rem; flex: 1; }

        /* STAT CARDS */
        .sa-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
        .sa-stat-card { background: var(--sa-card); border: 1px solid var(--sa-border); border-radius: 12px; padding: 1.5rem; transition: all 0.3s; }
        .sa-stat-card:hover { border-color: var(--sa-purple); }
        .sa-stat-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--sa-gray2); margin-bottom: 0.75rem; }
        .sa-stat-value { font-family: 'Space Grotesk', sans-serif; font-size: 2rem; font-weight: 800; color: var(--sa-white); line-height: 1; margin-bottom: 0.5rem; }
        .sa-stat-change { font-size: 0.75rem; display: flex; align-items: center; gap: 0.3rem; }
        .sa-stat-up { color: var(--sa-green); }
        .sa-stat-down { color: var(--sa-red); }
        .sa-stat-icon { float: right; width: 40px; height: 40px; background: var(--sa-purple-glow); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--sa-purple-light); font-size: 1rem; margin-top: -0.5rem; }

        /* TABLE */
        .sa-card { background: var(--sa-card); border: 1px solid var(--sa-border); border-radius: 12px; overflow: hidden; }
        .sa-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--sa-border); display: flex; align-items: center; justify-content: space-between; }
        .sa-card-title { font-size: 0.875rem; font-weight: 700; color: var(--sa-white); }
        .sa-card-action { font-size: 0.75rem; color: var(--sa-purple-light); text-decoration: none; }
        .sa-table { width: 100%; border-collapse: collapse; }
        .sa-table th { padding: 0.75rem 1.5rem; text-align: left; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--sa-gray2); border-bottom: 1px solid var(--sa-border); }
        .sa-table td { padding: 1rem 1.5rem; font-size: 0.825rem; color: var(--sa-gray); border-bottom: 1px solid rgba(255,255,255,0.03); }
        .sa-table tr:last-child td { border-bottom: none; }
        .sa-table tr:hover td { background: rgba(255,255,255,0.02); }
        .sa-badge { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.65rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 4px; }
        .sa-badge-active { background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
        .sa-badge-inactive { background: rgba(113,113,122,0.1); color: #52525b; border: 1px solid #1f1f1f; }
        .sa-badge-trial { background: rgba(234,179,8,0.1); color: #fbbf24; border: 1px solid rgba(234,179,8,0.2); }
        .sa-badge-starter { background: rgba(124,58,237,0.1); color: #a855f7; border: 1px solid rgba(124,58,237,0.2); }
        .sa-badge-pro { background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.3); }
        .sa-badge-business { background: rgba(250,204,21,0.1); color: #fbbf24; border: 1px solid rgba(250,204,21,0.2); }
        .sa-badge-suspended { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .sa-action-btn { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.75rem; border: 1px solid var(--sa-border2); border-radius: 6px; color: var(--sa-gray2); font-size: 0.75rem; text-decoration: none; transition: all 0.2s; background: transparent; cursor: pointer; }
        .sa-action-btn:hover { border-color: var(--sa-purple); color: var(--sa-purple-light); }
        .sa-action-btn.danger:hover { border-color: var(--sa-red); color: var(--sa-red); }
        .sa-btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; background: var(--sa-purple); color: #fff; border: none; border-radius: 8px; font-size: 0.825rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .sa-btn-primary:hover { background: #6d28d9; }

        /* GRID */
        .sa-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .sa-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .sa-search { display: flex; align-items: center; gap: 0.75rem; background: #111; border: 1px solid var(--sa-border2); border-radius: 8px; padding: 0.5rem 1rem; }
        .sa-search input { background: none; border: none; outline: none; color: var(--sa-white); font-size: 0.825rem; font-family: 'Inter', sans-serif; width: 200px; }
        .sa-search input::placeholder { color: #3f3f46; }
        .sa-search i { color: #3f3f46; font-size: 0.8rem; }

        @media(max-width:1024px){ .sa-sidebar{width:220px;} .sa-main{margin-left:220px;} .sa-stat-grid{grid-template-columns:repeat(2,1fr);} }
        @media(max-width:768px){ .sa-sidebar{display:none;} .sa-main{margin-left:0;} .sa-stat-grid{grid-template-columns:1fr;} .sa-grid-2,.sa-grid-3{grid-template-columns:1fr;} }
    </style>
    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sa-sidebar">
    <div class="sa-sidebar-logo">
        <img src="/images/xenoraa/logo.png" alt="Xenoraa" style="height:22px;filter:brightness(0) invert(1);">
        <div>
            <div class="sa-logo-text">xenoraa</div>
            <div class="sa-logo-badge">Super Admin</div>
        </div>
    </div>

    {{-- Overview --}}
    <div class="sa-nav-group open" id="grp-overview">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-overview')">
            <span class="sa-nav-group-label">Overview</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.analytics') }}" class="sa-nav-item {{ request()->routeIs('superadmin.analytics') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Analytics
            </a>
        </div>
    </div>

    {{-- User Management removed - covered by Administration > Customers --}}

    {{-- Administration --}}
    <div class="sa-nav-group {{ request()->routeIs('superadmin.customers*') || request()->routeIs('superadmin.agents*') || request()->routeIs('superadmin.staff*') ? 'open' : '' }}" id="grp-admin">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-admin')">
            <span class="sa-nav-group-label">Administration</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.customers.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.customers*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i> Customers
                <span class="sa-nav-badge">{{ \App\Models\User::whereNotNull('username')->count() }}</span>
            </a>
            <a href="{{ route('superadmin.agents.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.agents*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> Agents
                <span class="sa-nav-badge">{{ \App\Models\Agent::count() }}</span>
            </a>
            <a href="{{ route('superadmin.staff.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.staff*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> Staff Members
            </a>
            <a href="{{ route('superadmin.staff.roles') }}" class="sa-nav-item {{ request()->routeIs('superadmin.staff.roles*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i> Roles & Permissions
            </a>
        </div>
    </div>

    {{-- Subscriptions --}}
    <div class="sa-nav-group {{ request()->routeIs('superadmin.subscriptions*') || request()->routeIs('superadmin.revenue') ? 'open' : '' }}" id="grp-subs">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-subs')">
            <span class="sa-nav-group-label">Subscriptions</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.subscriptions') }}" class="sa-nav-item {{ request()->routeIs('superadmin.subscriptions*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> Subscriptions
            </a>
            <a href="{{ route('superadmin.revenue') }}" class="sa-nav-item {{ request()->routeIs('superadmin.revenue') ? 'active' : '' }}">
                <i class="fas fa-rupee-sign"></i> Revenue
            </a>
        </div>
    </div>

    {{-- Domains --}}
    <div class="sa-nav-group {{ request()->routeIs('superadmin.domains') ? 'open' : '' }}" id="grp-domains">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-domains')">
            <span class="sa-nav-group-label">Domains</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.domains') }}" class="sa-nav-item {{ request()->routeIs('superadmin.domains') ? 'active' : '' }}">
                <i class="fas fa-globe"></i> Custom Domains
            </a>
        </div>
    </div>

    {{-- Content --}}
    <div class="sa-nav-group {{ request()->routeIs('superadmin.blog*') || request()->routeIs('superadmin.showcase') ? 'open' : '' }}" id="grp-content">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-content')">
            <span class="sa-nav-group-label">Content</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.blog') }}" class="sa-nav-item {{ request()->routeIs('superadmin.blog*') ? 'active' : '' }}">
                <i class="fas fa-pen-nib"></i> Blog Posts
            </a>
            <a href="{{ route('superadmin.showcase') }}" class="sa-nav-item {{ request()->routeIs('superadmin.showcase') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Showcase
            </a>
        </div>
    </div>

    {{-- Theme Store --}}
    <div class="sa-nav-group {{ request()->routeIs('superadmin.themes*') ? 'open' : '' }}" id="grp-themes">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-themes')">
            <span class="sa-nav-group-label">Theme Store</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.themes.index') }}" class="sa-nav-item {{ request()->routeIs('superadmin.themes.index') ? 'active' : '' }}">
                <i class="fas fa-palette"></i> All Themes
            </a>
            <a href="{{ route('superadmin.themes.create') }}" class="sa-nav-item {{ request()->routeIs('superadmin.themes.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Add Theme
            </a>
        </div>
    </div>
    {{-- System --}}
    <div class="sa-nav-group {{ request()->routeIs('superadmin.settings') || request()->routeIs('superadmin.emails') || request()->routeIs('superadmin.logs') ? 'open' : '' }}" id="grp-system">
        <div class="sa-nav-group-header" onclick="toggleGroup('grp-system')">
            <span class="sa-nav-group-label">System</span>
            <i class="fas fa-chevron-down sa-nav-group-chevron"></i>
        </div>
        <div class="sa-nav-group-body">
            <a href="{{ route('superadmin.settings') }}" class="sa-nav-item {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Platform Settings
            </a>
            <a href="{{ route('superadmin.emails') }}" class="sa-nav-item {{ request()->routeIs('superadmin.emails') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Email Templates
            </a>
            <a href="{{ route('superadmin.logs') }}" class="sa-nav-item {{ request()->routeIs('superadmin.logs') ? 'active' : '' }}">
                <i class="fas fa-list-alt"></i> Activity Logs
            </a>
        </div>
    </div>

    {{-- Sidebar bottom: Profile + Logout --}}
    <div class="sa-sidebar-profile">
        <div class="sa-sidebar-avatar">{{ substr(auth()->user()->name ?? 'S', 0, 1) }}</div>
        <div class="sa-sidebar-profile-info">
            <div class="sa-sidebar-profile-name">{{ auth()->user()->name ?? 'Super Admin' }}</div>
            <div class="sa-sidebar-profile-role">
                @if(auth()->user()->isSuperAdmin())
                    Super Administrator
                @elseif(auth()->user()->isSaStaff())
                    Xenoraa Staff
                @elseif(auth()->user()->isSaAgent())
                    Xenoraa Agent
                @else
                    Xenoraa Team
                @endif
            </div>
        </div>
        <div class="sa-sidebar-profile-actions">
            <a href="{{ route('xenoraa.home') }}" class="sa-sidebar-profile-btn" title="View Site" target="_blank">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="sa-sidebar-profile-btn logout" title="Sign Out">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN --}}
<div class="sa-main">
    <div class="sa-topbar">
        <div class="sa-topbar-title">@yield('page_title', 'Dashboard')</div>
        <div class="sa-topbar-actions">

            {{-- Notification Button --}}
            <div class="sa-topbar-btn-wrap" id="notifWrap">
                <button class="sa-topbar-btn" onclick="togglePanel('notifPanel','profilePanel')">
                    <i class="fas fa-bell"></i>
                    @php $newCount = \App\Models\User::whereNotNull('username')->where('created_at', '>=', now()->subDays(7))->count(); @endphp
                    @if($newCount > 0)<span class="sa-notif-dot"></span>@endif
                </button>
                <div class="sa-notif-panel" id="notifPanel">
                    <div class="sa-notif-header">
                        <span class="sa-notif-title">Notifications</span>
                        <a href="#" class="sa-notif-mark" onclick="document.querySelectorAll('.sa-notif-dot').forEach(d=>d.remove());return false;">Mark all read</a>
                    </div>
                    @php
                        $recentUsers = \App\Models\User::whereNotNull('username')->latest()->take(4)->get();
                        $recentDomains = \App\Models\User::whereNotNull('custom_domain')->latest()->take(2)->get();
                    @endphp
                    @forelse($recentUsers as $nu)
                    <div class="sa-notif-item">
                        <div class="sa-notif-icon user"><i class="fas fa-user-plus"></i></div>
                        <div class="sa-notif-text">
                            <strong>New User: {{ $nu->name }}</strong>
                            <span>{{ ucfirst($nu->plan ?? 'starter') }} plan</span>
                        </div>
                        <div class="sa-notif-time">{{ $nu->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <div class="sa-notif-item" style="justify-content:center;color:var(--sa-gray2);font-size:0.8rem;padding:1.5rem;">No new notifications</div>
                    @endforelse
                    @foreach($recentDomains as $rd)
                    <div class="sa-notif-item">
                        <div class="sa-notif-icon domain"><i class="fas fa-globe"></i></div>
                        <div class="sa-notif-text">
                            <strong>Domain Mapped</strong>
                            <span>{{ $rd->custom_domain }} by {{ $rd->name }}</span>
                        </div>
                        <div class="sa-notif-time">{{ $rd->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                    <div class="sa-notif-footer"><a href="{{ route('superadmin.users') }}">View all users →</a></div>
                </div>
            </div>

            {{-- Profile Dropdown --}}
            <div class="sa-topbar-btn-wrap" id="profileWrap">
                <div class="sa-topbar-btn" onclick="togglePanel('profilePanel','notifPanel')" style="width:auto;padding:0 0.6rem;gap:0.5rem;cursor:pointer;border-radius:8px;">
                    <div style="width:26px;height:26px;background:var(--sa-purple-glow);border:1px solid rgba(124,58,237,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;color:var(--sa-purple-light);">
                        {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
                    </div>
                    <span style="font-size:0.8rem;font-weight:500;color:var(--sa-gray);">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.6rem;color:var(--sa-gray2);"></i>
                </div>
                <div class="sa-profile-dropdown" id="profilePanel">
                    <div class="sa-profile-dd-header">
                        <div class="sa-profile-dd-name">{{ auth()->user()->name ?? 'Super Admin' }}</div>
                        <div class="sa-profile-dd-email">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <a href="{{ route('superadmin.dashboard') }}" class="sa-profile-dd-item">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                    <a href="{{ route('xenoraa.home') }}" class="sa-profile-dd-item" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                    <div class="sa-profile-dd-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sa-profile-dd-item logout">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="sa-content">
        @if(session('success'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:0.875rem 1.25rem;margin-bottom:1.5rem;font-size:0.825rem;color:#22c55e;display:flex;align-items:center;gap:0.75rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:0.875rem 1.25rem;margin-bottom:1.5rem;font-size:0.825rem;color:#f87171;display:flex;align-items:center;gap:0.75rem;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script>
function toggleGroup(id) {
    document.getElementById(id).classList.toggle('open');
}
function togglePanel(openId, closeId) {
    const el = document.getElementById(openId);
    const cl = document.getElementById(closeId);
    if (cl) cl.classList.remove('open');
    el.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    ['notifWrap','profileWrap'].forEach(function(wrapId) {
        const wrap = document.getElementById(wrapId);
        if (wrap && !wrap.contains(e.target)) {
            const panel = wrap.querySelector('.sa-notif-panel, .sa-profile-dropdown');
            if (panel) panel.classList.remove('open');
        }
    });
});
</script>

@yield('scripts')
</body>
</html>
