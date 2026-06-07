@extends('layouts.superadmin')
@section('title', 'Staff Members')
@section('content')
<div class="sa-content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">Staff Members</h1>
            <p style="font-size:0.8rem;color:#71717a;margin:0.25rem 0 0;">Super admin staff who handle platform operations</p>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('superadmin.staff.roles') }}" style="background:#27272a;color:#a1a1aa;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;"><i class="fas fa-shield-alt"></i> Roles & Permissions</a>
            @if(auth()->user()->hasSaPermission('staff.create'))
            <a href="{{ route('superadmin.staff.create') }}" class="sa-btn-primary"><i class="fas fa-plus"></i> Add Staff</a>
            @endif
        </div>
    </div>

    <div class="sa-card">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Staff Member</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $s)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#3b82f6;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.875rem;flex-shrink:0;">{{ strtoupper(substr($s->name,0,1)) }}</div>
                            <span style="font-weight:600;color:#fff;font-size:0.85rem;">{{ $s->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:0.82rem;color:#a1a1aa;">{{ $s->email }}</td>
                    <td style="font-size:0.82rem;color:#a1a1aa;">{{ $s->phone ?? '—' }}</td>
                    <td>
                        @php $sc = $s->status ?? 'active'; $scC = ['active'=>'#22c55e','inactive'=>'#f59e0b']; @endphp
                        <span style="background:{{ $scC[$sc]??'#22c55e' }}22;color:{{ $scC[$sc]??'#22c55e' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $sc }}</span>
                    </td>
                    <td style="font-size:0.8rem;color:#71717a;">{{ $s->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            @if(auth()->user()->hasSaPermission('staff.edit'))
                            <a href="{{ route('superadmin.staff.edit', $s->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;"><i class="fas fa-edit"></i></a>
                            @endif
                            @if(auth()->user()->hasSaPermission('staff.delete'))
                            <form method="POST" action="{{ route('superadmin.staff.destroy', $s->id) }}" onsubmit="return confirm('Delete this staff member?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#ef444422;color:#ef4444;border:none;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;cursor:pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:3rem;color:#71717a;">No staff members yet. <a href="{{ route('superadmin.staff.create') }}" style="color:#a78bfa;">Add one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($staff->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $staff->links() }}</div>
        @endif
    </div>
</div>
<style>
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
@endsection
