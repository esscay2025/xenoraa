<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $adminTenant = auth()->user();
        $adminTenantId = $adminTenant?->id ?? 0;
        $adminSiteName = \App\Models\SiteSetting::getValueForTenant($adminTenantId, 'site_name', $adminTenant?->name ?? 'Admin');
        $adminFavicon  = \App\Models\SiteSetting::getValueForTenant($adminTenantId, 'favicon_path');
    @endphp
    <title>@yield('title', 'Admin') | {{ $adminSiteName }}</title>
    @if($adminFavicon)
    <link rel="shortcut icon" href="{{ $adminFavicon }}">
    @else
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(request()->routeIs('admin.crm2*') || request()->routeIs('admin.ecommerce*') || request()->routeIs('admin.accounts*'))
    <link rel="stylesheet" href="{{ asset('css/crm2.css') }}?v={{ filemtime(public_path('css/crm2.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/ecommerce.css') }}?v={{ filemtime(public_path('css/ecommerce.css')) }}">
    @endif
    <style>
        /* ══════════════════════════════════════════════════════════
           CSS VARIABLES — Dark (default) & Light
        ══════════════════════════════════════════════════════════ */
        :root, [data-theme="dark"] {
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-card: #1a1a1a;
            --bg-hover: #222222;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #666666;
            --border: #2a2a2a;
            --border-light: #333333;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --accent: #6366f1;
            --topbar-bg: #111111;
            /* Dual-rail dimensions */
            --rail-width: 60px;
            --panel-width: 220px;
            --sidebar-total: calc(var(--rail-width) + var(--panel-width));
        }
        [data-theme="light"] {
            --bg-primary: #f1f5f9;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-hover: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --border-light: #cbd5e1;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
            --info: #2563eb;
            --accent: #6366f1;
            --topbar-bg: #ffffff;
        }

        /* ══════════════════════════════════════════════════════════
           RESET & BASE
        ══════════════════════════════════════════════════════════ */
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            margin: 0; padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════════════════════════
           DUAL-RAIL SIDEBAR
        ══════════════════════════════════════════════════════════ */

        /* ── Rail (icon strip, always visible) ─────────────────── */
        .xn-rail {
            position: fixed;
            top: 0; left: 0;
            width: var(--rail-width);
            height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            z-index: 200;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
            overflow: hidden;
        }

        /* Rail brand / avatar */
        .xn-rail-brand {
            width: 100%;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            text-decoration: none;
        }
        .xn-rail-brand img {
            width: 34px; height: 34px;
            border-radius: 50%;
            object-fit: cover;
        }
        .xn-rail-brand .xn-rail-initial {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 700; color: #fff;
        }

        /* Rail nav items */
        .xn-rail-nav {
            flex: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 0;
            gap: 2px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .xn-rail-nav::-webkit-scrollbar { display: none; }

        .xn-rail-item {
            position: relative;
            width: 46px; height: 46px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.15s;
            border: none;
            background: transparent;
            gap: 3px;
            flex-shrink: 0;
        }
        .xn-rail-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }
        .xn-rail-item.active {
            background: rgba(99,102,241,0.15);
            color: var(--accent);
        }
        .xn-rail-item.active::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 50%; transform: translateY(-50%);
            width: 3px; height: 24px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }
        .xn-rail-item i {
            font-size: 1.05rem;
            line-height: 1;
        }
        .xn-rail-item .xn-rail-label {
            font-size: 0.55rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
        }

        /* Rail footer (dashboard + profile) */
        .xn-rail-footer {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 0;
            border-top: 1px solid var(--border);
            gap: 2px;
        }

        /* ── Module Panel (slide-out) ───────────────────────────── */
        .xn-panel {
            position: fixed;
            top: 0;
            left: var(--rail-width);
            width: var(--panel-width);
            height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            z-index: 190;
            display: flex;
            flex-direction: column;
            transform: translateX(calc(-1 * var(--panel-width)));
            transition: transform 0.22s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.35);
            overflow: hidden;
        }
        .xn-panel.open {
            transform: translateX(0);
        }

        /* Panel header */
        .xn-panel-header {
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            border-bottom: 1px solid var(--border);
            gap: 0.6rem;
            flex-shrink: 0;
        }
        .xn-panel-header i {
            font-size: 1rem;
            color: var(--accent);
        }
        .xn-panel-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-primary);
        }

        /* Panel scroll area */
        .xn-panel-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
        }
        .xn-panel-body::-webkit-scrollbar { width: 4px; }
        .xn-panel-body::-webkit-scrollbar-track { background: transparent; }
        .xn-panel-body::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 4px; }

        /* Panel section label */
        .xn-panel-section {
            padding: 0.6rem 1rem 0.25rem;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* Panel link */
        .xn-panel-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.825rem;
            font-weight: 500;
            transition: all 0.13s;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }
        .xn-panel-link:hover {
            color: var(--text-primary);
            background: var(--bg-hover);
        }
        .xn-panel-link.active {
            color: var(--text-primary);
            background: rgba(99,102,241,0.08);
            border-left-color: var(--accent);
        }
        .xn-panel-link i {
            width: 16px;
            text-align: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* Panel sub-group toggle */
        .xn-panel-group-btn {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            transition: all 0.13s;
            white-space: nowrap;
        }
        .xn-panel-group-btn:hover { color: var(--text-primary); background: var(--bg-hover); }
        .xn-panel-group-btn i.group-icon { width: 16px; text-align: center; font-size: 0.8rem; flex-shrink: 0; }
        .xn-panel-group-btn .xn-chevron {
            margin-left: auto;
            font-size: 0.65rem;
            transition: transform 0.2s;
            color: var(--text-muted);
        }
        .xn-panel-group-btn.open .xn-chevron { transform: rotate(180deg); }

        /* Panel sub-group panel */
        .xn-panel-group-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.22s ease;
        }
        .xn-panel-group-panel.open { max-height: 600px; }

        /* Sub-link (indented) */
        .xn-panel-sub-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.42rem 1rem 0.42rem 2.4rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.13s;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }
        .xn-panel-sub-link:hover { color: var(--text-primary); background: var(--bg-hover); }
        .xn-panel-sub-link.active {
            color: var(--text-primary);
            background: rgba(99,102,241,0.06);
            border-left-color: var(--accent);
        }
        .xn-panel-sub-link i { width: 14px; text-align: center; font-size: 0.75rem; flex-shrink: 0; }

        /* Panel divider */
        .xn-panel-divider {
            height: 1px;
            background: var(--border);
            margin: 0.4rem 0;
        }

        /* ── Main content area ──────────────────────────────────── */
        .main-content {
            margin-left: var(--rail-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.22s cubic-bezier(0.4,0,0.2,1);
        }
        /* When panel is open, push content right */
        body.xn-panel-open .main-content {
            margin-left: var(--sidebar-total);
        }

        /* ── Topbar ─────────────────────────────────────────────── */
        .topbar {
            background-color: var(--topbar-bg);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 150;
        }
        .topbar-title {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }
        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border: none;
            background: transparent;
            color: var(--text-primary);
            cursor: pointer;
            font-size: 1.1rem;
            border-radius: 6px;
        }
        .mobile-menu-btn:hover { background: var(--bg-hover); }

        /* ── Page content ───────────────────────────────────────── */
        .page-content {
            padding: 1.5rem;
            flex: 1;
        }

        /* ── Alert banners ──────────────────────────────────────── */
        .alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem; font-weight: 500;
            border-left: 4px solid;
        }
        .alert-success { background: rgba(34,197,94,0.1); border-color: var(--success); color: var(--success); }
        .alert-error   { background: rgba(239,68,68,0.1);  border-color: var(--danger);  color: var(--danger); }

        /* ── Light mode overrides ───────────────────────────────── */
        [data-theme="light"] body { background-color: var(--bg-primary); color: var(--text-primary); }
        [data-theme="light"] .xn-rail { background-color: #fff; border-right-color: var(--border); }
        [data-theme="light"] .xn-panel { background-color: #fff; border-right-color: var(--border); }
        [data-theme="light"] .xn-panel-link { color: var(--text-secondary); }
        [data-theme="light"] .xn-panel-link:hover,
        [data-theme="light"] .xn-panel-link.active { color: var(--text-primary); background-color: var(--bg-hover); }
        [data-theme="light"] .xn-panel-sub-link { color: var(--text-secondary); }
        [data-theme="light"] .xn-panel-sub-link:hover,
        [data-theme="light"] .xn-panel-sub-link.active { color: var(--text-primary); background-color: var(--bg-hover); }
        [data-theme="light"] .topbar { background-color: var(--topbar-bg); border-bottom-color: var(--border); }
        [data-theme="light"] .card { background-color: var(--bg-card); border-color: var(--border); }
        [data-theme="light"] .form-control { background-color: var(--bg-primary); border-color: var(--border); color: var(--text-primary); }
        [data-theme="light"] .btn-outline { border-color: var(--border); color: var(--text-secondary); }
        [data-theme="light"] .btn-outline:hover { color: var(--text-primary); border-color: var(--text-secondary); }

        /* ── Toggle Switch (iOS-style) ──────────────────────────── */
        .toggle-switch { position:relative; display:inline-block; width:48px; height:26px; }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .toggle-slider {
            position:absolute; cursor:pointer; inset:0;
            background:var(--border-light); border-radius:26px; transition:0.3s;
        }
        .toggle-slider:before {
            content:""; position:absolute;
            width:20px; height:20px; left:3px; bottom:3px;
            background:#fff; border-radius:50%; transition:0.3s;
        }
        .toggle-switch input:checked + .toggle-slider { background:#6366f1; }
        .toggle-switch input:checked + .toggle-slider:before { transform:translateX(22px); }

        /* ── Mode toggle button ─────────────────────────────────── */
        .mode-toggle {
            display: flex; align-items: center; gap: 0.4rem;
            background: var(--bg-hover); border: 1px solid var(--border);
            border-radius: 20px; padding: 0.3rem 0.75rem;
            cursor: pointer; font-size: 0.78rem; font-weight: 600;
            color: var(--text-secondary); transition: all 0.2s;
            white-space: nowrap;
        }
        .mode-toggle:hover { color: var(--text-primary); border-color: var(--text-secondary); }

        /* ── Mobile overlay ─────────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 180;
        }
        .sidebar-overlay.active { display: block; }

        /* ── Responsive ─────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .xn-rail { transform: translateX(-100%); transition: transform 0.3s ease; }
            .xn-rail.open { transform: translateX(0); }
            .xn-panel { left: var(--rail-width); }
            .main-content { margin-left: 0; }
            body.xn-panel-open .main-content { margin-left: 0; }
            .mobile-menu-btn { display: flex; }
        }

        /* ── Misc shared utilities ──────────────────────────────── */
        .grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; }
        @media (max-width: 1024px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
        .card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 12px; padding: 1.5rem;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem;
            font-weight: 600; cursor: pointer; border: none; text-decoration: none;
            transition: all 0.15s;
        }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
        .btn-outline:hover { color: var(--text-primary); border-color: var(--text-secondary); }
        .btn-danger { background: var(--danger); color: #fff; }
        .form-control {
            background: var(--bg-card); border: 1px solid var(--border);
            color: var(--text-primary); border-radius: 8px;
            padding: 0.5rem 0.875rem; font-size: 0.875rem; width: 100%;
        }
        .form-control:focus { outline: none; border-color: var(--accent); }
        .sidebar-divider { height: 1px; background: var(--border); margin: 0.4rem 0; }
        /* Legacy sidebar classes kept for compatibility with existing blade partials */
        .sidebar-sub-link { display:flex;align-items:center;gap:0.65rem;padding:0.42rem 1rem 0.42rem 2.4rem;color:var(--text-secondary);text-decoration:none;font-size:0.8rem;font-weight:500;transition:all 0.13s;border-left:3px solid transparent; }
        .sidebar-sub-link:hover { color:var(--text-primary);background:var(--bg-hover); }
        .sidebar-sub-link.active { color:var(--text-primary);background:rgba(99,102,241,0.06);border-left-color:var(--accent); }
        .sidebar-sub-link i { width:14px;text-align:center;font-size:0.75rem;flex-shrink:0; }
        .sidebar-sub-sub-link { padding-left: 3.2rem; }
        .sidebar-group-btn { display:flex;align-items:center;gap:0.65rem;padding:0.5rem 1rem;color:var(--text-secondary);font-size:0.825rem;font-weight:600;cursor:pointer;border:none;background:transparent;width:100%;text-align:left;transition:all 0.13s; }
        .sidebar-group-btn:hover { color:var(--text-primary);background:var(--bg-hover); }
        .sidebar-group-btn .group-chevron { margin-left:auto;font-size:0.65rem;transition:transform 0.2s;color:var(--text-muted); }
        .sidebar-group-btn.open .group-chevron { transform:rotate(180deg); }
        .sidebar-group-panel { max-height:0;overflow:hidden;transition:max-height 0.22s ease; }
        .sidebar-group-panel.open { max-height:1200px; }
        .sidebar-sub-group-btn { display:flex;align-items:center;gap:0.65rem;padding:0.42rem 1rem 0.42rem 2.4rem;color:var(--text-secondary);font-size:0.8rem;font-weight:600;cursor:pointer;border:none;background:transparent;width:100%;text-align:left;transition:all 0.13s; }
        .sidebar-sub-group-btn:hover { color:var(--text-primary);background:var(--bg-hover); }
        .sidebar-sub-group-btn .group-chevron { margin-left:auto;font-size:0.6rem;transition:transform 0.2s;color:var(--text-muted); }
        .sidebar-sub-group-btn.open .group-chevron { transform:rotate(180deg); }
        .group-icon { width:16px;text-align:center;font-size:0.8rem;flex-shrink:0; }
    </style>
    @stack('styles')
</head>
<body>

@php
    $tenantUser = auth()->user();
    $isOwner = $tenantUser?->isAdmin() || $tenantUser?->isSuperAdmin();
    $canSee = function(string $module) use ($tenantUser, $isOwner): bool {
        if ($tenantUser?->isSuperAdmin()) return true;
        if ($isOwner) return $tenantUser?->planHasModule($module) ?? false;
        $owner = $tenantUser?->tenantOwner ?? $tenantUser;
        if ($owner && !$owner->planHasModule($module)) return false;
        return $tenantUser?->hasModuleAccess($module) ?? false;
    };
    $tenantSiteUrl = $tenantUser->custom_domain
        ? 'https://' . $tenantUser->custom_domain
        : url('/' . $tenantUser->username);

    // Determine active module for rail highlight
    $activeModule = '';
    if (request()->routeIs('admin.dashboard')) $activeModule = 'dashboard';
    elseif (request()->routeIs('admin.users*') || request()->routeIs('admin.roles*')) $activeModule = 'admin';
    elseif (request()->routeIs('admin.crm2*') || request()->routeIs('admin.newcrm*')) $activeModule = 'crm';
    elseif (request()->routeIs('admin.crm.*') && !request()->routeIs('admin.crm2*')) $activeModule = 'ai';
    elseif (request()->routeIs('admin.ecommerce*')) $activeModule = 'ecommerce';
    elseif (request()->routeIs('admin.pos*')) $activeModule = 'pos';
    elseif (request()->routeIs('admin.accounts*')) $activeModule = 'accounts';
    elseif (request()->routeIs('admin.site*') || request()->routeIs('admin.settings*') || request()->routeIs('admin.blog*') || request()->routeIs('admin.forum*') || request()->routeIs('admin.jobs*') || request()->routeIs('admin.newsletter*') || request()->routeIs('admin.calendar*')) $activeModule = 'site';
@endphp

<!-- ══════════════════════════════════════════════════════════
     ICON RAIL (60px, always visible)
══════════════════════════════════════════════════════════ -->
<div class="xn-rail" id="xnRail">

    {{-- Brand / Avatar --}}
    <a href="{{ $tenantSiteUrl }}" class="xn-rail-brand" title="View Your Site" target="_blank">
        @if($tenantUser->avatar)
            <img src="{{ asset('storage/' . $tenantUser->avatar) }}" alt="{{ $tenantUser->name }}">
        @else
            <div class="xn-rail-initial">{{ strtoupper(substr($tenantUser->name,0,1)) }}</div>
        @endif
    </a>

    {{-- Module Icons --}}
    <div class="xn-rail-nav">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="xn-rail-item {{ $activeModule === 'dashboard' ? 'active' : '' }}"
           title="Dashboard">
            <i class="fas fa-tachometer-alt"></i>
            <span class="xn-rail-label">Home</span>
        </a>

        {{-- Administration --}}
        @if($isOwner)
        <button class="xn-rail-item {{ $activeModule === 'admin' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-admin', this)"
                title="Administration">
            <i class="fas fa-shield-alt"></i>
            <span class="xn-rail-label">Admin</span>
        </button>
        @endif

        {{-- CRM --}}
        @if($canSee('crm'))
        <button class="xn-rail-item {{ $activeModule === 'crm' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-crm', this)"
                title="CRM">
            <i class="fas fa-handshake"></i>
            <span class="xn-rail-label">CRM</span>
        </button>
        @endif

        {{-- E-Commerce --}}
        @if($canSee('ecommerce'))
        <button class="xn-rail-item {{ $activeModule === 'ecommerce' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-ecommerce', this)"
                title="E-Commerce">
            <i class="fas fa-shopping-cart"></i>
            <span class="xn-rail-label">Store</span>
        </button>
        @endif

        {{-- AI Hub --}}
        @if($canSee('ai'))
        <button class="xn-rail-item {{ $activeModule === 'ai' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-ai', this)"
                title="AI Hub">
            <i class="fas fa-robot"></i>
            <span class="xn-rail-label">AI Hub</span>
        </button>
        @endif

        {{-- Point of Sale --}}
        @if($canSee('pos'))
        <button class="xn-rail-item {{ $activeModule === 'pos' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-pos', this)"
                title="Point of Sale">
            <i class="fas fa-cash-register"></i>
            <span class="xn-rail-label">POS</span>
        </button>
        @endif

        {{-- Accounts (Finance) --}}
        @if(('accounts'))
        <button class="xn-rail-item {{ $activeModule === 'accounts' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-accounts', this)"
                title="Accounts">
            <i class="fas fa-chart-pie"></i>
            <span class="xn-rail-label">ACCTS</span>
        </button>
        @endif

        {{-- Site Builder --}}
        @if($canSee('site_builder'))
        <button class="xn-rail-item {{ $activeModule === 'site' ? 'active' : '' }}"
                onclick="xnOpenPanel('panel-site', this)"
                title="Site Builder">
            <i class="fas fa-paint-brush"></i>
            <span class="xn-rail-label">Site</span>
        </button>
        @endif

    </div>{{-- /.xn-rail-nav --}}

    {{-- Rail Footer --}}
    <div class="xn-rail-footer">
        {{-- View Site --}}
        <a href="{{ $tenantSiteUrl }}" class="xn-rail-item" title="View Site" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            <span class="xn-rail-label">Site</span>
        </a>
    </div>

</div>{{-- /.xn-rail --}}

<!-- ══════════════════════════════════════════════════════════
     MODULE PANELS (slide-out, one per module)
══════════════════════════════════════════════════════════ -->

{{-- ── Administration Panel ───────────────────────────── --}}
@if($isOwner)
<div class="xn-panel" id="panel-admin">
    <div class="xn-panel-header">
        <i class="fas fa-shield-alt"></i>
        <span class="xn-panel-title">Administration</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.users.index') }}" class="xn-panel-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"><i class="fas fa-list"></i> Staff Users</a>
        <a href="{{ route('admin.users.create') }}" class="xn-panel-link"><i class="fas fa-user-plus"></i> Add Staff User</a>
        <a href="{{ route('admin.roles.index') }}" class="xn-panel-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}"><i class="fas fa-user-tag"></i> Roles &amp; Permissions</a>
    </div>
</div>
@endif

{{-- ── CRM Panel ───────────────────────────────────────── --}}
@if($canSee('crm'))
@php
    $crmSalesActive       = request()->routeIs('admin.crm2.sales*');
    $crmActivitiesActive  = request()->routeIs('admin.crm2.activities*');
    $crmInventoryActive   = request()->routeIs('admin.crm2.inventory*');
    $crmSupportActive     = request()->routeIs('admin.crm2.support*');
    $crmServicesActive    = request()->routeIs('admin.crm2.services*');
    $crmProjectsActive    = request()->routeIs('admin.crm2.projects*');
    $crmIntegrationsActive= request()->routeIs('admin.crm2.integrations*');
    $crmSettingsActive    = request()->routeIs('admin.crm2.settings*');
@endphp
<div class="xn-panel" id="panel-crm">
    <div class="xn-panel-header">
        <i class="fas fa-handshake"></i>
        <span class="xn-panel-title">CRM</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.crm2.analysis') }}" class="xn-panel-link {{ request()->routeIs('admin.crm2.analysis') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Analysis</a>
        <a href="{{ route('admin.crm2.reports') }}" class="xn-panel-link {{ request()->routeIs('admin.crm2.reports') ? 'active' : '' }}"><i class="fas fa-file-chart-line"></i> Reports</a>
        <div class="xn-panel-divider"></div>

        {{-- Sales --}}
        <button class="xn-panel-group-btn {{ $crmSalesActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-sales', this)">
            <i class="fas fa-chart-bar group-icon"></i> Sales <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmSalesActive ? 'open' : '' }}" id="xng-crm-sales">
            <a href="{{ route('admin.crm2.sales.leads') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.sales.leads*') ? 'active' : '' }}"><i class="fas fa-user-tag"></i> Leads</a>
            <a href="{{ route('admin.crm2.sales.contacts') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.sales.contacts*') ? 'active' : '' }}"><i class="fas fa-address-book"></i> Contacts</a>
            <a href="{{ route('admin.crm2.sales.accounts') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.sales.accounts*') ? 'active' : '' }}"><i class="fas fa-building"></i> Accounts</a>
            <a href="{{ route('admin.crm2.sales.deals') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.sales.deals*') ? 'active' : '' }}"><i class="fas fa-funnel-dollar"></i> Deals</a>
            <a href="{{ route('admin.crm2.sales.forecasts') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.sales.forecasts*') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Forecasts</a>
        </div>

        {{-- Activities --}}
        <button class="xn-panel-group-btn {{ $crmActivitiesActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-activities', this)">
            <i class="fas fa-tasks group-icon"></i> Activities <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmActivitiesActive ? 'open' : '' }}" id="xng-crm-activities">
            <a href="{{ route('admin.crm2.activities.tasks') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.activities.tasks*') ? 'active' : '' }}"><i class="fas fa-check-square"></i> Tasks</a>
            <a href="{{ route('admin.crm2.activities.meetings') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.activities.meetings*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Meetings</a>
            <a href="{{ route('admin.crm2.activities.calls') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.activities.calls*') ? 'active' : '' }}"><i class="fas fa-phone-alt"></i> Calls</a>
        </div>

        {{-- Inventory --}}
        <button class="xn-panel-group-btn {{ $crmInventoryActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-inventory', this)">
            <i class="fas fa-boxes group-icon"></i> Inventory <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmInventoryActive ? 'open' : '' }}" id="xng-crm-inventory">
            <a href="{{ route('admin.crm2.inventory.price-books') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.price-books*') ? 'active' : '' }}"><i class="fas fa-tag"></i> Price Books</a>
            <a href="{{ route('admin.crm2.inventory.quotes') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.quotes*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Quotes</a>
            <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.sales-orders*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Sales Orders</a>
            <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.purchase-orders*') ? 'active' : '' }}"><i class="fas fa-truck"></i> Purchase Orders</a>
            <a href="{{ route('admin.crm2.inventory.invoices') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.invoices*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
            <a href="{{ route('admin.crm2.inventory.vendors') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.vendors*') ? 'active' : '' }}"><i class="fas fa-store"></i> Vendors</a>
            <a href="{{ route('admin.crm2.inventory.products') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.inventory.products*') ? 'active' : '' }}"><i class="fas fa-box-open"></i> Products</a>
        </div>

        {{-- Support --}}
        <button class="xn-panel-group-btn {{ $crmSupportActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-support', this)">
            <i class="fas fa-headset group-icon"></i> Support <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmSupportActive ? 'open' : '' }}" id="xng-crm-support">
            <a href="{{ route('admin.crm2.support.cases') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.support.cases*') ? 'active' : '' }}"><i class="fas fa-ticket-alt"></i> Cases</a>
            <a href="{{ route('admin.crm2.support.solutions') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.support.solutions*') ? 'active' : '' }}"><i class="fas fa-lightbulb"></i> Solutions</a>
        </div>

        {{-- Services --}}
        <button class="xn-panel-group-btn {{ $crmServicesActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-services', this)">
            <i class="fas fa-concierge-bell group-icon"></i> Services <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmServicesActive ? 'open' : '' }}" id="xng-crm-services">
            <a href="{{ route('admin.crm2.services.catalog') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.services.catalog*') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> Service Catalog</a>
            <a href="{{ route('admin.crm2.services.bookings') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.services.bookings*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Bookings</a>
        </div>

        {{-- Projects --}}
        <button class="xn-panel-group-btn {{ $crmProjectsActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-projects', this)">
            <i class="fas fa-project-diagram group-icon"></i> Projects <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmProjectsActive ? 'open' : '' }}" id="xng-crm-projects">
            <a href="{{ route('admin.crm2.projects.list') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.projects.list*') ? 'active' : '' }}"><i class="fas fa-folder-open"></i> Projects</a>
            <a href="{{ route('admin.crm2.projects.tasks') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.projects.tasks*') ? 'active' : '' }}"><i class="fas fa-tasks"></i> Tasks</a>
        </div>

        <div class="xn-panel-divider"></div>

        {{-- Integrations --}}
        <button class="xn-panel-group-btn {{ $crmIntegrationsActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-integrations', this)">
            <i class="fas fa-plug group-icon"></i> Integrations <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmIntegrationsActive ? 'open' : '' }}" id="xng-crm-integrations">
            <a href="{{ route('admin.crm2.integrations.mail-config') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.integrations.mail-config*') ? 'active' : '' }}"><i class="fas fa-envelope-open-text"></i> Mail Config</a>
        </div>

        {{-- Settings --}}
        <button class="xn-panel-group-btn {{ $crmSettingsActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-crm-settings', this)">
            <i class="fas fa-cog group-icon"></i> Settings <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $crmSettingsActive ? 'open' : '' }}" id="xng-crm-settings">
            <a href="{{ route('admin.crm2.settings.mail-templates') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.crm2.settings.mail-templates*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Mail Templates</a>
        </div>
    </div>
