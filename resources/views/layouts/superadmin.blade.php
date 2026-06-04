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
            --sa-black: #000;
            --sa-dark: #080808;
            --sa-sidebar: #0a0a0a;
            --sa-card: #111;
            --sa-border: #1a1a1a;
            --sa-border2: #222;
            --sa-purple: #7c3aed;
            --sa-purple-light: #a855f7;
            --sa-purple-glow: rgba(124,58,237,0.12);
            --sa-white: #fff;
            --sa-gray: #a1a1aa;
            --sa-gray2: #71717a;
            --sa-red: #ef4444;
            --sa-green: #22c55e;
            --sa-yellow: #eab308;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--sa-dark); color: var(--sa-white); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sa-sidebar {
            width: 260px; flex-shrink: 0;
            background: var(--sa-sidebar);
            border-right: 1px solid var(--sa-border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            overflow-y: auto; z-index: 100;
        }
        .sa-sidebar-logo {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid var(--sa-border);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .sa-logo-text { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; }
        .sa-logo-badge { font-size: 0.55rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.3); color: #a855f7; padding: 0.15rem 0.5rem; border-radius: 4px; }
        .sa-nav-section { padding: 1rem 0; }
        .sa-nav-label { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #2a2a2a; padding: 0 1.5rem; margin-bottom: 0.5rem; }
        .sa-nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1.5rem;
            color: var(--sa-gray2); text-decoration: none;
            font-size: 0.825rem; font-weight: 500;
            transition: all 0.2s; border-left: 2px solid transparent;
        }
        .sa-nav-item:hover { color: var(--sa-white); background: rgba(255,255,255,0.03); }
        .sa-nav-item.active { color: var(--sa-white); border-left-color: var(--sa-purple); background: rgba(124,58,237,0.08); }
        .sa-nav-item i { width: 16px; text-align: center; font-size: 0.8rem; }
        .sa-nav-badge { margin-left: auto; background: var(--sa-purple); color: #fff; font-size: 0.6rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 100px; }

        /* MAIN */
        .sa-main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* TOPBAR */
        .sa-topbar {
            height: 60px; background: var(--sa-sidebar);
            border-bottom: 1px solid var(--sa-border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; position: sticky; top: 0; z-index: 50;
        }
        .sa-topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; }
        .sa-topbar-actions { display: flex; align-items: center; gap: 1rem; }
        .sa-topbar-btn { width: 36px; height: 36px; border: 1px solid var(--sa-border2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--sa-gray2); cursor: pointer; transition: all 0.2s; background: transparent; position: relative; }
        .sa-topbar-btn:hover { border-color: var(--sa-purple); color: var(--sa-purple-light); }
        .sa-notif-dot { position: absolute; top: 6px; right: 6px; width: 6px; height: 6px; background: var(--sa-red); border-radius: 50%; }
        .sa-avatar { width: 36px; height: 36px; background: var(--sa-purple-glow); border: 1px solid rgba(124,58,237,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; color: var(--sa-purple-light); cursor: pointer; }

        /* CONTENT */
        .sa-content { padding: 2rem; flex: 1; }

        /* CARDS */
        .sa-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
        .sa-stat-card {
            background: var(--sa-card); border: 1px solid var(--sa-border);
            border-radius: 12px; padding: 1.5rem;
            transition: all 0.3s;
        }
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
        .sa-badge-starter { background: rgba(124,58,237,0.1); color: #a855f7; border: 1px solid rgba(124,58,237,0.2); }
        .sa-badge-pro { background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.3); }
        .sa-badge-business { background: rgba(250,204,21,0.1); color: #fbbf24; border: 1px solid rgba(250,204,21,0.2); }
        .sa-badge-suspended { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .sa-action-btn { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.75rem; border: 1px solid var(--sa-border2); border-radius: 6px; color: var(--sa-gray2); font-size: 0.75rem; text-decoration: none; transition: all 0.2s; background: transparent; cursor: pointer; }
        .sa-action-btn:hover { border-color: var(--sa-purple); color: var(--sa-purple-light); }
        .sa-action-btn.danger:hover { border-color: var(--sa-red); color: var(--sa-red); }

        /* GRID */
        .sa-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .sa-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }

        /* SEARCH */
        .sa-search { display: flex; align-items: center; gap: 0.75rem; background: #111; border: 1px solid var(--sa-border2); border-radius: 8px; padding: 0.5rem 1rem; }
        .sa-search input { background: none; border: none; outline: none; color: var(--sa-white); font-size: 0.825rem; font-family: 'Inter', sans-serif; width: 200px; }
        .sa-search input::placeholder { color: #3f3f46; }
        .sa-search i { color: #3f3f46; font-size: 0.8rem; }

        @media(max-width:1024px){
            .sa-sidebar{width:220px;}
            .sa-main{margin-left:220px;}
            .sa-stat-grid{grid-template-columns:repeat(2,1fr);}
        }
        @media(max-width:768px){
            .sa-sidebar{display:none;}
            .sa-main{margin-left:0;}
            .sa-stat-grid{grid-template-columns:1fr;}
            .sa-grid-2,.sa-grid-3{grid-template-columns:1fr;}
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sa-sidebar">
    <div class="sa-sidebar-logo">
        <img src="/images/xenoraa/logo.png" alt="Xenoraa" style="height:24px;filter:brightness(0) invert(1);">
        <div>
            <div class="sa-logo-text">xenoraa</div>
            <div class="sa-logo-badge">Super Admin</div>
        </div>
    </div>

    <div class="sa-nav-section">
        <div class="sa-nav-label">Overview</div>
        <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="{{ route('superadmin.analytics') }}" class="sa-nav-item {{ request()->routeIs('superadmin.analytics') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Analytics
        </a>
    </div>

    <div class="sa-nav-section">
        <div class="sa-nav-label">User Management</div>
        <a href="{{ route('superadmin.users') }}" class="sa-nav-item {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> All Users
            <span class="sa-nav-badge">{{ \App\Models\User::count() }}</span>
        </a>
        <a href="{{ route('superadmin.users') }}?plan=starter" class="sa-nav-item">
            <i class="fas fa-user"></i> Starter Plan
        </a>
        <a href="{{ route('superadmin.users') }}?plan=professional" class="sa-nav-item">
            <i class="fas fa-user-tie"></i> Professional Plan
        </a>
        <a href="{{ route('superadmin.users') }}?plan=business" class="sa-nav-item">
            <i class="fas fa-building"></i> Business Pro
        </a>
    </div>

    <div class="sa-nav-section">
        <div class="sa-nav-label">Subscriptions</div>
        <a href="{{ route('superadmin.subscriptions') }}" class="sa-nav-item {{ request()->routeIs('superadmin.subscriptions*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Subscriptions
        </a>
        <a href="{{ route('superadmin.revenue') }}" class="sa-nav-item {{ request()->routeIs('superadmin.revenue') ? 'active' : '' }}">
            <i class="fas fa-dollar-sign"></i> Revenue
        </a>
    </div>

    <div class="sa-nav-section">
        <div class="sa-nav-label">Domains</div>
        <a href="{{ route('superadmin.domains') }}" class="sa-nav-item {{ request()->routeIs('superadmin.domains') ? 'active' : '' }}">
            <i class="fas fa-globe"></i> Custom Domains
        </a>
    </div>

    <div class="sa-nav-section">
        <div class="sa-nav-label">Content</div>
        <a href="{{ route('superadmin.blog') }}" class="sa-nav-item {{ request()->routeIs('superadmin.blog*') ? 'active' : '' }}">
            <i class="fas fa-pen-nib"></i> Blog Posts
        </a>
        <a href="{{ route('superadmin.showcase') }}" class="sa-nav-item {{ request()->routeIs('superadmin.showcase') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Showcase
        </a>
    </div>

    <div class="sa-nav-section">
        <div class="sa-nav-label">System</div>
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

    <div style="margin-top:auto;padding:1rem 1.5rem;border-top:1px solid var(--sa-border);">
        <a href="{{ route('admin.dashboard') }}" class="sa-nav-item" style="padding:0.5rem 0;">
            <i class="fas fa-arrow-left"></i> Back to Admin
        </a>
        <a href="{{ route('xenoraa.home') }}" class="sa-nav-item" style="padding:0.5rem 0;">
            <i class="fas fa-external-link-alt"></i> View Site
        </a>
    </div>
</aside>

{{-- MAIN --}}
<div class="sa-main">
    <div class="sa-topbar">
        <div class="sa-topbar-title">@yield('page_title', 'Dashboard')</div>
        <div class="sa-topbar-actions">
            <button class="sa-topbar-btn"><i class="fas fa-search"></i></button>
            <button class="sa-topbar-btn">
                <i class="fas fa-bell"></i>
                <span class="sa-notif-dot"></span>
            </button>
            <div class="sa-avatar">{{ substr(auth()->user()->name ?? 'S', 0, 1) }}</div>
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

@yield('scripts')
</body>
</html>
