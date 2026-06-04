@extends('layouts.superadmin')
@section('title', 'Super Admin Dashboard')
@section('page_title', 'Platform Overview')

@section('content')

{{-- Stat Cards --}}
<div class="sa-stat-grid">
    <div class="sa-stat-card">
        <div style="display:flex;justify-content:space-between;">
            <div class="sa-stat-label">Total Users</div>
            <div class="sa-stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="sa-stat-value">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="sa-stat-change sa-stat-up"><i class="fas fa-arrow-up"></i> +{{ $stats['new_users_month'] ?? 0 }} this month</div>
    </div>
    <div class="sa-stat-card">
        <div style="display:flex;justify-content:space-between;">
            <div class="sa-stat-label">Active Subscriptions</div>
            <div class="sa-stat-icon"><i class="fas fa-credit-card"></i></div>
        </div>
        <div class="sa-stat-value">{{ $stats['active_subs'] ?? 0 }}</div>
        <div class="sa-stat-change sa-stat-up"><i class="fas fa-arrow-up"></i> {{ $stats['sub_growth'] ?? 0 }}% growth</div>
    </div>
    <div class="sa-stat-card">
        <div style="display:flex;justify-content:space-between;">
            <div class="sa-stat-label">Monthly Revenue</div>
            <div class="sa-stat-icon"><i class="fas fa-rupee-sign"></i></div>
        </div>
        <div class="sa-stat-value">₹{{ number_format($stats['monthly_revenue'] ?? 0) }}</div>
        <div class="sa-stat-change sa-stat-up"><i class="fas fa-arrow-up"></i> MRR growing</div>
    </div>
    <div class="sa-stat-card">
        <div style="display:flex;justify-content:space-between;">
            <div class="sa-stat-label">Custom Domains</div>
            <div class="sa-stat-icon"><i class="fas fa-globe"></i></div>
        </div>
        <div class="sa-stat-value">{{ $stats['custom_domains'] ?? 0 }}</div>
        <div class="sa-stat-change" style="color:#71717a;">Active mappings</div>
    </div>
</div>