</div>
@endif

{{-- ── E-Commerce Panel ────────────────────────────────── --}}
@if($canSee('ecommerce'))
@php
    $ecomProductsActive     = request()->routeIs('admin.ecommerce.products*') || request()->routeIs('admin.ecommerce.categories*') || request()->routeIs('admin.ecommerce.reviews*');
    $ecomIntegrationsActive = request()->routeIs('admin.ecommerce.integrations.*');
    $ecomSettingsActive     = request()->routeIs('admin.ecommerce.settings.*');
@endphp
<div class="xn-panel" id="panel-ecommerce">
    <div class="xn-panel-header">
        <i class="fas fa-shopping-cart"></i>
        <span class="xn-panel-title">E-Commerce</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.ecommerce.dashboard') }}" class="xn-panel-link {{ request()->routeIs('admin.ecommerce.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Dashboard</a>

        {{-- Products --}}
        <button class="xn-panel-group-btn {{ $ecomProductsActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-ecom-products', this)">
            <i class="fas fa-box-open group-icon"></i> Products <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $ecomProductsActive ? 'open' : '' }}" id="xng-ecom-products">
            <a href="{{ route('admin.ecommerce.products') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.ecommerce.products') || (request()->routeIs('admin.ecommerce.products*') && !request()->routeIs('admin.ecommerce.products.create')) ? 'active' : '' }}"><i class="fas fa-list"></i> All Products</a>
            <a href="{{ route('admin.ecommerce.products.create') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.ecommerce.products.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> Add Product</a>
            <a href="{{ route('admin.ecommerce.categories') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.ecommerce.categories*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Categories</a>
            <a href="{{ route('admin.ecommerce.reviews') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.ecommerce.reviews*') ? 'active' : '' }}"><i class="fas fa-star"></i> Reviews</a>
        </div>

        {{-- Integrations --}}
        <button class="xn-panel-group-btn {{ $ecomIntegrationsActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-ecom-integrations', this)">
            <i class="fas fa-plug group-icon"></i> Integrations <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $ecomIntegrationsActive ? 'open' : '' }}" id="xng-ecom-integrations">
            <a href="{{ route('admin.ecommerce.integrations.mail-config') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.ecommerce.integrations.*') ? 'active' : '' }}"><i class="fas fa-envelope-open-text"></i> Mail Config</a>
        </div>

        {{-- Settings --}}
        <button class="xn-panel-group-btn {{ $ecomSettingsActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-ecom-settings', this)">
            <i class="fas fa-cog group-icon"></i> Settings <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $ecomSettingsActive ? 'open' : '' }}" id="xng-ecom-settings">
            <a href="{{ route('admin.ecommerce.settings.mail-templates') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.ecommerce.settings.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Mail Templates</a>
        </div>

        <a href="{{ route('admin.ecommerce.store-config') }}" class="xn-panel-link {{ request()->routeIs('admin.ecommerce.store-config*') ? 'active' : '' }}"><i class="fas fa-store"></i> Store Config</a>
    </div>
