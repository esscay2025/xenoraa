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
        :root {
            --bg-primary: #0a0a0a; --bg-secondary: #111111; --bg-card: #1a1a1a; --bg-hover: #222222;
            --text-primary: #ffffff; --text-secondary: #a0a0a0; --text-muted: #666666;
            --border: #2a2a2a; --border-light: #333333;
            --success: #22c55e; --danger: #ef4444; --warning: #f59e0b; --info: #3b82f6;
            --sidebar-width: 260px;
        }
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
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand" title="View Website" target="_blank">
                <img src="{{ asset('images/gopi-logo-transparent.png') }}" alt="Gopi K" style="height:36px;width:auto;">
            </a>
            <p class="sidebar-role">Admin Panel</p>
        </div>

        <nav class="sidebar-nav">
            <p class="sidebar-section-label">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <p class="sidebar-section-label" style="margin-top: 0.75rem;">Content</p>
            <a href="{{ route('admin.blog.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                <i class="fas fa-pen-nib"></i> Blog Posts
            </a>
            <a href="{{ route('admin.blog.create') }}" class="sidebar-link {{ request()->routeIs('admin.blog.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> New Post
            </a>
            <a href="{{ route('admin.blog.comments') }}" class="sidebar-link">
                <i class="fas fa-comments"></i> Comments
            </a>

            <p class="sidebar-section-label" style="margin-top: 0.75rem;">Recruitment</p>
            <a href="{{ route('admin.jobs.index') }}" class="sidebar-link {{ request()->routeIs('admin.jobs*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Job Listings
            </a>
            <a href="{{ route('admin.jobs.create') }}" class="sidebar-link">
                <i class="fas fa-plus-circle"></i> Post a Job
            </a>

            <p class="sidebar-section-label" style="margin-top: 0.75rem;">Finance</p>
            <a href="{{ route('admin.expenses.index') }}" class="sidebar-link {{ request()->routeIs('admin.expenses*') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i> Expenses
            </a>
            <a href="{{ route('admin.expenses.create') }}" class="sidebar-link">
                <i class="fas fa-plus-circle"></i> Add Expense
            </a>

            <p class="sidebar-section-label" style="margin-top: 0.75rem;">Administration</p>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="{{ route('admin.users.create') }}" class="sidebar-link">
                <i class="fas fa-user-plus"></i> Add User
            </a>

            <p class="sidebar-section-label" style="margin-top: 0.75rem;">Site</p>
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Site Settings
            </a>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
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
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('home') }}" class="btn btn-outline btn-sm" target="_blank">
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

    @stack('scripts')
</body>
</html>
