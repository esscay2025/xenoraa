@extends('layouts.superadmin')
@section('title', 'Add Staff Member')
@section('content')
<div class="sa-content" style="max-width:800px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('superadmin.staff.index') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Staff</a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Add Staff Member</h1>
        <p style="font-size:0.8rem;color:#71717a;margin:0;">Staff members can access super admin with specific module permissions.</p>
    </div>

    @if(session('error'))<div style="background:#ef444422;border:1px solid #ef4444;color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('error') }}</div>@endif

    <form method="POST" action="{{ route('superadmin.staff.store') }}">
        @csrf
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-user" style="color:#3b82f6;margin-right:0.5rem;"></i> Account Details</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><label class="sa-label">Full Name *</label><input type="text" name="name" value="{{ old('name') }}" required class="sa-input" placeholder="Staff member name">@error('name')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="sa-input" placeholder="staff@xenoraa.com">@error('email')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Password *</label><input type="password" name="password" required class="sa-input" placeholder="Min. 8 characters">@error('password')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="sa-input"></div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header">
                <span class="sa-card-title"><i class="fas fa-shield-alt" style="color:#f59e0b;margin-right:0.5rem;"></i> Module Permissions</span>
                <button type="button" onclick="toggleAll(true)" style="background:#27272a;color:#a1a1aa;border:none;padding:0.3rem 0.75rem;border-radius:6px;font-size:0.75rem;cursor:pointer;margin-right:0.5rem;">Select All</button>
                <button type="button" onclick="toggleAll(false)" style="background:#27272a;color:#a1a1aa;border:none;padding:0.3rem 0.75rem;border-radius:6px;font-size:0.75rem;cursor:pointer;">Clear All</button>
            </div>
            <div style="padding:1.5rem;">
                @foreach($permissions as $group => $perms)
                <div style="margin-bottom:1.5rem;">
                    <div style="font-size:0.72rem;font-weight:800;color:#7c3aed;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;padding-bottom:0.5rem;border-bottom:1px solid #27272a;">{{ $group }}</div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;">
                        @foreach($perms as $perm)
                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;padding:0.5rem 0.75rem;border:1px solid #27272a;border-radius:8px;transition:border-color 0.2s;"
                               onmouseover="this.style.borderColor='#7c3aed'" onmouseout="this.style.borderColor='#27272a'">
                            <input type="checkbox" name="permissions[{{ $perm->key }}]" value="1" {{ old('permissions.'.$perm->key) ? 'checked' : '' }}
                                   style="accent-color:#7c3aed;width:16px;height:16px;">
                            <span style="font-size:0.8rem;color:#d4d4d8;">{{ $perm->display_name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <a href="{{ route('superadmin.staff.index') }}" style="background:#27272a;color:#a1a1aa;padding:0.75rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Cancel</a>
            <button type="submit" class="sa-btn-primary">Create Staff Member</button>
        </div>
    </form>
</div>
<style>
.sa-input{width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box;}
.sa-input:focus{border-color:#7c3aed;}
.sa-label{display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;}
.sa-err{color:#ef4444;font-size:0.75rem;}
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
<script>
function toggleAll(state) {
    document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = state);
}
</script>
@endsection