</div>
@endif

{{-- ── AI Hub Panel ────────────────────────────────────── --}}
@if($canSee('ai'))
<div class="xn-panel" id="panel-ai">
    <div class="xn-panel-header">
        <i class="fas fa-robot"></i>
        <span class="xn-panel-title">AI Hub</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.crm.ai.toggle') }}" class="xn-panel-link {{ request()->routeIs('admin.crm.ai*') ? 'active' : '' }}"><i class="fas fa-robot"></i> AI Assistant</a>
        <a href="{{ route('admin.crm.training') }}" class="xn-panel-link {{ request()->routeIs('admin.crm.training*') ? 'active' : '' }}"><i class="fas fa-brain"></i> Train AI</a>
        <a href="{{ route('admin.crm.conversations') }}" class="xn-panel-link {{ request()->routeIs('admin.crm.conversation*') ? 'active' : '' }}"><i class="fas fa-comments"></i> AI Conversations</a>
    </div>
</div>
@endif

{{-- ── Point of Sale Panel ─────────────────────────────── --}}
@if($canSee('pos'))
<div class="xn-panel" id="panel-pos">
    <div class="xn-panel-header">
        <i class="fas fa-cash-register"></i>
        <span class="xn-panel-title">Point of Sale</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.pos.terminal') }}" class="xn-panel-link {{ request()->routeIs('admin.pos.terminal') ? 'active' : '' }}" target="_blank"><i class="fas fa-desktop"></i> POS Terminal</a>
        <a href="{{ route('admin.pos.orders') }}" class="xn-panel-link {{ request()->routeIs('admin.pos.orders') ? 'active' : '' }}"><i class="fas fa-receipt"></i> Orders</a>
        <a href="{{ route('admin.pos.sessions') }}" class="xn-panel-link {{ request()->routeIs('admin.pos.sessions*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Sessions</a>
        <a href="{{ route('admin.pos.dashboard') }}" class="xn-panel-link {{ request()->routeIs('admin.pos.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> POS Reports</a>
        <a href="{{ route('admin.pos.settings') }}" class="xn-panel-link {{ request()->routeIs('admin.pos.settings*') ? 'active' : '' }}"><i class="fas fa-cog"></i> POS Settings</a>
    </div>
