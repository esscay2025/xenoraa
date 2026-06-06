@extends('layouts.admin')
@section('title', 'Edit Staff User')
@section('page-title', 'Edit Staff User')
@section('content')
<style>
.uf-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:1.5rem;margin-bottom:1.25rem}
.uf-title{font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem}
.form-label{font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.35rem}
.form-control{width:100%;padding:.6rem .85rem;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.875rem;box-sizing:border-box}
.module-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.5rem;margin-top:.5rem}
.module-check{display:flex;align-items:center;gap:.5rem;padding:.35rem .6rem;background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;cursor:pointer;font-size:.78rem;color:var(--text-secondary)}
.module-check:hover{border-color:var(--accent,#6366f1)}
.module-check input{accent-color:var(--accent,#6366f1)}
.module-check.checked{border-color:var(--accent,#6366f1);background:rgba(99,102,241,.07);color:var(--text-primary)}
.btn{display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.1rem;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none}
.btn-primary{background:var(--accent,#6366f1);color:#fff}
.btn-outline{background:transparent;border:1px solid var(--border);color:var(--text-primary)}
</style>
<div style="max-width:700px">
    <div style="margin-bottom:1.25rem">
        <a href="{{ route('admin.users.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:.85rem"><i class="fas fa-arrow-left"></i> Back to Users</a>
    </div>
    @if($errors->any())
    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444;padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.875rem">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
    @endif
    @if(session('success'))
    <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#22c55e;padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.875rem">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="uf-card">
            <div class="uf-title"><i class="fas fa-user"></i> User Details</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
                </div>
                <div>
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status',$user->status)==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status',$user->status)==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">New Password <span style="color:var(--text-muted);font-weight:400">(optional)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                    <input type="hidden" name="password_confirmation" value="">
                </div>
            </div>
        </div>
        <div class="uf-card">
            <div class="uf-title"><i class="fas fa-user-tag"></i> Role &amp; Access</div>
            <div style="margin-bottom:1rem">
                <label class="form-label">Assign Role *</label>
                <select name="role_id" class="form-control" required>
                    <option value="">Select a role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" data-modules="{{ json_encode($role->modules??[]) }}" {{ old('role_id',$user->role_id)==$role->id?'selected':'' }}>{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </div>
            @php $hasOverride = $user->module_permissions !== null; @endphp
            <div style="margin-bottom:.75rem">
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.875rem;font-weight:500">
                    <input type="checkbox" name="override_modules" id="overrideModules" value="1"
                           {{ $hasOverride||old('override_modules')?'checked':'' }}
                           style="width:16px;height:16px;accent-color:var(--accent,#6366f1)">
                    Override module access for this user
                </label>
                <p style="font-size:.75rem;color:var(--text-muted);margin-top:.25rem">If unchecked, user inherits modules from their assigned role.</p>
            </div>
            <div id="moduleOverrideSection" style="display:{{ $hasOverride||old('override_modules')?'block':'none' }}">
                <label class="form-label">Select Modules</label>
                <div class="module-grid">
                    @foreach($availableModules as $key => $mod)
                        @php
                            $userMods = old('module_permissions', $user->module_permissions ?? []);
                            $checked = in_array($key, $userMods);
                        @endphp
                        <label class="module-check {{ $checked?'checked':'' }}" id="um-{{ $key }}">
                            <input type="checkbox" name="module_permissions[]" value="{{ $key }}" {{ $checked?'checked':'' }} class="mod-cb">
                            <i class="{{ $mod['icon'] }}" style="font-size:.75rem;opacity:.7"></i>
                            {{ $mod['label'] }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div style="display:flex;gap:.75rem">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('overrideModules').addEventListener('change',function(){
    document.getElementById('moduleOverrideSection').style.display=this.checked?'block':'none';
});
document.querySelectorAll('.mod-cb').forEach(function(cb){
    cb.addEventListener('change',function(){
        this.closest('.module-check').classList.toggle('checked',this.checked);
    });
});
</script>
@endsection
