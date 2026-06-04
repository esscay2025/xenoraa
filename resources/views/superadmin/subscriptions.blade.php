@extends('layouts.superadmin')
@section('title', 'Subscriptions')
@section('page_title', 'Subscriptions')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Subscriptions</h1>
        <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">All user subscription plans on Xenoraa</p>
    </div>
</div>

<div class="sa-stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem;">
    @php
    $plans = [
        ['name'=>'Starter','key'=>'starter','price'=>499,'color'=>'#7c3aed','icon'=>'fa-user'],
        ['name'=>'Professional','key'=>'professional','price'=>999,'color'=>'#a855f7','icon'=>'fa-user-tie'],
        ['name'=>'Business Pro','key'=>'business','price'=>1999,'color'=>'#fbbf24','icon'=>'fa-building'],
    ];
    @endphp
    @foreach($plans as $plan)
    @php $count = $subscriptions->where('plan', $plan['key'])->count(); @endphp
    <div class="sa-stat-card">
        <div style="display:flex;justify-content:space-between;align-items:start;">
            <div>
                <div class="sa-stat-label">{{ $plan['name'] }}</div>
                <div class="sa-stat-value" style="color:{{ $plan['color'] }};">{{ $subscriptions->total() > 0 ? $subscriptions->where('plan',$plan['key'])->count() : 0 }}</div>
                <div style="font-size:0.75rem;color:#52525b;">₹{{ number_format($plan['price']) }}/mo per user</div>
            </div>
            <div class="sa-stat-icon" style="background:rgba(124,58,237,0.08);color:{{ $plan['color'] }};"><i class="fas {{ $plan['icon'] }}"></i></div>
        </div>
    </div>
    @endforeach
</div>

<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">All Subscriptions</div>
        <span style="font-size:0.75rem;color:#52525b;">{{ $subscriptions->total() }} total</span>
    </div>
    <table class="sa-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Plan</th>
                <th>Status</th>
                <th>Custom Domain</th>
                <th>Trial Ends</th>
                <th>Member Since</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscriptions as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:32px;height:32px;background:rgba(124,58,237,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;color:#a855f7;">{{ substr($user->name,0,1) }}</div>
                        <div>
                            <div style="font-weight:600;color:#fff;font-size:0.825rem;">{{ $user->name }}</div>
                            <div style="font-size:0.72rem;color:#52525b;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @php $plan = $user->plan ?? 'starter'; @endphp
                    <span class="sa-badge sa-badge-{{ $plan === 'professional' ? 'pro' : ($plan === 'business' ? 'business' : 'starter') }}">{{ ucfirst($plan) }}</span>
                </td>
                <td>
                    <span class="sa-badge {{ ($user->status ?? 'active') === 'active' ? 'sa-badge-active' : 'sa-badge-suspended' }}">
                        <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </td>
                <td style="font-size:0.8rem;color:#71717a;">{{ $user->custom_domain ?? '—' }}</td>
                <td style="font-size:0.8rem;color:#71717a;">
                    {{ isset($user->trial_ends_at) ? \Carbon\Carbon::parse($user->trial_ends_at)->format('d M Y') : 'N/A' }}
                </td>
                <td style="font-size:0.75rem;color:#52525b;">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('superadmin.users.show', $user->id) }}" class="sa-action-btn"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#3f3f46;padding:2rem;">No subscriptions found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($subscriptions->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #1a1a1a;">{{ $subscriptions->links() }}</div>
    @endif
</div>
@endsection