</div>
@endif

{{-- ── Accounts (Finance) Panel ───────────────────────── --}}
@if($canSee('accounts'))
@php
    $accBankingActive    = request()->routeIs('admin.accounts.bank-accounts*') || request()->routeIs('admin.accounts.transactions*');
    $accMoneyActive      = request()->routeIs('admin.accounts.income*') || request()->routeIs('admin.accounts.expenses*');
    $accAccountingActive = request()->routeIs('admin.accounts.coa*') || request()->routeIs('admin.accounts.journal*') || request()->routeIs('admin.accounts.reports*');
@endphp
<div class="xn-panel" id="panel-accounts">
    <div class="xn-panel-header">
        <i class="fas fa-chart-pie"></i>
        <span class="xn-panel-title">Accounts</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.accounts.dashboard') }}" class="xn-panel-link {{ request()->routeIs('admin.accounts.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        {{-- Banking --}}
        <button class="xn-panel-group-btn {{ $accBankingActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-acc-banking', this)">
            <i class="fas fa-university group-icon"></i> Banking <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $accBankingActive ? 'open' : '' }}" id="xng-acc-banking">
            <a href="{{ route('admin.accounts.bank-accounts') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.bank-accounts*') ? 'active' : '' }}"><i class="fas fa-university"></i> Bank Accounts</a>
            <a href="{{ route('admin.accounts.transactions') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.transactions*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> Transactions</a>
        </div>
        {{-- Money In / Out --}}
        <button class="xn-panel-group-btn {{ $accMoneyActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-acc-money', this)">
            <i class="fas fa-wallet group-icon"></i> Money In / Out <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $accMoneyActive ? 'open' : '' }}" id="xng-acc-money">
            <a href="{{ route('admin.accounts.income') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.income*') ? 'active' : '' }}"><i class="fas fa-arrow-circle-down"></i> Income</a>
            <a href="{{ route('admin.accounts.expenses') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.expenses*') ? 'active' : '' }}"><i class="fas fa-arrow-circle-up"></i> Expenses</a>
        </div>
        {{-- Accounting --}}
        <button class="xn-panel-group-btn {{ $accAccountingActive ? 'open' : '' }}" onclick="xnToggleGroup('xng-acc-accounting', this)">
            <i class="fas fa-book group-icon"></i> Accounting <i class="fas fa-chevron-down xn-chevron"></i>
        </button>
        <div class="xn-panel-group-panel {{ $accAccountingActive ? 'open' : '' }}" id="xng-acc-accounting">
            <a href="{{ route('admin.accounts.coa') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.coa*') ? 'active' : '' }}"><i class="fas fa-sitemap"></i> Chart of Accounts</a>
            <a href="{{ route('admin.accounts.journal') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.journal*') ? 'active' : '' }}"><i class="fas fa-book"></i> Journal Entries</a>
            <a href="{{ route('admin.accounts.reports') }}" class="xn-panel-sub-link {{ request()->routeIs('admin.accounts.reports*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar"></i> Reports</a>
        </div>
    </div>
