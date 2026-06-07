@extends('layouts.superadmin')
@section('title', 'Roles & Permissions')
@section('content')
<div class="sa-content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <a href="{{ route('superadmin.staff.index') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Staff</a>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Roles & Permissions</h1>
            <p style="font-size:0.8rem;color:#71717a;margin:0;">Configure what each role can access in the super admin panel.</p>
        </div>
    </div>

    @if(session('success'))<div style="background:#22c55e22;border:1px solid #22c55e;color:#86efac;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('success') }}</div>@endif

    @foreach($roles as $role)
    <div class="sa-card" style="margin-bottom:1.5rem;">
        <div class="sa-card-header">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                @php $roleColors = ['superadmin'=>'#ef4444','staff'=>'#3b82f6','agent'=>'#22c55e']; @endphp
                <span style="background:{{ $roleColors[$role->name]??'#7c3aed' }}22;color:{{ $roleColors[$role->name]??'#7c3aed' }};padding:0.25rem 0.75rem;border-radius:20px;font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;">{{ $role->name }}</span>
                <span class="sa-card-title">{{ $role->display_name }}</span>
                @if($role->name === 'superadmin')<span style="font-size:0.72rem;color:#71717a;margin-left:0.5rem;">— Full access, cannot be modified</span>@endif
            </div>
        </div>

        @if($role->name !== 'superadmin')
        <form method="POST" action="{{ route('superadmin.staff.roles.update', $role->id) }}">
            @csrf @method('PUT')
            <div style="padding:1.5rem;">
                @php $rolePermIds = $role->saPermissions->pluck('id')->toArray(); @endphp
                @foreach($permissions as $group => $perms)
                <div style="margin-bottom:1.5rem;">
                    <div style="font-size:0.72rem;font-weight:800;color:#7c3aed;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;padding-bottom:0.5rem;border-bottom:1px solid #27272a;">{{ $group }}</div>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem;">
                        @foreach($perms as $perm)
                        @php $checked = in_array($perm->id, $rolePermIds); @endphp
                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;padding:0.5rem 0.75rem;border:1px solid {{ $checked ? '#7c3aed' : '#27272a' }};border-radius:8px;"
                               onmouseover="this.style.borderColor='#7c3aed'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#27272a'">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" {{ $checked ? 'checked' : '' }}
                                   style="accent-color:#7c3aed;width:15px;height:15px;"
                                   onchange="this.closest('label').style.borderColor = this.checked ? '#7c3aed' : '#27272a'">
                            <span style="font-size:0.78rem;color:#d4d4d8;">{{ $perm->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" onclick="toggleAllInForm(this, true)" style="background:#27272a;color:#a1a1aa;border:none;padding:0.5rem 1rem;border-radius:6px;font-size:0.8rem;cursor:pointer;">Select All</button>
                <button type="button" onclick="toggleAllInForm(this, false)" style="background:#27272a;color:#a1a1aa;border:none;padding:0.5rem 1rem;border-radius:6px;font-size:0.8rem;cursor:pointer;">Clear All</button>
                <button type="submit" class="sa-btn-primary">Save {{ $role->display_name }} Permissions</button>
            </div>
        </form>
        @else
        <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem;">
            @foreach($permissions as $group => $perms)
            @foreach($perms as $perm)
            <div style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 0.75rem;border:1px solid #7c3aed44;border-radius:8px;background:#7c3aed11;">
                <i class="fas fa-check" style="color:#7c3aed;font-size:0.7rem;"></i>
                <span style="font-size:0.78rem;color:#a78bfa;">{{ $perm->label }}</span>
            </div>
            @endforeach
            @endforeach
        </div>
        @endif
    </div>
    @endforeach
</div>
<style>
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
<script>
function toggleAllInForm(btn, state) {
    const form = btn.closest('form');
    form.querySelectorAll('input[type=checkbox]').forEach(cb => {
        cb.checked = state;
        cb.closest('label').style.borderColor = state ? '#7c3aed' : '#27272a';
    });
}
</script>
@endsection
