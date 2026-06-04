@extends('layouts.superadmin')
@section('title', 'Showcase')
@section('page_title', 'Showcase')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Showcase</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">Active Xenoraa user profiles featured on the showcase page</p>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
    @forelse($users as $user)
    <div class="sa-card" style="padding:1.5rem;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
            <div style="width:48px;height:48px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
                <div style="font-weight:700;color:#fff;">{{ $user->name }}</div>
                <div style="font-size:0.75rem;color:#52525b;">xenoraa.com/{{ $user->username }}</div>
            </div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:1rem;">
            <div style="text-align:center;">
                <div style="font-family:'Space Grotesk',sans-serif;font-size:1.25rem;font-weight:700;color:#a855f7;">{{ $user->blog_posts_count }}</div>
                <div style="font-size:0.7rem;color:#52525b;">Posts</div>
            </div>
            <div style="text-align:center;">
                @php $plan = $user->plan ?? 'starter'; @endphp
                <span class="sa-badge sa-badge-{{ $plan === 'professional' ? 'pro' : ($plan === 'business' ? 'business' : 'starter') }}">{{ ucfirst($plan) }}</span>
                <div style="font-size:0.7rem;color:#52525b;margin-top:0.3rem;">Plan</div>
            </div>
            <div style="text-align:center;">
                @if($user->custom_domain)
                <div style="font-size:0.75rem;color:#22c55e;"><i class="fas fa-globe"></i></div>
                <div style="font-size:0.7rem;color:#52525b;">Custom Domain</div>
                @else
                <div style="font-size:0.75rem;color:#3f3f46;"><i class="fas fa-globe"></i></div>
                <div style="font-size:0.7rem;color:#3f3f46;">No Domain</div>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:0.5rem;">
            <a href="{{ url('/'.$user->username) }}" target="_blank" class="sa-action-btn" style="flex:1;justify-content:center;"><i class="fas fa-external-link-alt"></i> View Profile</a>
            <a href="{{ route('superadmin.users.show', $user->id) }}" class="sa-action-btn"><i class="fas fa-eye"></i></a>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#3f3f46;">
        <i class="fas fa-star" style="font-size:2rem;margin-bottom:1rem;display:block;opacity:0.2;"></i>
        No active users yet
    </div>
    @endforelse
</div>
@if($users->hasPages())
<div style="margin-top:1rem;">{{ $users->links() }}</div>
@endif
@endsection