</div>
@endif

{{-- ── Site Builder Panel ──────────────────────────────── --}}
@if($canSee('site_builder'))
<div class="xn-panel" id="panel-site">
    <div class="xn-panel-header">
        <i class="fas fa-paint-brush"></i>
        <span class="xn-panel-title">Site Builder</span>
        <button onclick="xnClosePanel()" style="margin-left:auto;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.9rem;"><i class="fas fa-times"></i></button>
    </div>
    <div class="xn-panel-body">
        <a href="{{ route('admin.site.index') }}" class="xn-panel-link {{ request()->routeIs('admin.site.index') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Site Builder Hub</a>
        <a href="{{ route('admin.site.pages') }}" class="xn-panel-link {{ request()->routeIs('admin.site.pages*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Page Manager</a>
        <a href="{{ route('admin.site.menu') }}" class="xn-panel-link {{ request()->routeIs('admin.site.menu*') ? 'active' : '' }}"><i class="fas fa-bars"></i> Menu Builder</a>
        <a href="{{ route('admin.site.domain') }}" class="xn-panel-link {{ request()->routeIs('admin.site.domain*') ? 'active' : '' }}"><i class="fas fa-globe"></i> Domain Config</a>
        <a href="{{ route('admin.settings.index') }}" class="xn-panel-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="fas fa-sliders-h"></i> Site Settings</a>
        <div class="xn-panel-divider"></div>
        @if($canSee('content'))
        <a href="{{ route('admin.blog.index') }}" class="xn-panel-link {{ request()->routeIs('admin.blog*') || request()->routeIs('admin.forum*') ? 'active' : '' }}"><i class="fas fa-pen-nib"></i> Content</a>
        @endif
        @if($canSee('recruitment'))
        <a href="{{ route('admin.jobs.index') }}" class="xn-panel-link {{ request()->routeIs('admin.jobs*') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> Recruitment</a>
        @endif
        <a href="{{ route('admin.newsletter.index') }}" class="xn-panel-link {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Newsletter</a>
        <a href="{{ route('admin.calendar.index') }}" class="xn-panel-link {{ request()->routeIs('admin.calendar*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Calendar &amp; Notes</a>
    </div>
