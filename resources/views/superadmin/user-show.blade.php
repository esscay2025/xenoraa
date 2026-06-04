@extends('layouts.superadmin')
@section('title', 'User — '.$user->name)
@section('page_title', 'User Profile')

@section('content')
<div style="margin-bottom:1.5rem;">
    <a href="{{ route('superadmin.users') }}" style="color:#71717a;font-size:0.875rem;text-decoration:none;"><i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i>Back to Users</a>
</div>

<div class="sa-grid-2" style="margin-bottom:1.5rem;">
    {{-- User Info Card --}}
    <div class="sa-card" style="padding:1.5rem;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
            <div style="width:60px;height:60px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.5rem;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
                <div style="font-family:'Space Grotesk',sans-serif;font-size:1.25rem;font-weight:700;color:#fff;">{{ $user->name }}</div>
                <div style="font-size:0.875rem;color:#52525b;">{{ $user->email }}</div>
                @if($user->username)
                <a href="{{ url('/'.$user->username) }}" target="_blank" style="font-size:0.8rem;color:#7c3aed;">xenoraa.com/{{ $user->username }}</a>
                @endif
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            @foreach([
                ['label'=>'Plan','value'=>ucfirst($user->plan ?? 'starter')],
                ['label'=>'Status','value'=>ucfirst($user->status ?? 'active')],
                ['label'=>'Custom Domain','value'=>$user->custom_domain ?? 'None'],
                ['label'=>'Blog Posts','value'=>$user->blog_posts_count],
                ['label'=>'CRM Leads','value'=>$user->crm_leads_count],
                ['label'=>'Joined','value'=>$user->created_at->format('d M Y')],
            ] as $field)
            <div style="background:rgba(255,255,255,0.02);border:1px solid #1a1a1a;border-radius:8px;padding:0.75rem;">
                <div style="font-size:0.7rem;color:#52525b;margin-bottom:0.2rem;">{{ $field['label'] }}</div>
                <div style="font-size:0.875rem;font-weight:600;color:#fff;">{{ $field['value'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Actions Card --}}
    <div class="sa-card" style="padding:1.5rem;">
        <div style="font-size:0.875rem;font-weight:700;color:#fff;margin-bottom:1rem;">Admin Actions</div>
        <div style="display:grid;gap:0.75rem;">
            <a href="{{ route('superadmin.users.impersonate', $user->id) }}"
                style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);border-radius:8px;color:#a855f7;text-decoration:none;font-size:0.875rem;font-weight:600;transition:all 0.2s;">
                <i class="fas fa-sign-in-alt"></i> Login as this User
            </a>
            <form method="POST" action="{{ route('superadmin.users.toggle-status', $user->id) }}">
                @csrf @method('PATCH')
                <button type="submit" style="width:100%;display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;color:#f87171;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
                    <i class="fas fa-{{ ($user->status ?? 'active') === 'active' ? 'ban' : 'check' }}"></i>
                    {{ ($user->status ?? 'active') === 'active' ? 'Suspend User' : 'Activate User' }}
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Recent Blog Posts --}}
<div class="sa-card" style="margin-bottom:1.5rem;">
    <div class="sa-card-header">
        <div class="sa-card-title">Recent Blog Posts</div>
    </div>
    <table class="sa-table">
        <thead><tr><th>Title</th><th>Status</th><th>Published</th></tr></thead>
        <tbody>
            @forelse($recentPosts as $post)
            <tr>
                <td style="color:#fff;font-size:0.825rem;">{{ $post->title }}</td>
                <td><span class="sa-badge sa-badge-active">{{ ucfirst($post->status ?? 'published') }}</span></td>
                <td style="color:#52525b;font-size:0.75rem;">{{ \Carbon\Carbon::parse($post->created_at)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;color:#3f3f46;padding:1.5rem;">No posts yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Recent Leads --}}
<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">Recent CRM Leads</div>
    </div>
    <table class="sa-table">
        <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Created</th></tr></thead>
        <tbody>
            @forelse($recentLeads as $lead)
            <tr>
                <td style="color:#fff;font-size:0.825rem;">{{ $lead->name }}</td>
                <td style="color:#71717a;font-size:0.8rem;">{{ $lead->email }}</td>
                <td><span class="sa-badge sa-badge-starter">{{ ucfirst($lead->status ?? 'new') }}</span></td>
                <td style="color:#52525b;font-size:0.75rem;">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#3f3f46;padding:1.5rem;">No leads yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
