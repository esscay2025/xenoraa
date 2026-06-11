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
    @if(request()->routeIs('admin.crm2*'))
    <link rel="stylesheet" href="{{ asset('css/crm2.css') }}?v={{ filemtime(public_path('css/crm2.css')) }}">
    @endif
    <style>
        /* ── Dark mode (default) ─────────────────── */
        :root, [data-theme="dark"] {
            --bg-primary: #0a0a0a; --bg-secondary: #111111; --bg-card: #1a1a1a; --bg-hover: #222222;
            --text-primary: #ffffff; --text-secondary: #a0a0a0; --text-muted: #666666;
            --border: #2a2a2a; --border-light: #333333;
            --success: #22c55e; --danger: #ef4444; --warning: #f59e0b; --info: #3b82f6;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 64px;
            --accent: #6366f1;
            --topbar-bg: #111111;
        }
        /* ── Light mode ──────────────────────────── */
        [data-theme="light"] {
            --bg-primary: #f1f5f9; --bg-secondary: #ffffff; --bg-card: #ffffff; --bg-hover: #f8fafc;
            --text-primary: #0f172a; --text-secondary: #475569; --text-muted: #94a3b8;
            --border: #e2e8f0; --border-light: #cbd5e1;
            --success: #16a34a; --danger: #dc2626; --warning: #d97706; --info: #2563eb;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 64px;
            --accent: #6366f1;
            --topbar-bg: #ffffff;
        }
        [data-theme="light"] body { background-color: var(--bg-primary); color: var(--text-primary); }
        [data-theme="light"] .sidebar { background-color: #fff; border-right-color: var(--border); }
        [data-theme="light"] .sidebar-flyout { background-color: #fff; }
        [data-theme="light"] .sidebar-link { color: var(--text-secondary); }
        [data-theme="light"] .sidebar-link:hover, [data-theme="light"] .sidebar-link.active { color: var(--text-primary); background-color: var(--bg-hover); }
        [data-theme="light"] .sidebar-group-btn { color: var(--text-secondary); }
        [data-theme="light"] .sidebar-group-btn:hover { color: var(--text-primary); background-color: var(--bg-hover); }
        [data-theme="light"] .sidebar-sub-link { color: var(--text-secondary); }
        [data-theme="light"] .sidebar-sub-link:hover, [data-theme="light"] .sidebar-sub-link.active { color: var(--text-primary); background-color: var(--bg-hover); }
        [data-theme="light"] .topbar { background-color: var(--topbar-bg); border-bottom-color: var(--border); }
        [data-theme="light"] .card { background-color: var(--bg-card); border-color: var(--border); }
        [data-theme="light"] .form-control { background-color: var(--bg-primary); border-color: var(--border); color: var(--text-primary); }
        [data-theme="light"] .btn-outline { border-color: var(--border); color: var(--text-secondary); }
        [data-theme="light"] .btn-outline:hover { color: var(--text-primary); border-color: var(--text-secondary); }
        /* Toggle Switch (iOS-style) */
        .toggle-switch { position:relative; display:inline-block; width:48px; height:26px; }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .toggle-slider {
            position:absolute; cursor:pointer; inset:0;
            background:var(--border-light); border-radius:26px;
            transition:0.3s;
        }
        .toggle-slider:before {
            content:""; position:absolute;
            width:20px; height:20px; left:3px; bottom:3px;
            background:#fff; border-radius:50%; transition:0.3s;
        }
        .toggle-switch input:checked + .toggle-slider { background:#6366f1; }
        .toggle-switch input:checked + .toggle-slider:before { transform:translateX(22px); }
        /* Mode toggle button */
        .mode-toggle {
            display: flex; align-items: center; gap: 0.4rem;
            background: var(--bg-hover); border: 1px solid var(--border);
            border-radius: 20px; padding: 0.3rem 0.75rem;
            cursor: pointer; font-size: 0.78rem; font-weight: 600;
            color: var(--text-secondary); transition: all 0.2s;
            white-space: nowrap;
        }
        .mode-toggle:hover { color: var(--text-primary); border-color: var(--text-secondary); }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-primary); color: var(--text-primary); margin: 0; padding: 0; display: flex; min-height: 100vh; }

        /* ── Sidebar ─────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-secondary);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: width 0.25s ease;
        }
        /* ── Collapsed state ─────────────────────────────────── */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            overflow: visible; /* allow flyout to escape */
        }
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .sidebar-role,
        .sidebar.collapsed .sidebar-section-label,
        .sidebar.collapsed .sidebar-link-label,
        .sidebar.collapsed .group-label,
        .sidebar.collapsed .group-chevron,
        .sidebar.collapsed .sidebar-user-name,
        .sidebar.collapsed .sidebar-user-role,
        .sidebar.collapsed .sidebar-collapse-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            pointer-events: none;
        }
        .sidebar.collapsed .sidebar-header { padding: 1rem 0; justify-content: center; }
        .sidebar.collapsed .sidebar-brand { justify-content: center; }
        .sidebar.collapsed .sidebar-nav { overflow: visible; }
        .sidebar.collapsed .sidebar-link {
            padding: 0.625rem 0;
            justify-content: center;
            gap: 0;
            border-left: none;
            border-right: 3px solid transparent;
            position: relative;
        }
        .sidebar.collapsed .sidebar-link.active { border-right-color: var(--text-primary); border-left: none; }
        .sidebar.collapsed .sidebar-group-btn {
            padding: 0.625rem 0;
            justify-content: center;
            gap: 0;
            border-left: none;
            position: relative;
        }
        .sidebar.collapsed .sidebar-group-panel { max-height: 0 !important; overflow: hidden !important; }
        .sidebar.collapsed .sidebar-footer { padding: 0.75rem 0; justify-content: center; }
        .sidebar.collapsed .sidebar-user { justify-content: center; }
        .sidebar.collapsed .sidebar-avatar { margin: 0 auto; }
        .sidebar.collapsed .sidebar-collapse-btn {
            justify-content: center;
            padding: 0.75rem 0;
        }
        /* ── Flyout submenu (collapsed hover) ───────────────── */
        .sidebar-flyout {
            display: none;
            position: fixed;
            left: var(--sidebar-collapsed-width);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 0 8px 8px 0;
            box-shadow: 4px 0 20px rgba(0,0,0,0.4);
            z-index: 200;
            min-width: 200px;
            padding: 0.5rem 0;
            pointer-events: none;
            opacity: 0;
            transform: translateX(-8px);
            transition: opacity 0.18s ease, transform 0.18s ease;
        }
        .sidebar-flyout.visible {
            display: block;
            pointer-events: auto;
            opacity: 1;
            transform: translateX(0);
        }
        .sidebar-flyout-title {
            padding: 0.4rem 1rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            margin-bottom: 0.25rem;
        }
        .sidebar-flyout a {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.825rem;
            font-weight: 500;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .sidebar-flyout a:hover { color: var(--text-primary); background: var(--bg-hover); }
        .sidebar-flyout a.active { color: var(--text-primary); background: rgba(255,255,255,0.05); }
        .sidebar-flyout a i { width: 14px; text-align: center; font-size: 0.8rem; }
        /* ── Collapse toggle button ──────────────────────────── */
        .sidebar-collapse-btn {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            border: none; background: none; width: 100%;
            transition: all 0.15s;
        }
        .sidebar-collapse-btn:hover { color: var(--text-primary); background: var(--bg-hover); }
        .sidebar-collapse-btn i { width: 18px; text-align: center; font-size: 0.9rem; transition: transform 0.25s; }
        .sidebar.collapsed .sidebar-collapse-btn i { transform: rotate(180deg); }
        /* ── Sidebar header ──────────────────────────────────── */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            transition: padding 0.25s;
        }
        .sidebar-brand { display: flex; align-items: center; text-decoration: none; }
        .sidebar-brand img { height: 32px; width: auto; display: block; }
        .sidebar-brand span { color: var(--text-secondary); transition: opacity 0.2s, width 0.2s; }
        .sidebar-role { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; transition: opacity 0.2s; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .sidebar-section-label {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: opacity 0.2s;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover { color: var(--text-primary); background-color: var(--bg-hover); }
        .sidebar-link.active { color: var(--text-primary); background-color: rgba(255,255,255,0.05); border-left-color: var(--text-primary); }
        .sidebar-link i { width: 18px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
        .sidebar-link-label { transition: opacity 0.2s, width 0.2s; }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            transition: padding 0.25s;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .sidebar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bg-card);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: var(--text-secondary);
            overflow: hidden;
            flex-shrink: 0;
        }
        .sidebar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            border-radius: 50%;
        }
        .sidebar-user-name { font-size: 0.875rem; font-weight: 600; }
        .sidebar-user-role { font-size: 0.75rem; color: var(--text-muted); }

        /* ── Main Content ────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s ease;
        }
        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        .topbar {
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title { font-size: 1rem; font-weight: 600; }
        .page-content { padding: 2rem; flex: 1; }

        /* Components */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.25rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-primary { background-color: var(--text-primary); color: var(--bg-primary); }
        .btn-primary:hover { background-color: #e0e0e0; }
        .btn-outline { background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-light); }
        .btn-outline:hover { background-color: var(--bg-card); }
        .btn-danger { background-color: var(--danger); color: white; }
        .btn-success { background-color: var(--success); color: white; }
        .btn-warning { background-color: var(--warning); color: #000; }
        .btn-info { background-color: var(--info); color: white; }
        .btn-sm { padding: 0.3rem 0.75rem; font-size: 0.8rem; }
        .btn-xs { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
        .card { background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; }
        .alert { padding: 0.875rem 1.25rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #86efac; }
        .alert-error { background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; background-color: var(--bg-secondary); border: 1px solid var(--border-light); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; transition: border-color 0.2s; }
        .form-control:focus { outline: none; border-color: #555; }
        .form-control::placeholder { color: var(--text-muted); }
        textarea.form-control { resize: vertical; min-height: 120px; }
        select.form-control { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 12px; appearance: none; }
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-success { background: rgba(34,197,94,0.15); color: #86efac; }
        .badge-danger { background: rgba(239,68,68,0.15); color: #fca5a5; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-info { background: rgba(59,130,246,0.15); color: #93c5fd; }
        .badge-secondary { background: rgba(255,255,255,0.1); color: #a0a0a0; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .table th { text-align: left; padding: 0.75rem 1rem; background-color: var(--bg-secondary); color: var(--text-secondary); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        .table td { padding: 0.875rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-primary); }
        .table tr:hover td { background-color: var(--bg-hover); }
        .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; }
        .stat-number { font-size: 2rem; font-weight: 800; }
        .stat-label { color: var(--text-secondary); font-size: 0.875rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .flex { display: flex; } .items-center { align-items: center; } .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; } .gap-4 { gap: 1rem; }
        .text-sm { font-size: 0.875rem; } .text-xs { font-size: 0.75rem; }
        .text-muted { color: var(--text-muted); } .text-secondary { color: var(--text-secondary); }
        .font-bold { font-weight: 700; } .font-semibold { font-weight: 600; }
        .mt-4 { margin-top: 1rem; } .mt-6 { margin-top: 1.5rem; } .mt-8 { margin-top: 2rem; }
        .mb-4 { margin-bottom: 1rem; } .mb-6 { margin-bottom: 1.5rem; } .mb-8 { margin-bottom: 2rem; }
        /* ── Sidebar Accordion ───────────────────────────────── */
        .sidebar-group-btn {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1.5rem; width: 100%; background: none; border: none;
            color: var(--text-secondary); font-size: 0.875rem; font-weight: 600;
            cursor: pointer; text-align: left; transition: all 0.15s;
            border-left: 3px solid transparent;
            position: relative;
        }
        .sidebar-group-btn:hover { color: var(--text-primary); background-color: var(--bg-hover); }
        .sidebar-group-btn i.group-icon { width: 18px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
        .sidebar-group-btn .group-label { transition: opacity 0.2s, width 0.2s; }
        .sidebar-group-btn .group-chevron { margin-left: auto; font-size: 0.7rem; transition: transform 0.25s; color: var(--text-muted); }
        .sidebar-group-btn.open .group-chevron { transform: rotate(180deg); }
        .sidebar-group-panel { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .sidebar-group-panel.open { max-height: 600px; }
        .sidebar-sub-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.5rem 1.5rem 0.5rem 3rem;
            color: var(--text-muted); text-decoration: none;
            font-size: 0.825rem; font-weight: 500; transition: all 0.15s;
            border-left: 3px solid transparent;
        }
        .sidebar-sub-link:hover { color: var(--text-primary); background-color: var(--bg-hover); }
        .sidebar-sub-link.active { color: var(--text-primary); background-color: rgba(255,255,255,0.05); border-left-color: var(--text-primary); }
        .sidebar-sub-link i { width: 14px; text-align: center; font-size: 0.8rem; }
        /* ── CRM Sub-group buttons ──────────────────────────── */
        .sidebar-sub-group-btn {
            padding: 0.4rem 1.5rem 0.4rem 2.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            border-left: 3px solid transparent;
        }
        .sidebar-sub-sub-link {
            padding-left: 4rem !important;
        }
        .sidebar.collapsed .sidebar-sub-group-btn {
            padding: 0.4rem 0 !important;
            justify-content: center;
            gap: 0;
        }
        /* Mobile Hamburger Button */
        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-primary);
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 99;
        }
        .sidebar-overlay.active { display: block; }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .mobile-menu-btn { display: flex; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            @php
                $tenantUser = auth()->user();
                $tenantSiteUrl = $tenantUser->custom_domain
                    ? 'https://' . $tenantUser->custom_domain
                    : url('/' . $tenantUser->username);
            @endphp
            <a href="{{ $tenantSiteUrl }}" class="sidebar-brand" title="View Your Site" target="_blank">
                @if($tenantUser->avatar)
                    <img src="{{ asset('storage/' . $tenantUser->avatar) }}" alt="{{ $tenantUser->name }}" style="height:36px;width:36px;border-radius:50%;object-fit:cover;">
                @else
                    <span style="font-size:1.2rem;font-weight:700;color:#a78bfa;">{{ strtoupper(substr($tenantUser->name,0,1)) }}</span>
                @endif
                <span style="font-size:0.85rem;color:#a78bfa;font-weight:600;margin-left:0.5rem;">{{ $tenantUser->name }}</span>
            </a>
            <p class="sidebar-role">Admin Panel</p>
        </div>

        <nav class="sidebar-nav">
            @php
                $sidebarUser = auth()->user();
                $isOwner = $sidebarUser?->isAdmin() || $sidebarUser?->isSuperAdmin();
                // For tenant owners: check plan-level module access.
                // For admin_staff sub-users: check role/user module_permissions.
                // SuperAdmins always see everything.
                $canSee = function(string $module) use ($sidebarUser, $isOwner): bool {
                    if ($sidebarUser?->isSuperAdmin()) return true;
                    if ($isOwner) {
                        // Tenant owner — gate by subscription plan
                        return $sidebarUser?->planHasModule($module) ?? false;
                    }
                    // Sub-user (admin_staff) — gate by role/user module_permissions
                    // but also respect the owner's plan
                    $owner = $sidebarUser?->tenantOwner ?? $sidebarUser;
                    if ($owner && !$owner->planHasModule($module)) return false;
                    return $sidebarUser?->hasModuleAccess($module) ?? false;
                };
            @endphp
            {{-- Overview --}}
            <p class="sidebar-section-label">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span class="sidebar-link-label"> Dashboard</span>
            </a>

            {{-- Content Group --}}
            @if($canSee('content'))
            @php $contentActive = request()->routeIs('admin.blog*') || request()->routeIs('admin.forum*'); @endphp
            <button class="sidebar-group-btn {{ $contentActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgContent', this)">
                <i class="fas fa-pen-nib group-icon"></i> <span class="group-label">Content</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $contentActive ? 'open' : '' }}" id="sgContent">
                <a href="{{ route('admin.blog.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.blog.index') ? 'active' : '' }}"><i class="fas fa-list"></i> All Posts</a>
                <a href="{{ route('admin.blog.create') }}" class="sidebar-sub-link {{ request()->routeIs('admin.blog.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> New Post</a>
                <a href="{{ route('admin.blog.comments') }}" class="sidebar-sub-link"><i class="fas fa-comments"></i> Comments</a>
                <a href="{{ route('admin.forum.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.forum*') ? 'active' : '' }}"><i class="fas fa-comments"></i> Forum</a>
            </div>
            @endif

            {{-- Recruitment Group --}}
            @if($canSee('recruitment'))
            @php $jobsActive = request()->routeIs('admin.jobs*'); @endphp
            <button class="sidebar-group-btn {{ $jobsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgJobs', this)">
                <i class="fas fa-briefcase group-icon"></i> <span class="group-label">Recruitment</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $jobsActive ? 'open' : '' }}" id="sgJobs">
                <a href="{{ route('admin.jobs.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.jobs.index') ? 'active' : '' }}"><i class="fas fa-list"></i> Job Listings</a>
                <a href="{{ route('admin.jobs.create') }}" class="sidebar-sub-link"><i class="fas fa-plus-circle"></i> Post a Job</a>
            </div>
            @endif

            {{-- Administration Group (owner only) --}}
            @if($isOwner)
            @php $usersActive = request()->routeIs('admin.users*') || request()->routeIs('admin.roles*'); @endphp
            <button class="sidebar-group-btn {{ $usersActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgUsers', this)">
                <i class="fas fa-users group-icon"></i> <span class="group-label">Administration</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $usersActive ? 'open' : '' }}" id="sgUsers">
                <a href="{{ route('admin.users.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"><i class="fas fa-list"></i> Staff Users</a>
                <a href="{{ route('admin.users.create') }}" class="sidebar-sub-link"><i class="fas fa-user-plus"></i> Add Staff User</a>
                <a href="{{ route('admin.roles.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}"><i class="fas fa-user-tag"></i> Roles &amp; Permissions</a>
            </div>
            @endif

            {{-- CRM Group --}}
            @if($canSee('crm'))
            @php $crmActive = request()->routeIs('admin.crm2*') || request()->routeIs('admin.newcrm*') || request()->routeIs('admin.newsletter*') || request()->routeIs('admin.calendar*'); @endphp
            <button class="sidebar-group-btn {{ $crmActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgNewCRM', this)">
                <i class="fas fa-handshake group-icon"></i> <span class="group-label">CRM</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $crmActive ? 'open' : '' }}" id="sgNewCRM">

                {{-- Analysis & Reports --}}
                <a href="{{ route('admin.crm2.analysis') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm2.analysis') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Analysis</a>
                <a href="{{ route('admin.crm2.reports') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm2.reports') ? 'active' : '' }}"><i class="fas fa-file-chart-line"></i> Reports</a>

                {{-- Sales sub-group --}}
                @php $salesActive = request()->routeIs('admin.crm2.sales*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $salesActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmSales', this)">
                    <i class="fas fa-chart-bar group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Sales</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $salesActive ? 'open' : '' }}" id="sgCrmSales">
                    <a href="{{ route('admin.crm2.sales.leads') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.sales.leads*') ? 'active' : '' }}"><i class="fas fa-user-tag"></i> Leads</a>
                    <a href="{{ route('admin.crm2.sales.contacts') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.sales.contacts*') ? 'active' : '' }}"><i class="fas fa-address-book"></i> Contacts</a>
                    <a href="{{ route('admin.crm2.sales.accounts') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.sales.accounts*') ? 'active' : '' }}"><i class="fas fa-building"></i> Accounts</a>
                    <a href="{{ route('admin.crm2.sales.deals') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.sales.deals*') ? 'active' : '' }}"><i class="fas fa-funnel-dollar"></i> Deals</a>
                    <a href="{{ route('admin.crm2.sales.forecasts') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.sales.forecasts*') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Forecasts</a>
                </div>

                {{-- Activities sub-group --}}
                @php $activitiesActive = request()->routeIs('admin.crm2.activities*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $activitiesActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmActivities', this)">
                    <i class="fas fa-tasks group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Activities</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $activitiesActive ? 'open' : '' }}" id="sgCrmActivities">
                    <a href="{{ route('admin.crm2.activities.tasks') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.activities.tasks*') ? 'active' : '' }}"><i class="fas fa-check-square"></i> Tasks</a>
                    <a href="{{ route('admin.crm2.activities.meetings') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.activities.meetings*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Meetings</a>
                    <a href="{{ route('admin.crm2.activities.calls') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.activities.calls*') ? 'active' : '' }}"><i class="fas fa-phone-alt"></i> Calls</a>
                </div>

                {{-- Inventory sub-group --}}
                @php $inventoryActive = request()->routeIs('admin.crm2.inventory*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $inventoryActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmInventory', this)">
                    <i class="fas fa-boxes group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Inventory</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $inventoryActive ? 'open' : '' }}" id="sgCrmInventory">
                    <a href="{{ route('admin.crm2.inventory.price-books') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.price-books*') ? 'active' : '' }}"><i class="fas fa-tag"></i> Price Books</a>
                    <a href="{{ route('admin.crm2.inventory.quotes') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.quotes*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Quotes</a>
                    <a href="{{ route('admin.crm2.inventory.sales-orders') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.sales-orders*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Sales Orders</a>
                    <a href="{{ route('admin.crm2.inventory.purchase-orders') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.purchase-orders*') ? 'active' : '' }}"><i class="fas fa-truck"></i> Purchase Orders</a>
                    <a href="{{ route('admin.crm2.inventory.invoices') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.invoices*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
                    <a href="{{ route('admin.crm2.inventory.vendors') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.vendors*') ? 'active' : '' }}"><i class="fas fa-store"></i> Vendors</a>
                    <a href="{{ route('admin.crm2.inventory.products') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.inventory.products*') ? 'active' : '' }}"><i class="fas fa-box-open"></i> Products</a>
                </div>

                {{-- Support sub-group --}}
                @php $supportActive = request()->routeIs('admin.crm2.support*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $supportActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmSupport', this)">
                    <i class="fas fa-headset group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Support</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $supportActive ? 'open' : '' }}" id="sgCrmSupport">
                    <a href="{{ route('admin.crm2.support.cases') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.support.cases*') ? 'active' : '' }}"><i class="fas fa-ticket-alt"></i> Cases</a>
                    <a href="{{ route('admin.crm2.support.solutions') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.support.solutions*') ? 'active' : '' }}"><i class="fas fa-lightbulb"></i> Solutions</a>
                </div>

                {{-- Services sub-group --}}
                @php $servicesActive = request()->routeIs('admin.crm2.services*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $servicesActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmServices', this)">
                    <i class="fas fa-concierge-bell group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Services</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $servicesActive ? 'open' : '' }}" id="sgCrmServices">
                    <a href="{{ route('admin.crm2.services.catalog') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.services.catalog*') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> Service Catalog</a>
                    <a href="{{ route('admin.crm2.services.bookings') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.services.bookings*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Bookings</a>
                </div>

                {{-- Projects sub-group --}}
                @php $projectsCrmActive = request()->routeIs('admin.crm2.projects*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $projectsCrmActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmProjects', this)">
                    <i class="fas fa-project-diagram group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Projects</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $projectsCrmActive ? 'open' : '' }}" id="sgCrmProjects">
                    <a href="{{ route('admin.crm2.projects.list') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.projects.list*') ? 'active' : '' }}"><i class="fas fa-folder-open"></i> Projects</a>
                    <a href="{{ route('admin.crm2.projects.tasks') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.projects.tasks*') ? 'active' : '' }}"><i class="fas fa-tasks"></i> Tasks</a>
                </div>

                <div class="sidebar-divider"></div>

                {{-- Integrations Group --}}
                @php $integrationsActive = request()->routeIs('admin.crm2.integrations*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $integrationsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmIntegrations', this)">
                    <i class="fas fa-plug group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Integrations</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $integrationsActive ? 'open' : '' }}" id="sgCrmIntegrations">
                    <a href="{{ route('admin.crm2.integrations.mail-config') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.integrations.mail-config*') ? 'active' : '' }}"><i class="fas fa-envelope-open-text"></i> Mail Config</a>
                </div>

                {{-- Settings Group --}}
                @php $crmSettingsActive = request()->routeIs('admin.crm2.settings*'); @endphp
                <button class="sidebar-group-btn sidebar-sub-group-btn {{ $crmSettingsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCrmSettings', this)">
                    <i class="fas fa-cog group-icon" style="font-size:0.8rem;width:14px;"></i> <span class="group-label">Settings</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $crmSettingsActive ? 'open' : '' }}" id="sgCrmSettings">
                    <a href="{{ route('admin.crm2.settings.mail-templates') }}" class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.crm2.settings.mail-templates*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Mail Templates</a>
                </div>

                <div class="sidebar-divider"></div>
                <a href="{{ route('admin.newsletter.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Newsletter</a>
                <a href="{{ route('admin.calendar.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.calendar*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Calendar &amp; Notes</a>
            </div>
            @endif

            {{-- AI Hub Group --}}
            @if($canSee('ai'))
            @php $aiHubActive = request()->routeIs('admin.crm*'); @endphp
            <button class="sidebar-group-btn {{ $aiHubActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgAIHub', this)">
                <i class="fas fa-robot group-icon"></i> <span class="group-label">AI Hub</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $aiHubActive ? 'open' : '' }}" id="sgAIHub">
                <a href="{{ route('admin.crm.ai.toggle') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm.ai*') ? 'active' : '' }}"><i class="fas fa-robot"></i> AI Assistant</a>
                <a href="{{ route('admin.crm.training') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm.training*') ? 'active' : '' }}"><i class="fas fa-brain"></i> Train AI</a>
                <a href="{{ route('admin.crm.conversations') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm.conversation*') ? 'active' : '' }}"><i class="fas fa-comments"></i> AI Conversations</a>
            </div>
            @endif

            {{-- E-commerce Group --}}
            @if($canSee('ecommerce'))
            @php $ecommerceActive = request()->routeIs('admin.ecommerce*'); @endphp
            <button class="sidebar-group-btn {{ $ecommerceActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgEcommerce', this)">
                <i class="fas fa-store group-icon"></i> <span class="group-label">E-commerce</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $ecommerceActive ? 'open' : '' }}" id="sgEcommerce">
                <a href="{{ route('admin.ecommerce.dashboard') }}" class="sidebar-sub-link {{ request()->routeIs('admin.ecommerce.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Dashboard</a>

                {{-- Products Sub-Group --}}
                @php $ecomProductsActive = request()->routeIs('admin.ecommerce.products*') || request()->routeIs('admin.ecommerce.categories*') || request()->routeIs('admin.ecommerce.reviews*'); @endphp
                <button class="sidebar-sub-group-btn {{ $ecomProductsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgEcomProducts', this)">
                    <i class="fas fa-box-open group-icon" style="font-size:0.8rem;width:14px;"></i>
                    <span class="group-label">Products</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $ecomProductsActive ? 'open' : '' }}" id="sgEcomProducts">
                    <a href="{{ route('admin.ecommerce.products') }}"
                       class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.ecommerce.products') || (request()->routeIs('admin.ecommerce.products*') && !request()->routeIs('admin.ecommerce.products.create')) ? 'active' : '' }}">
                        <i class="fas fa-list"></i> All Products
                    </a>
                    <a href="{{ route('admin.ecommerce.products.create') }}"
                       class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.ecommerce.products.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </a>
                    <a href="{{ route('admin.ecommerce.categories') }}"
                       class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.ecommerce.categories*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i> Categories
                    </a>
                    <a href="{{ route('admin.ecommerce.reviews') }}"
                       class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.ecommerce.reviews*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i> Reviews
                    </a>
                </div>
                {{-- Integrations --}}
                @php $ecomIntegrationsActive = request()->routeIs('admin.ecommerce.integrations.*'); @endphp
                <button class="sidebar-sub-group-btn {{ $ecomIntegrationsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgEcomIntegrations', this)">
                    <i class="fas fa-plug group-icon" style="font-size:0.8rem;width:14px;"></i>
                    <span class="group-label">Integrations</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $ecomIntegrationsActive ? 'open' : '' }}" id="sgEcomIntegrations">
                    <a href="{{ route('admin.ecommerce.integrations.mail-config') }}"
                       class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.ecommerce.integrations.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope-open-text"></i> Mail Config
                    </a>
                </div>

                {{-- Settings --}}
                @php $ecomSettingsActive = request()->routeIs('admin.ecommerce.settings.*'); @endphp
                <button class="sidebar-sub-group-btn {{ $ecomSettingsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgEcomSettings', this)">
                    <i class="fas fa-cog group-icon" style="font-size:0.8rem;width:14px;"></i>
                    <span class="group-label">Settings</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-group-panel {{ $ecomSettingsActive ? 'open' : '' }}" id="sgEcomSettings">
                    <a href="{{ route('admin.ecommerce.settings.mail-templates') }}"
                       class="sidebar-sub-link sidebar-sub-sub-link {{ request()->routeIs('admin.ecommerce.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Mail Templates
                    </a>
                </div>
            </div>
            @endif

            {{-- POS Group --}}
            @if($canSee('pos'))
            @php $posActive = request()->routeIs('admin.pos*'); @endphp
            <button class="sidebar-group-btn {{ $posActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgPos', this)">
                <i class="fas fa-cash-register group-icon"></i> <span class="group-label">Point of Sale</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $posActive ? 'open' : '' }}" id="sgPos">
                <a href="{{ route('admin.pos.terminal') }}" class="sidebar-sub-link {{ request()->routeIs('admin.pos.terminal') ? 'active' : '' }}" target="_blank"><i class="fas fa-desktop"></i> POS Terminal</a>
                <a href="{{ route('admin.pos.orders') }}" class="sidebar-sub-link {{ request()->routeIs('admin.pos.orders') ? 'active' : '' }}"><i class="fas fa-receipt"></i> Orders</a>
                <a href="{{ route('admin.pos.sessions') }}" class="sidebar-sub-link {{ request()->routeIs('admin.pos.sessions*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Sessions</a>
                <a href="{{ route('admin.pos.dashboard') }}" class="sidebar-sub-link {{ request()->routeIs('admin.pos.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> POS Reports</a>
                <a href="{{ route('admin.pos.settings') }}" class="sidebar-sub-link {{ request()->routeIs('admin.pos.settings*') ? 'active' : '' }}"><i class="fas fa-cog"></i> POS Settings</a>
            </div>
            
            @endif

            {{-- Site Builder Group --}}
            @if($canSee('site_builder'))
            @php $siteActive = request()->routeIs('admin.site*') || request()->routeIs('admin.settings*'); @endphp
            <button class="sidebar-group-btn {{ $siteActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgSite', this)">
                <i class="fas fa-paint-brush group-icon"></i> <span class="group-label">Site Builder</span>
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $siteActive ? 'open' : '' }}" id="sgSite">
                <a href="{{ route('admin.site.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.index') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Site Builder Hub</a>
                <a href="{{ route('admin.site.pages') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.pages*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Page Manager</a>
                <a href="{{ route('admin.site.menu') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.menu*') ? 'active' : '' }}"><i class="fas fa-bars"></i> Menu Builder</a>
                <a href="{{ route('admin.site.domain') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.domain*') ? 'active' : '' }}"><i class="fas fa-globe"></i> Domain Config</a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="fas fa-sliders-h"></i> Site Settings</a>
            </div>
            @endif
        </nav>
        {{-- Collapse Toggle --}}
        <div style="border-top: 1px solid var(--border); padding: 0.25rem 0;">
            <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" title="Collapse sidebar">
                <i class="fas fa-chevron-left" id="sidebarCollapseIcon"></i>
                <span class="sidebar-collapse-label">Collapse</span>
            </button>
        </div></aside>
    {{-- Flyout container (shared, positioned dynamically by JS) --}}
    <div id="sidebarFlyout" class="sidebar-flyout"></div>

    <!-- Main Content -->
    <div class="main-content">
        @php
            $tenantUser = auth()->user();
            $viewSiteUrl = $tenantUser->custom_domain
                ? 'https://' . $tenantUser->custom_domain
                : url('/' . $tenantUser->username);
            $topbarChatbotEnabled = \App\Models\SiteSetting::getValueForTenant($tenantUser->id, 'chatbot_enabled', '1');
            $isImpersonating = session()->has('impersonating_customer_id');
        @endphp

        {{-- Exit Impersonation Banner (only when super admin is impersonating) --}}
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
                <button class="mobile-menu-btn" id="adminMenuToggle" onclick="toggleAdminSidebar()">
                    <i class="fas fa-bars" id="adminMenuIcon"></i>
                </button>
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
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
                <div class="topbar-profile" id="profileDropdownWrap" style="position:relative;">
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

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

    <script>
        // ── Dashboard Mode (Light / Dark) ─────────────────────────
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

            // Persist to server (non-blocking)
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
            if (mode === 'light') {
                icon.className = 'fas fa-moon';
                label.textContent = 'Dark';
            } else {
                icon.className = 'fas fa-sun';
                label.textContent = 'Light';
            }
        }

        // Init button state
        document.addEventListener('DOMContentLoaded', function() {
            const mode = localStorage.getItem('xenoraa_dashboard_mode') || 'dark';
            document.documentElement.setAttribute('data-theme', mode);
            updateModeBtn(mode);
        });

        function toggleSidebarGroup(panelId, btn) {
            const panel = document.getElementById(panelId);
            const isOpen = panel.classList.contains('open');
            panel.classList.toggle('open', !isOpen);
            btn.classList.toggle('open', !isOpen);
        }

        // ── Sidebar Collapse ──────────────────────────────────────
        function toggleSidebarCollapse() {
            const sidebar = document.querySelector('.sidebar');
            const isCollapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed', isCollapsed);
            localStorage.setItem('xenoraa_sidebar_collapsed', isCollapsed ? '1' : '0');
        }

        // Init collapse state from localStorage
        (function() {
            const collapsed = localStorage.getItem('xenoraa_sidebar_collapsed') === '1';
            if (collapsed) {
                document.querySelector('.sidebar')?.classList.add('collapsed');
                document.body.classList.add('sidebar-collapsed');
            }
        })();

        // ── Flyout submenu on hover (collapsed mode) ──────────────
        (function() {
            const flyout = document.getElementById('sidebarFlyout');
            let flyoutTimer = null;
            let activeTrigger = null;

            function showFlyout(trigger, title, links) {
                if (!document.querySelector('.sidebar.collapsed')) return;
                clearTimeout(flyoutTimer);
                activeTrigger = trigger;

                const rect = trigger.getBoundingClientRect();
                flyout.innerHTML = '';
                if (title) {
                    const t = document.createElement('div');
                    t.className = 'sidebar-flyout-title';
                    t.textContent = title;
                    flyout.appendChild(t);
                }
                links.forEach(function(l) {
                    const a = document.createElement('a');
                    a.href = l.href;
                    if (l.target) a.target = l.target;
                    a.innerHTML = l.html;
                    if (l.active) a.classList.add('active');
                    flyout.appendChild(a);
                });

                // Position flyout
                const top = Math.min(rect.top, window.innerHeight - flyout.offsetHeight - 8);
                flyout.style.top = Math.max(8, top) + 'px';
                flyout.classList.add('visible');
            }

            function hideFlyout() {
                flyoutTimer = setTimeout(function() {
                    flyout.classList.remove('visible');
                    activeTrigger = null;
                }, 120);
            }

            flyout.addEventListener('mouseenter', function() { clearTimeout(flyoutTimer); });
            flyout.addEventListener('mouseleave', hideFlyout);

            // Attach hover to all group buttons
            document.querySelectorAll('.sidebar-group-btn').forEach(function(btn) {
                const panelId = btn.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
                const panel = panelId ? document.getElementById(panelId) : null;
                const titleEl = btn.querySelector('.group-label');
                const title = titleEl ? titleEl.textContent.trim() : '';

                btn.addEventListener('mouseenter', function() {
                    if (!document.querySelector('.sidebar.collapsed')) return;
                    const links = [];
                    if (panel) {
                        panel.querySelectorAll('.sidebar-sub-link').forEach(function(a) {
                            links.push({
                                href: a.href,
                                target: a.target || '',
                                html: a.innerHTML,
                                active: a.classList.contains('active')
                            });
                        });
                    }
                    showFlyout(btn, title, links);
                });
                btn.addEventListener('mouseleave', hideFlyout);
            });

            // Attach hover to plain sidebar-links (non-group)
            document.querySelectorAll('.sidebar-link').forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!document.querySelector('.sidebar.collapsed')) return;
                    const labelEl = link.querySelector('.sidebar-link-label');
                    const label = labelEl ? labelEl.textContent.trim() : '';
                    const rect = link.getBoundingClientRect();
                    flyout.innerHTML = '';
                    const a = document.createElement('a');
                    a.href = link.href;
                    if (link.target) a.target = link.target;
                    a.textContent = label;
                    if (link.classList.contains('active')) a.classList.add('active');
                    flyout.appendChild(a);
                    const top = Math.min(rect.top, window.innerHeight - 50);
                    flyout.style.top = Math.max(8, top) + 'px';
                    clearTimeout(flyoutTimer);
                    flyout.classList.add('visible');
                });
                link.addEventListener('mouseleave', hideFlyout);
            });
        })();

        function toggleAdminSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const icon = document.getElementById('adminMenuIcon');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            if (sidebar.classList.contains('open')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        }
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

        // Close sidebar when a link is clicked on mobile
        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    const sidebar = document.querySelector('.sidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    const icon = document.getElementById('adminMenuIcon');
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    icon.className = 'fas fa-bars';
                }
            });
        });

        // ── Profile Dropdown ─────────────────────────────────────
        function toggleProfileDropdown() {
            const menu = document.getElementById('profileDropdownMenu');
            if (!menu) return;
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('profileDropdownWrap');
            const menu = document.getElementById('profileDropdownMenu');
            if (wrap && menu && !wrap.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