</div>
@endif

<!-- ══════════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════════ -->
<div class="main-content" id="xnMainContent">
    @php
        $viewSiteUrl = $tenantUser->custom_domain
            ? 'https://' . $tenantUser->custom_domain
            : url('/' . $tenantUser->username);
        $topbarChatbotEnabled = \App\Models\SiteSetting::getValueForTenant($tenantUser->id, 'chatbot_enabled', '1');
        $isImpersonating = session()->has('impersonating_customer_id');
    @endphp

    {{-- Exit Impersonation Banner --}}
    @if($isImpersonating)
    <div style="background:#f59e0b;color:#000;padding:0.6rem 2rem;display:flex;align-items:center;justify-content:space-between;font-size:0.875rem;font-weight:600;">
        <span><i class="fas fa-user-secret" style="margin-right:0.5rem;"></i>You are viewing as <strong>{{ $tenantUser->name }}</strong> (@{{ $tenantUser->username }})</span>
        <a href="{{ route('superadmin.exit-impersonation') }}" style="background:#000;color:#f59e0b;padding:0.35rem 1rem;border-radius:6px;text-decoration:none;font-size:0.8rem;">
            <i class="fas fa-sign-out-alt"></i> Exit Impersonation
        </a>
    </div>
    @endif

    <div class="topbar">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <button class="mobile-menu-btn" id="adminMenuToggle" onclick="xnMobileToggle()">
                <i class="fas fa-bars" id="adminMenuIcon"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <button class="mode-toggle" id="aiToggleBtn" onclick="quickToggleAI()" title="Toggle AI Assistant on/off"
                style="{{ $topbarChatbotEnabled == '1' ? 'color:#22c55e;border-color:#22c55e;' : 'color:#ef4444;border-color:#ef4444;' }}">
                <i class="fas fa-robot"></i>
                <span id="aiToggleLabel">{{ $topbarChatbotEnabled == '1' ? 'AI On' : 'AI Off' }}</span>
            </button>
            <button class="mode-toggle" id="modeToggleBtn" onclick="toggleDashboardMode()" title="Toggle light/dark mode">
                <i id="modeIcon" class="fas fa-sun"></i>
                <span id="modeLabel">Light</span>
            </button>
            <a href="{{ route('admin.pos.terminal') }}" class="btn btn-sm" target="_blank" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;font-weight:600;">
                <i class="fas fa-cash-register"></i> View POS
            </a>
            <a href="{{ $viewSiteUrl }}" class="btn btn-outline btn-sm" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>

            {{-- Profile Dropdown --}}
            <div id="profileDropdownWrap" style="position:relative;">
                <button onclick="toggleProfileDropdown()" style="display:flex;align-items:center;gap:0.5rem;background:var(--bg-card);border:1px solid var(--border);border-radius:8px;padding:0.35rem 0.75rem;cursor:pointer;color:var(--text-primary);">
                    @if($tenantUser->avatar)
                        <img src="{{ asset('storage/' . $tenantUser->avatar) }}" alt="{{ $tenantUser->name }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                    @else
                        <span style="width:28px;height:28px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#fff;">{{ strtoupper(substr($tenantUser->name,0,1)) }}</span>
                    @endif
                    <span style="font-size:0.8rem;font-weight:600;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $tenantUser->username ?? $tenantUser->name }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.65rem;color:var(--text-muted);"></i>
                </button>
                <div id="profileDropdownMenu" style="display:none;position:absolute;right:0;top:calc(100% + 8px);background:var(--bg-card);border:1px solid var(--border);border-radius:10px;min-width:220px;box-shadow:0 8px 24px rgba(0,0,0,0.3);z-index:999;overflow:hidden;">
                    <div style="padding:1rem;border-bottom:1px solid var(--border);">
                        <div style="font-size:0.875rem;font-weight:700;">{{ $tenantUser->name }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $tenantUser->username ? '@'.$tenantUser->username : '—' }}</div>
                        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:0.2rem;">{{ $tenantUser->email }}</div>
                    </div>
                    <div style="padding:0.5rem 0;">
                        <a href="{{ route('admin.settings.index') }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 1rem;font-size:0.8rem;color:var(--text-primary);text-decoration:none;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-user-edit" style="width:16px;color:var(--text-muted);"></i> Edit Profile
                        </a>
                        <a href="{{ route('admin.settings.index') }}#change-password" style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 1rem;font-size:0.8rem;color:var(--text-primary);text-decoration:none;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-key" style="width:16px;color:var(--text-muted);"></i> Change Password
                        </a>
                        <a href="{{ route('admin.settings.index') }}#subscription" style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 1rem;font-size:0.8rem;color:var(--text-primary);text-decoration:none;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-credit-card" style="width:16px;color:var(--text-muted);"></i> View Subscription
                        </a>
                    </div>
                    <div style="padding:0.5rem 0;border-top:1px solid var(--border);">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 1rem;font-size:0.8rem;color:#ef4444;background:transparent;border:none;cursor:pointer;width:100%;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-sign-out-alt" style="width:16px;"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Mobile overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="xnMobileClose()"></div>

