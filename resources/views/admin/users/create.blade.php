@extends("layouts.admin")
@section("title", "Add Staff User")
@section("page-title", "Add Staff User")
@section("content")
<style>
.uf-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.5rem; margin-bottom:1.25rem; }
.uf-title { font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:1rem; }
.form-label { font-size:0.8rem; font-weight:600; color:var(--text-secondary); display:block; margin-bottom:0.35rem; }
.form-control { width:100%; padding:0.6rem 0.85rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:8px; color:var(--text-primary); font-size:0.875rem; box-sizing:border-box; }
.module-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:0.5rem; margin-top:0.5rem; }
.module-check { display:flex; align-items:center; gap:0.5rem; padding:0.35rem 0.6rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:6px; cursor:pointer; font-size:0.78rem; color:var(--text-secondary); }
.module-check:hover { border-color:var(--accent,#6366f1); }
.module-check input { accent-color:var(--accent,#6366f1); }
.module-check.checked { border-color:var(--accent,#6366f1); background:rgba(99,102,241,0.07); color:var(--text-primary); }
.btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.1rem; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; }
.btn-primary { background:var(--accent,#6366f1); color:#fff; }
.btn-outline { background:transparent; border:1px solid var(--border); color:var(--text-primary); }
</style>
<div style="max-width:700px;">
    <div style="margin-bottom:1.25rem;">
        <a href="{{ route("admin.users.index") }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Back to Users</a>
    </div>
    @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif
    <form method="POST" action="{{ route("admin.users.store") }}">
        @csrf
        <div class="uf-card">
            <div class="uf-title"><i class="fas fa-user"></i> User Details</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old("name") }}" required placeholder="e.g. John Doe">
                </div>
                <div>
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old("email") }}" required placeholder="john@example.com">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required placeholder="Min 8 characters">
                </div>
                <div>
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Repeat password">
                </div>
            </div>
        </div>
        <div class="uf-card">
            <div class="uf-title"><i class="fas fa-user-tag"></i> Role &amp; Access</div>
            <div style="margin-bottom:1rem;">
                <label class="form-label">Assign Role *</label>
                <select name="role_id" class="form-control" id="roleSelect" onchange="onRoleChange(this)" required>
                    <option value="">Select a role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                            data-modules="{{ json_encode($role->modules ?? []) }}"
                            {{ old("role_id") == $role->id ? "selected" : "" }}>
                        {{ $role->display_name }}
                    </option>
                    @endforeach
                </select>
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">The role determines default module access. You can override below.</p>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.875rem;font-weight:500;">
                    <input type="checkbox" name="override_modules" id="overrideModules" value="1"
                           {{ old("override_modules") ? "checked" : "" }}
                           onchange="toggleOverride(this.checked)"
                           style="width:16px;height:16px;accent-color:var(--accent,#6366f1);">
                    Override module access for this user
                </label>
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.25rem;">If unchecked, the user inherits modules from their assigned role.</p>
            </div>
            <div id="moduleOverrideSection" style="display:{{ old("override_modules") ? "block" : "none" }};">
                <label class="form-label">Select Modules</label>
                <div class="module-grid">
                    @foreach($availableModules as $key => $mod)
                        @php $checked = old("override_modules") && in_array($key, old("module_permissions", [])); @endphp
                        <label class="module-check {{ $checked ? "checked" : "" }}" id="um-{{ $key }}">
                            <input type="checkbox" name="module_permissions[]" value="{{ $key }}"
                                   {{ $checked ? "checked" : "" }}
                                   onchange="toggleLabel(this, "um-{{ $key }}")" class="mod-cb">
                            <i class="{{ $mod["icon"] }}" style="font-size:0.75rem;opacity:0.7;"></i>
                            {{ $mod["label"] }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create Staff User</button>
            <a href="{{ route("admin.users.index") }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('overrideModules').addEventListener('change', function() {
    document.getElementById('moduleOverrideSection').style.display = this.checked ? 'block' : 'none';
});
document.querySelectorAll('.module-check input').forEach(function(cb) {
    cb.addEventListener('change', function() {
        this.closest('.module-check').classList.toggle('checked', this.checked);
    });
});
</script>
@endsection
