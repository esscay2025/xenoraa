<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agent Portal') — Xenoraa</title>
    <link rel="icon" type="image/png" href="/images/xenoraa/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --ag-dark: #080808; --ag-sidebar: #0a0a0a; --ag-card: #111;
            --ag-border: #1a1a1a; --ag-border2: #222;
            --ag-green: #22c55e; --ag-green-glow: rgba(34,197,94,0.12);
            --ag-white: #fff; --ag-gray: #a1a1aa; --ag-gray2: #71717a;
            --ag-red: #ef4444; --ag-yellow: #f59e0b; --ag-blue: #3b82f6;
            --ag-purple: #7c3aed;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--ag-dark); color: var(--ag-white); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .ag-sidebar { width: 240px; flex-shrink: 0; background: var(--ag-sidebar); border-right: 1px solid var(--ag-border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto; z-index: 100; }
        .ag-sidebar-logo { padding: 1.25rem 1.5rem 1rem; border-bottom: 1px solid var(--ag-border); display: flex; align-items: center; gap: 0.75rem; }
        .ag-logo-text { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; }
        .ag-logo-badge { font-size: 0.55rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #22c55e; padding: 0.15rem 0.5rem; border-radius: 4px; }

        /* NAV */
        .ag-nav-section { padding: 1.25rem 0 0.5rem; }
        .ag-nav-label { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #3f3f46; padding: 0 1.5rem; margin-bottom: 0.4rem; }
        .ag-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.5rem; color: var(--ag-gray2); text-decoration: none; font-size: 0.825rem; font-weight: 500; transition: all 0.2s; border-left: 2px solid transparent; }
        .ag-nav-item:hover { color: var(--ag-white); background: rgba(255,255,255,0.03); }
        .ag-nav-item.active { color: var(--ag-white); border-left-color: var(--ag-green); background: rgba(34,197,94,0.08); }
        .ag-nav-item i { width: 16px; text-align: center; font-size: 0.8rem; }
        .ag-nav-badge { margin-left: auto; background: var(--ag-green); color: #000; font-size: 0.6rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 100px; }

        /* SIDEBAR BOTTOM */
        .ag-sidebar-profile { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid var(--ag-border); display: flex; align-items: center; gap: 0.75rem; }
        .ag-sidebar-avatar { width: 34px; height: 34px; flex-shrink: 0; background: var(--ag-green-glow); border: 1px solid rgba(34,197,94,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: var(--ag-green); }
        .ag-sidebar-profile-info { flex: 1; min-width: 0; }
        .ag-sidebar-profile-name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ag-sidebar-profile-role { font-size: 0.65rem; color: var(--ag-gray2); }
        .ag-sidebar-profile-actions { display: flex; gap: 0.4rem; }
        .ag-sidebar-profile-btn { width: 28px; height: 28px; border: 1px solid var(--ag-border2); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--ag-gray2); cursor: pointer; transition: all 0.2s; background: transparent; text-decoration: none; font-size: 0.75rem; }
        .ag-sidebar-profile-btn:hover { border-color: var(--ag-green); color: var(--ag-green); }
        .ag-sidebar-profile-btn.logout:hover { border-color: var(--ag-red); color: var(--ag-red); }

        /* MAIN */
        .ag-main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* TOPBAR */
        .ag-topbar { height: 60px; background: var(--ag-sidebar); border-bottom: 1px solid var(--ag-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 50; }
        .ag-topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; }
        .ag-topbar-actions { display: flex; align-items: center; gap: 0.75rem; }
        .ag-topbar-btn { width: 36px; height: 36px; border: 1px solid var(--ag-border2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--ag-gray2); cursor: pointer; transition: all 0.2s; background: transparent; }
        .ag-topbar-btn:hover { border-color: var(--ag-green); color: var(--ag-green); }

        /* CONTENT */
        .ag-content { padding: 2rem; flex: 1; }

        /* CARDS */
        .ag-card { background: var(--ag-card); border: 1px solid var(--ag-border); border-radius: 12px; overflow: hidden; }
        .ag-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ag-border); display: flex; align-items: center; justify-content: space-between; }
        .ag-card-title { font-size: 0.875rem; font-weight: 700; color: var(--ag-white); }
        .ag-table { width: 100%; border-collapse: collapse; }
        .ag-table th { padding: 0.75rem 1.5rem; text-align: left; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ag-gray2); border-bottom: 1px solid var(--ag-border); }
        .ag-table td { padding: 1rem 1.5rem; font-size: 0.825rem; color: var(--ag-gray); border-bottom: 1px solid rgba(255,255,255,0.03); }
        .ag-table tr:last-child td { border-bottom: none; }
        .ag-table tr:hover td { background: rgba(255,255,255,0.02); }
        .ag-btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; background: var(--ag-green); color: #000; border: none; border-radius: 8px; font-size: 0.825rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .ag-btn-primary:hover { background: #16a34a; color: #fff; }
        .ag-btn-outline { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.45rem 1rem; background: transparent; color: var(--ag-gray2); border: 1px solid var(--ag-border2); border-radius: 8px; font-size: 0.8rem; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .ag-btn-outline:hover { border-color: var(--ag-green); color: var(--ag-green); }

        /* STAT GRID */
        .ag-stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
        .ag-stat-card { background: var(--ag-card); border: 1px solid var(--ag-border); border-radius: 12px; padding: 1.5rem; transition: all 0.3s; }
        .ag-stat-card:hover { border-color: var(--ag-green); }

        /* BADGE */
        .ag-badge { display: inline-flex; align-items: center; font-size: 0.65rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 100px; }
        .ag-badge-active { background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
        .ag-badge-pending { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
        .ag-badge-paid { background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
        .ag-badge-cancelled { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
        .ag-badge-starter { background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
        .ag-badge-professional { background: rgba(124,58,237,0.1); color: #a855f7; border: 1px solid rgba(124,58,237,0.2); }
        .ag-badge-business { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }

        @media(max-width:768px){ .ag-sidebar{display:none;} .ag-main{margin-left:0;} .ag-stat-grid{grid-template-columns:1fr;} }
    </style>
    @yield('styles')
</head>
<body>

{{-- AGENT SIDEBAR --}}
<aside class="ag-sidebar">
    <div class="ag-sidebar-logo">
        <img src="/images/xenoraa/logo.png" alt="Xenoraa" style="height:22px;filter:brightness(0) invert(1);">
        <div>
            <div class="ag-logo-text">xenoraa</div>
            <div class="ag-logo-badge">Agent Portal</div>
        </div>
    </div>

    {{-- Main Navigation --}}
    <div class="ag-nav-section">
        <div class="ag-nav-label">Overview</div>
        <a href="{{ route('agent.dashboard') }}" class="ag-nav-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
    </div>

    <div class="ag-nav-section">
        <div class="ag-nav-label">Subscriptions</div>
        <a href="{{ route('agent.create-customer') }}" class="ag-nav-item {{ request()->routeIs('agent.create-customer') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i> Create Customer
        </a>
        <a href="{{ route('agent.my-customers') }}" class="ag-nav-item {{ request()->routeIs('agent.my-customers') ? 'active' : '' }}">
            <i class="fas fa-users"></i> My Customers
        </a>
        <a href="{{ route('agent.quota') }}" class="ag-nav-item {{ request()->routeIs('agent.quota') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i> My Quota
        </a>
    </div>

    <div class="ag-nav-section">
        <div class="ag-nav-label">Earnings</div>
        <a href="{{ route('agent.commissions') }}" class="ag-nav-item {{ request()->routeIs('agent.commissions') ? 'active' : '' }}">
            <i class="fas fa-coins"></i> Commission
        </a>
        <a href="{{ route('agent.payouts') }}" class="ag-nav-item {{ request()->routeIs('agent.payouts') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> Payout History
        </a>
    </div>

    <div class="ag-nav-section">
        <div class="ag-nav-label">Account</div>
        <a href="{{ route('agent.profile') }}" class="ag-nav-item {{ request()->routeIs('agent.profile') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
    </div>

    {{-- Sidebar Profile --}}
    <div class="ag-sidebar-profile">
        @php $agentUser = auth()->user(); @endphp
        <div class="ag-sidebar-avatar">{{ strtoupper(substr($agentUser->name ?? 'A', 0, 1)) }}</div>
        <div class="ag-sidebar-profile-info">
            <div class="ag-sidebar-profile-name">{{ $agentUser->name ?? 'Agent' }}</div>
            <div class="ag-sidebar-profile-role">
                {{ $agentUser->agentProfile?->agent_code ?? 'Agent' }}
                @if($agentUser->agentProfile?->company_name)
                    · {{ $agentUser->agentProfile->company_name }}
                @endif
            </div>
        </div>
        <div class="ag-sidebar-profile-actions">
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="ag-sidebar-profile-btn logout" title="Sign Out">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN --}}
<div class="ag-main">
    {{-- TOPBAR --}}
    <div class="ag-topbar">
        <div class="ag-topbar-title">@yield('title', 'Agent Portal')</div>
        <div class="ag-topbar-actions">
            @if(auth()->user()->agentProfile)
            <div style="display:flex;align-items:center;gap:0.5rem;background:#111;border:1px solid #27272a;border-radius:8px;padding:0.4rem 0.875rem;">
                <i class="fas fa-ticket-alt" style="color:#22c55e;font-size:0.75rem;"></i>
                <span style="font-size:0.775rem;color:#a1a1aa;">Quota:</span>
                <span style="font-size:0.8rem;font-weight:700;color:#22c55e;">{{ auth()->user()->agentProfile->available_quota }} left</span>
            </div>
            @endif
            <a href="{{ route('agent.create-customer') }}" class="ag-btn-primary" style="padding:0.45rem 1rem;font-size:0.8rem;">
                <i class="fas fa-plus"></i> New Customer
            </a>
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
    <div class="ag-content">
        @yield('content')
    </div>
</div>

<script>
// Auto-hide flash messages
setTimeout(() => {
    document.querySelectorAll('[data-flash]').forEach(el => el.style.display = 'none');
}, 5000);
</script>
@yield('scripts')
</body>
</html>