<script>
    // ── Theme init ────────────────────────────────────────────
    (function() {
        const saved = localStorage.getItem('xenoraa_dashboard_mode') || 'dark';
        document.documentElement.setAttribute('data-theme', saved);
    })();

    function toggleDashboardMode() {
        const current = document.documentElement.getAttribute('data-theme') || 'dark';
        const next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('xenoraa_dashboard_mode', next);
        updateModeBtn(next);
        fetch('{{ route("admin.site.mode") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: JSON.stringify({ mode: next })
        }).catch(() => {});
    }

    function updateModeBtn(mode) {
        const icon = document.getElementById('modeIcon');
        const label = document.getElementById('modeLabel');
        if (!icon || !label) return;
        if (mode === 'light') { icon.className = 'fas fa-moon'; label.textContent = 'Dark'; }
        else { icon.className = 'fas fa-sun'; label.textContent = 'Light'; }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mode = localStorage.getItem('xenoraa_dashboard_mode') || 'dark';
        document.documentElement.setAttribute('data-theme', mode);
        updateModeBtn(mode);
    });

    // ── Dual-Rail Panel Logic ─────────────────────────────────
    let _xnActivePanel = null;
    let _xnActiveRailBtn = null;

    function xnOpenPanel(panelId, railBtn) {
        const panel = document.getElementById(panelId);
        if (!panel) return;

        // If clicking the same panel that's already open → close it
        if (_xnActivePanel === panel && panel.classList.contains('open')) {
            xnClosePanel();
            return;
        }

        // Close any open panel first
        if (_xnActivePanel) {
            _xnActivePanel.classList.remove('open');
        }
        if (_xnActiveRailBtn) {
            _xnActiveRailBtn.classList.remove('active');
        }

        // Open new panel
        panel.classList.add('open');
        document.body.classList.add('xn-panel-open');
        _xnActivePanel = panel;
        _xnActiveRailBtn = railBtn;
        if (railBtn) railBtn.classList.add('active');

        // Save to localStorage
        localStorage.setItem('xenoraa_active_panel', panelId);
    }

    function xnClosePanel() {
        if (_xnActivePanel) {
            _xnActivePanel.classList.remove('open');
        }
        if (_xnActiveRailBtn) {
            _xnActiveRailBtn.classList.remove('active');
        }
        document.body.classList.remove('xn-panel-open');
        _xnActivePanel = null;
        _xnActiveRailBtn = null;
        localStorage.removeItem('xenoraa_active_panel');
    }

    // Restore open panel on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Determine active module from PHP-set class on rail items
        const activeRailItem = document.querySelector('.xn-rail-item.active[onclick]');
        if (activeRailItem) {
            const match = activeRailItem.getAttribute('onclick')?.match(/xnOpenPanel\('([^']+)'/);
            if (match) {
                const panel = document.getElementById(match[1]);
                if (panel) {
                    panel.classList.add('open');
                    document.body.classList.add('xn-panel-open');
                    _xnActivePanel = panel;
                    _xnActiveRailBtn = activeRailItem;
                }
            }
        }
    });

    // ── Sub-group toggle inside panel ────────────────────────
    function xnToggleGroup(groupId, btn) {
        const panel = document.getElementById(groupId);
        if (!panel) return;
        const isOpen = panel.classList.contains('open');
        panel.classList.toggle('open', !isOpen);
        btn.classList.toggle('open', !isOpen);
    }

    // Legacy compatibility (some blades may still call toggleSidebarGroup)
    function toggleSidebarGroup(panelId, btn) {
        xnToggleGroup(panelId, btn);
    }

    // ── Mobile toggle ─────────────────────────────────────────
    function xnMobileToggle() {
        const rail = document.getElementById('xnRail');
        const overlay = document.getElementById('sidebarOverlay');
        const icon = document.getElementById('adminMenuIcon');
        rail.classList.toggle('open');
        overlay.classList.toggle('active');
        icon.className = rail.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
    }

    function xnMobileClose() {
        const rail = document.getElementById('xnRail');
        const overlay = document.getElementById('sidebarOverlay');
        const icon = document.getElementById('adminMenuIcon');
        rail.classList.remove('open');
        overlay.classList.remove('active');
        icon.className = 'fas fa-bars';
        xnClosePanel();
    }

    // Legacy compatibility
    function toggleAdminSidebar() { xnMobileToggle(); }

    // ── Profile Dropdown ──────────────────────────────────────
    function toggleProfileDropdown() {
        const menu = document.getElementById('profileDropdownMenu');
        if (!menu) return;
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('profileDropdownWrap');
        const menu = document.getElementById('profileDropdownMenu');
        if (wrap && menu && !wrap.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    // ── Quick AI Toggle ───────────────────────────────────────
    let _aiEnabled = {{ $topbarChatbotEnabled == '1' ? 'true' : 'false' }};
    function quickToggleAI() {
        _aiEnabled = !_aiEnabled;
        const btn   = document.getElementById('aiToggleBtn');
        const label = document.getElementById('aiToggleLabel');
        if (_aiEnabled) {
            btn.style.color = '#22c55e'; btn.style.borderColor = '#22c55e';
            label.textContent = 'AI On';
        } else {
            btn.style.color = '#ef4444'; btn.style.borderColor = '#ef4444';
            label.textContent = 'AI Off';
        }
        fetch('{{ route("admin.crm.ai.toggle.save") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: JSON.stringify({ chatbot_enabled: _aiEnabled ? '1' : '0' })
        }).catch(() => {});
    }
</script>
@stack('scripts')
</body>
</html>
