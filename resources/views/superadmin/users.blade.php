@extends('layouts.superadmin')
@section('title', 'Users — Super Admin')
@section('page_title', 'User Management')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        @foreach(['all'=>'All Users','starter'=>'Starter','professional'=>'Professional','business'=>'Business Pro','suspended'=>'Suspended'] as $key => $label)
        <a href="{{ route('superadmin.users') }}?plan={{ $key }}" style="padding:0.4rem 1rem;border:1px solid {{ request('plan',$key==='all'?'all':'') === $key ? '#7c3aed' : '#1f1f1f' }};border-radius:100px;font-size:0.775rem;color:{{ request('plan',$key==='all'?'all':'') === $key ? '#a855f7' : '#71717a' }};text-decoration:none;background:{{ request('plan',$key==='all'?'all':'') === $key ? 'rgba(124,58,237,0.08)' : 'transparent' }};transition:all 0.2s;">{{ $label }}</a>
        @endforeach
    </div>
    <div class="sa-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search users..." id="userSearch" oninput="searchUsers()">
    </div>
</div>

<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">{{ $users->total() ?? 0 }} Users</div>
        <div style="display:flex;gap:0.75rem;">
            <button class="sa-action-btn" onclick="exportUsers()"><i class="fas fa-download"></i> Export CSV</button>
        </div>
    </div>
    <table class="sa-table" id="usersTable">
        <thead>
            <tr>
                <th>User</th>
                <th>Profile URL</th>
                <th>Plan</th>
                <th>Custom Domain</th>
                <th>Status</th>
                <th>Blog Posts</th>
                <th>Leads</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users ?? [] as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:36px;height:36px;background:rgba(124,58,237,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.875rem;color:#a855f7;flex-shrink:0;">{{ substr($user->name,0,1) }}</div>
                        <div>
                            <div style="font-weight:600;color:#fff;font-size:0.825rem;">{{ $user->name }}</div>
                            <div style="font-size:0.7rem;color:#52525b;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($user->username)
                    <a href="/{{ $user->username }}" target="_blank" style="color:#7c3aed;font-size:0.775rem;text-decoration:none;">xenoraa.com/{{ $user->username }}</a>
                    @else
                    <span style="color:#3f3f46;font-size:0.775rem;">Not set</span>
                    @endif
                </td>
                <td>
                    @php $plan = $user->plan ?? 'starter'; @endphp
                    <span class="sa-badge sa-badge-{{ $plan === 'professional' ? 'pro' : ($plan === 'business' ? 'business' : 'starter') }}">{{ ucfirst($plan) }}</span>
                </td>
                <td style="font-size:0.775rem;color:#a855f7;">{{ $user->custom_domain ?? '—' }}</td>
                <td>
                    <span class="sa-badge {{ ($user->status ?? 'active') === 'active' ? 'sa-badge-active' : (($user->status ?? '') === 'suspended' ? 'sa-badge-suspended' : 'sa-badge-inactive') }}">
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </td>
                <td style="color:#71717a;">{{ $user->blog_posts_count ?? 0 }}</td>
                <td style="color:#71717a;">{{ $user->crm_leads_count ?? 0 }}</td>
                <td style="color:#52525b;font-size:0.75rem;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                        <a href="{{ route('superadmin.users.show', $user->id) }}" class="sa-action-btn"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('superadmin.users.impersonate', $user->id) }}" class="sa-action-btn" title="Login as this user"><i class="fas fa-sign-in-alt"></i></a>
                        <form method="POST" action="{{ route('superadmin.users.toggle-status', $user->id) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="sa-action-btn {{ ($user->status ?? 'active') === 'active' ? 'danger' : '' }}" title="{{ ($user->status ?? 'active') === 'active' ? 'Suspend' : 'Activate' }}">
                                <i class="fas {{ ($user->status ?? 'active') === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:#3f3f46;padding:3rem;">No users found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($users) && $users->hasPages())
    <div style="padding:1.25rem 1.5rem;border-top:1px solid #1a1a1a;display:flex;justify-content:flex-end;">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function searchUsers() {
    const q = document.getElementById('userSearch').value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
function exportUsers() {
    window.location = '{{ route('superadmin.users') }}?export=csv';
}
</script>
@endsection
