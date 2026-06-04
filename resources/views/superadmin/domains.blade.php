@extends('layouts.superadmin')
@section('title', 'Custom Domains')
@section('page_title', 'Custom Domains')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Custom Domains</h1>
        <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">Users who have mapped their own domain to Xenoraa</p>
    </div>
</div>

<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">Domain Mappings</div>
        <span style="font-size:0.75rem;color:#52525b;">{{ $domains->total() }} domains</span>
    </div>
    <table class="sa-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Xenoraa Profile</th>
                <th>Custom Domain</th>
                <th>Plan</th>
                <th>SSL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($domains as $user)
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
                    <a href="{{ url('/'.$user->username) }}" target="_blank" style="color:#7c3aed;font-size:0.825rem;">xenoraa.com/{{ $user->username }}</a>
                </td>
                <td>
                    <a href="https://{{ $user->custom_domain }}" target="_blank" style="color:#a855f7;font-weight:600;font-size:0.825rem;">{{ $user->custom_domain }}</a>
                </td>
                <td>
                    @php $plan = $user->plan ?? 'starter'; @endphp
                    <span class="sa-badge sa-badge-{{ $plan === 'professional' ? 'pro' : ($plan === 'business' ? 'business' : 'starter') }}">{{ ucfirst($plan) }}</span>
                </td>
                <td><span class="sa-badge sa-badge-active"><i class="fas fa-lock" style="font-size:0.6rem;"></i> SSL Active</span></td>
                <td>
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('superadmin.users.show', $user->id) }}" class="sa-action-btn"><i class="fas fa-eye"></i> View</a>
                        <form method="POST" action="{{ route('superadmin.domains.update', $user->id) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="custom_domain" value="">
                            <button type="submit" class="sa-action-btn danger" onclick="return confirm('Remove this domain mapping?')">
                                <i class="fas fa-times"></i> Revoke
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#3f3f46;padding:3rem;">
                <i class="fas fa-globe" style="font-size:2rem;margin-bottom:1rem;display:block;opacity:0.2;"></i>
                No custom domains mapped yet
            </td></tr>
            @endforelse
        </tbody>
    </table>
    @if($domains->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #1a1a1a;">{{ $domains->links() }}</div>
    @endif
</div>
@endsection
