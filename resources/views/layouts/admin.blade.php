<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | Gopi K Portfolio</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Dark mode (default) ─────────────────── */
        :root, [data-theme="dark"] {
            --bg-primary: #0a0a0a; --bg-secondary: #111111; --bg-card: #1a1a1a; --bg-hover: #222222;
            --text-primary: #ffffff; --text-secondary: #a0a0a0; --text-muted: #666666;
            --border: #2a2a2a; --border-light: #333333;
            --success: #22c55e; --danger: #ef4444; --warning: #f59e0b; --info: #3b82f6;
            --sidebar-width: 260px;
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
            --accent: #6366f1;
            --topbar-bg: #ffffff;
        }
        [data-theme="light"] body { background-color: var(--bg-primary); color: var(--text-primary); }
        [data-theme="light"] .sidebar { background-color: #fff; border-right-color: var(--border); }
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

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-secondary);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand { display: flex; align-items: center; text-decoration: none; }
        .sidebar-brand img { height: 32px; width: auto; display: block; }
        .sidebar-brand span { color: var(--text-secondary); }
        .sidebar-role { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .sidebar-section-label {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
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
        .sidebar-link i { width: 18px; text-align: center; font-size: 0.9rem; }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
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

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
        /* Sidebar Accordion */
        .sidebar-group-btn {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1.5rem; width: 100%; background: none; border: none;
            color: var(--text-secondary); font-size: 0.875rem; font-weight: 600;
            cursor: pointer; text-align: left; transition: all 0.15s;
            border-left: 3px solid transparent;
        }
        .sidebar-group-btn:hover { color: var(--text-primary); background-color: var(--bg-hover); }
        .sidebar-group-btn i.group-icon { width: 18px; text-align: center; font-size: 0.9rem; }
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
                @if($tenantUser->profile_photo)
                    <img src="{{ asset('storage/' . $tenantUser->profile_photo) }}" alt="{{ $tenantUser->name }}" style="height:36px;width:36px;border-radius:50%;object-fit:cover;">
                @else
                    <span style="font-size:1.2rem;font-weight:700;color:#a78bfa;">{{ strtoupper(substr($tenantUser->name,0,1)) }}</span>
                @endif
                <span style="font-size:0.85rem;color:#a78bfa;font-weight:600;margin-left:0.5rem;">{{ $tenantUser->name }}</span>
            </a>
            <p class="sidebar-role">Admin Panel</p>
        </div>

        <nav class="sidebar-nav">
            {{-- Overview --}}
            <p class="sidebar-section-label">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            {{-- Content Group --}}
            @php $contentActive = request()->routeIs('admin.blog*'); @endphp
            <button class="sidebar-group-btn {{ $contentActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgContent', this)">
                <i class="fas fa-pen-nib group-icon"></i> Content
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $contentActive ? 'open' : '' }}" id="sgContent">
                <a href="{{ route('admin.blog.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.blog.index') ? 'active' : '' }}"><i class="fas fa-list"></i> All Posts</a>
                <a href="{{ route('admin.blog.create') }}" class="sidebar-sub-link {{ request()->routeIs('admin.blog.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> New Post</a>
                <a href="{{ route('admin.blog.comments') }}" class="sidebar-sub-link"><i class="fas fa-comments"></i> Comments</a>
            </div>

            {{-- Recruitment Group --}}
            @php $jobsActive = request()->routeIs('admin.jobs*'); @endphp
            <button class="sidebar-group-btn {{ $jobsActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgJobs', this)">
                <i class="fas fa-briefcase group-icon"></i> Recruitment
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $jobsActive ? 'open' : '' }}" id="sgJobs">
                <a href="{{ route('admin.jobs.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.jobs.index') ? 'active' : '' }}"><i class="fas fa-list"></i> Job Listings</a>
                <a href="{{ route('admin.jobs.create') }}" class="sidebar-sub-link"><i class="fas fa-plus-circle"></i> Post a Job</a>
            </div>

            {{-- Finance Group --}}
            @php $financeActive = request()->routeIs('admin.expenses*'); @endphp
            <button class="sidebar-group-btn {{ $financeActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgFinance', this)">
                <i class="fas fa-wallet group-icon"></i> Finance
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $financeActive ? 'open' : '' }}" id="sgFinance">
                <a href="{{ route('admin.expenses.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.expenses.index') ? 'active' : '' }}"><i class="fas fa-list"></i> All Expenses</a>
                <a href="{{ route('admin.expenses.create') }}" class="sidebar-sub-link"><i class="fas fa-plus-circle"></i> Add Expense</a>
            </div>

            {{-- Administration Group --}}
            @php $usersActive = request()->routeIs('admin.users*'); @endphp
            <button class="sidebar-group-btn {{ $usersActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgUsers', this)">
                <i class="fas fa-users group-icon"></i> Administration
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $usersActive ? 'open' : '' }}" id="sgUsers">
                <a href="{{ route('admin.users.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"><i class="fas fa-list"></i> All Users</a>
                <a href="{{ route('admin.users.create') }}" class="sidebar-sub-link"><i class="fas fa-user-plus"></i> Add User</a>
            </div>

            {{-- Community Group --}}
            @php $communityActive = request()->routeIs('admin.forum*') || request()->routeIs('admin.chat*') || request()->routeIs('admin.calendar*') || request()->routeIs('admin.newsletter*'); @endphp
            <button class="sidebar-group-btn {{ $communityActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCommunity', this)">
                <i class="fas fa-users-cog group-icon"></i> Community
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $communityActive ? 'open' : '' }}" id="sgCommunity">
                <a href="{{ route('admin.forum.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.forum*') ? 'active' : '' }}"><i class="fas fa-comments"></i> Forum Control</a>
                <a href="{{ route('admin.chat.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.chat*') ? 'active' : '' }}"><i class="fas fa-comment-dots"></i> Chat Monitor</a>
                <a href="{{ route('admin.calendar.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.calendar*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Calendar &amp; Notes</a>
                <a href="{{ route('admin.newsletter.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Newsletter</a>
            </div>

            {{-- CRM Group --}}
            @php $crmActive = request()->routeIs('admin.crm*'); @endphp
            <button class="sidebar-group-btn {{ $crmActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgCRM', this)">
                <i class="fas fa-handshake group-icon"></i> CRM
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $crmActive ? 'open' : '' }}" id="sgCRM">
                <a href="{{ route('admin.crm.leads') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm.leads*') || request()->routeIs('admin.crm.lead*') ? 'active' : '' }}"><i class="fas fa-user-tie"></i> All Leads</a>
                <a href="{{ route('admin.crm.conversations') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm.conversation*') ? 'active' : '' }}"><i class="fas fa-robot"></i> AI Conversations</a>
                <a href="{{ route('admin.crm.training') }}" class="sidebar-sub-link {{ request()->routeIs('admin.crm.training*') ? 'active' : '' }}"><i class="fas fa-brain"></i> Train Chatbot</a>
            </div>

            {{-- E-commerce Group --}}
            @php $ecommerceActive = request()->routeIs('admin.ecommerce*'); @endphp
            <button class="sidebar-group-btn {{ $ecommerceActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgEcommerce', this)">
                <i class="fas fa-store group-icon"></i> E-commerce
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $ecommerceActive ? 'open' : '' }}" id="sgEcommerce">
                <a href="{{ route('admin.ecommerce.dashboard') }}" class="sidebar-sub-link {{ request()->routeIs('admin.ecommerce.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a href="{{ route('admin.ecommerce.categories') }}" class="sidebar-sub-link {{ request()->routeIs('admin.ecommerce.categories*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Categories</a>
                <a href="{{ route('admin.ecommerce.products') }}" class="sidebar-sub-link {{ request()->routeIs('admin.ecommerce.products*') ? 'active' : '' }}"><i class="fas fa-box"></i> Products</a>
                <a href="{{ route('admin.ecommerce.products.create') }}" class="sidebar-sub-link"><i class="fas fa-plus-circle"></i> Add Product</a>
                <a href="{{ route('admin.ecommerce.reviews') }}" class="sidebar-sub-link {{ request()->routeIs('admin.ecommerce.reviews*') ? 'active' : '' }}"><i class="fas fa-star"></i> Reviews</a>
            </div>

            {{-- Site Builder Group --}}
            @php $siteActive = request()->routeIs('admin.site*') || request()->routeIs('admin.settings*'); @endphp
            <button class="sidebar-group-btn {{ $siteActive ? 'open' : '' }}" onclick="toggleSidebarGroup('sgSite', this)">
                <i class="fas fa-paint-brush group-icon"></i> Site Builder
                <i class="fas fa-chevron-down group-chevron"></i>
            </button>
            <div class="sidebar-group-panel {{ $siteActive ? 'open' : '' }}" id="sgSite">
                <a href="{{ route('admin.site.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.index') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Site Builder Hub</a>
                <a href="{{ route('admin.site.themes') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.themes*') ? 'active' : '' }}"><i class="fas fa-palette"></i> Theme Store</a>
                <a href="{{ route('admin.site.pages') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.pages*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Page Manager</a>
                <a href="{{ route('admin.site.menu') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.menu*') ? 'active' : '' }}"><i class="fas fa-bars"></i> Menu Builder</a>
                <a href="{{ route('admin.site.branding') }}" class="sidebar-sub-link {{ request()->routeIs('admin.site.branding*') ? 'active' : '' }}"><i class="fas fa-image"></i> Branding</a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-sub-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="fas fa-sliders-h"></i> Site Settings</a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    @if(auth()->user()->email === 'gopi@outlook.in')
                        <img src="{{ asset('images/gopi-profile.png') }}" alt="{{ auth()->user()->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role?->name ?? 'Admin') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="width: 100%;">
                    <i class="fas fa-sign-out-alt"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <button class="mobile-menu-btn" id="adminMenuToggle" onclick="toggleAdminSidebar()">
                    <i class="fas fa-bars" id="adminMenuIcon"></i>
                </button>
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                @php
                    $tenantUser = auth()->user();
                    $viewSiteUrl = $tenantUser->custom_domain
                        ? 'https://' . $tenantUser->custom_domain
                        : url('/' . $tenantUser->username);
                @endphp
                <button class="mode-toggle" id="modeToggleBtn" onclick="toggleDashboardMode()" title="Toggle light/dark mode">
                    <i id="modeIcon" class="fas fa-sun"></i>
                    <span id="modeLabel">Light</span>
                </button>
                <a href="{{ $viewSiteUrl }}" class="btn btn-outline btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
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
    </script>
    @stack('scripts')
</body>
</html>