{{-- Plan Distribution + Recent Users --}}
<div class="sa-grid-2" style="margin-bottom:1.5rem;">
    {{-- Plan Distribution --}}
    <div class="sa-card">
        <div class="sa-card-header">
            <div class="sa-card-title">Plan Distribution</div>
            <a href="{{ route('superadmin.subscriptions') }}" class="sa-card-action">View All →</a>
        </div>
        <div style="padding:1.5rem;">
            @php
            $plans = [
                ['name'=>'Starter','count'=>$stats['starter_count'] ?? 0,'color'=>'#7c3aed','price'=>'₹499'],
                ['name'=>'Professional','count'=>$stats['pro_count'] ?? 0,'color'=>'#a855f7','price'=>'₹999'],
                ['name'=>'Business Pro','count'=>$stats['business_count'] ?? 0,'color'=>'#c084fc','price'=>'₹1,999'],
            ];
            $total = array_sum(array_column($plans, 'count')) ?: 1;
            @endphp
            @foreach($plans as $plan)
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                    <span style="font-size:0.825rem;color:#a1a1aa;">{{ $plan['name'] }} <span style="color:#52525b;">{{ $plan['price'] }}/mo</span></span>
                    <span style="font-size:0.825rem;font-weight:700;color:#fff;">{{ $plan['count'] }} users</span>
                </div>
                <div style="height:6px;background:#1a1a1a;border-radius:3px;overflow:hidden;">
                    <div style="height:100%;width:{{ round(($plan['count']/$total)*100) }}%;background:{{ $plan['color'] }};border-radius:3px;transition:width 1s;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="sa-card">
        <div class="sa-card-header">
            <div class="sa-card-title">Platform Activity</div>
        </div>
        <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem;">
            @php
            $activities = [
                ['icon'=>'fa-pen-nib','label'=>'Blog Posts Published','value'=>$stats['total_posts'] ?? 0,'color'=>'#a855f7'],
                ['icon'=>'fa-users','label'=>'CRM Leads','value'=>$stats['total_leads'] ?? 0,'color'=>'#22c55e'],
                ['icon'=>'fa-comments','label'=>'Chat Conversations','value'=>$stats['total_chats'] ?? 0,'color'=>'#3b82f6'],
                ['icon'=>'fa-envelope','label'=>'Newsletter Subscribers','value'=>$stats['total_subscribers'] ?? 0,'color'=>'#f59e0b'],
                ['icon'=>'fa-shopping-bag','label'=>'Products Listed','value'=>$stats['total_products'] ?? 0,'color'=>'#ec4899'],
                ['icon'=>'fa-calendar-alt','label'=>'Calendar Events','value'=>$stats['total_events'] ?? 0,'color'=>'#06b6d4'],
            ];
            @endphp
            @foreach($activities as $a)
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:32px;height:32px;background:rgba(255,255,255,0.04);border-radius:8px;display:flex;align-items:center;justify-content:center;color:{{ $a['color'] }};font-size:0.8rem;">
                        <i class="fas {{ $a['icon'] }}"></i>
                    </div>
                    <span style="font-size:0.825rem;color:#71717a;">{{ $a['label'] }}</span>
                </div>
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#fff;font-size:0.95rem;">{{ $a['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Users --}}
<div class="sa-card" style="margin-bottom:1.5rem;">
    <div class="sa-card-header">
        <div class="sa-card-title">Recent Signups</div>
        <a href="{{ route('superadmin.users') }}" class="sa-card-action">View All Users →</a>
    </div>
    <table class="sa-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Username</th>
                <th>Plan</th>
                <th>Domain</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers ?? [] as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:32px;height:32px;background:rgba(124,58,237,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;color:#a855f7;flex-shrink:0;">{{ substr($user->name,0,1) }}</div>
                        <div>
                            <div style="font-weight:600;color:#fff;font-size:0.825rem;">{{ $user->name }}</div>
                            <div style="font-size:0.75rem;color:#52525b;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:#7c3aed;">{{ $user->username ?? 'N/A' }}</td>
                <td>
                    @php $plan = $user->plan ?? 'starter'; @endphp
                    <span class="sa-badge sa-badge-{{ $plan === 'professional' ? 'pro' : ($plan === 'business' ? 'business' : 'starter') }}">{{ ucfirst($plan) }}</span>
                </td>
                <td style="font-size:0.75rem;color:#52525b;">{{ $user->custom_domain ?? '—' }}</td>
                <td>
                    <span class="sa-badge {{ $user->status === 'active' ? 'sa-badge-active' : 'sa-badge-inactive' }}">
                        <span style="width:5px;height:5px;border-radius:50%;background:currentColor;"></span>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </td>
                <td style="color:#52525b;font-size:0.75rem;">{{ $user->created_at->diffForHumans() }}</td>
                <td>
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('superadmin.users.show', $user->id) }}" class="sa-action-btn"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('superadmin.users.impersonate', $user->id) }}" class="sa-action-btn" title="Login as user"><i class="fas fa-sign-in-alt"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#3f3f46;padding:2rem;">No users yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Custom Domains --}}
<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">Custom Domain Mappings</div>
        <a href="{{ route('superadmin.domains') }}" class="sa-card-action">Manage Domains →</a>
    </div>
    <table class="sa-table">
        <thead>
            <tr>
                <th>Custom Domain</th>
                <th>Xenoraa Profile</th>
                <th>User</th>
                <th>SSL Status</th>
                <th>DNS Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($domains ?? [] as $domain)
            <tr>
                <td style="color:#a855f7;font-weight:600;">{{ $domain->domain }}</td>
                <td style="color:#71717a;">xenoraa.com/{{ $domain->user->username ?? '—' }}</td>
                <td style="color:#fff;">{{ $domain->user->name ?? '—' }}</td>
                <td><span class="sa-badge sa-badge-active"><i class="fas fa-lock" style="font-size:0.6rem;"></i> Active</span></td>
                <td><span class="sa-badge sa-badge-active"><i class="fas fa-check" style="font-size:0.6rem;"></i> Verified</span></td>
                <td>
                    <button class="sa-action-btn danger" onclick="revokeDomain({{ $domain->id }})"><i class="fas fa-times"></i> Revoke</button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#3f3f46;padding:2rem;">No custom domains mapped yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
