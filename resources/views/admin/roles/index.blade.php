@extends('layouts.admin')
@section('title', 'Role Management')
@section('page-title', 'Role Management')
@section('content')
<style>
.rm-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
@media(max-width:900px){ .rm-grid{grid-template-columns:1fr;} }
.rm-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.5rem; }
.rm-card-title { font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; }
.role-row { background:var(--bg-secondary); border:1px solid var(--border); border-radius:10px; padding:1rem 1.25rem; margin-bottom:0.75rem; }
.role-row-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem; }
.role-name { font-size:0.875rem; font-weight:700; color:var(--text-primary); }
.role-desc { font-size:0.75rem; color:var(--text-muted); }
.module-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:0.5rem; }
.module-check { display:flex; align-items:center; gap:0.5rem; padding:0.35rem 0.6rem; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; cursor:pointer; font-size:0.78rem; color:var(--text-secondary); }
.module-check:hover { border-color:var(--accent,#6366f1); }
.module-check input { accent-color:var(--accent,#6366f1); }
.module-check.checked { border-color:var(--accent,#6366f1); background:rgba(99,102,241,0.07); color:var(--text-primary); }
.btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 0.9rem; border-radius:7px; font-size:0.8rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; }
.btn-primary { background:var(--accent,#6366f1); color:#fff; }
.btn-danger { background:#ef4444; color:#fff; }
.btn-outline { background:transparent; border:1px solid var(--border); color:var(--text-primary); }
.form-control { width:100%; padding:0.55rem 0.8rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:8px; color:var(--text-primary); font-size:0.875rem; box-sizing:border-box; }
.form-label { font-size:0.78rem; font-weight:600; color:var(--text-secondary); display:block; margin-bottom:0.3rem; }
.badge { display:inline-flex; align-items:center; padding:0.15rem 0.5rem; border-radius:20px; font-size:0.68rem; font-weight:700; }
.badge-system { background:rgba(99,102,241,0.12); color:var(--accent,#6366f1); }
.badge-custom { background:rgba(34,197,94,0.12); color:#22c55e; }
</style>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;margin:0;">Role Management</h1>
        <p style="font-size:0.82rem;color:var(--text-muted);margin:0.25rem 0 0;">Configure default module access for each role. Staff users can also have individual module overrides.</p>
    </div>
</div>

@if(session('success'))
    <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="rm-grid">
    {{-- System Roles --}}
    <div>
        <div class="rm-card">
            <div class="rm-card-title"><i class="fas fa-shield-alt"></i> System Roles</div>
            <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:1.25rem;">Configure which modules are enabled by default for each system role. Admin always has full access.</p>

            @foreach($systemRoles as $role)
                @if($role->name !== 'admin')
                <div class="role-row">
                    <div class="role-row-header">
                        <div>
                            <div class="role-name">{{ $role->display_name }} <span class="badge badge-system">System</span></div>
                            <div class="role-desc">{{ $role->description }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.roles.update-system', $role) }}">
                        @csrf @method('PUT')
                        <div class="module-grid" style="margin-bottom:0.75rem;">
                            @foreach($availableModules as $key => $mod)
                                @php $checked = in_array('*', $role->modules ?? []) || in_array($key, $role->modules ?? []); @endphp
                                <label class="module-check {{ $checked ? 'checked' : '' }}" id="mlabel-{{ $role->id }}-{{ $key }}">
                                    <input type="checkbox" name="modules[]" value="{{ $key }}"
                                           {{ $checked ? 'checked' : '' }}
                                           onchange="toggleModuleLabel(this, 'mlabel-{{ $role->id }}-{{ $key }}')">
                                    <i class="{{ $mod['icon'] }}" style="font-size:0.75rem;opacity:0.7;"></i>
                                    {{ $mod['label'] }}
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary" style="font-size:0.78rem;padding:0.35rem 0.75rem;">
                            <i class="fas fa-save"></i> Save Modules
                        </button>
                    </form>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Custom Roles --}}
    <div>
        <div class="rm-card" style="margin-bottom:1.25rem;">
            <div class="rm-card-title"><i class="fas fa-plus-circle"></i> Create Custom Role</div>
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div style="margin-bottom:0.75rem;">
                    <label class="form-label">Role Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="display_name" class="form-control" required placeholder="e.g. Content Editor, Sales Manager">
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Brief description of this role">
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Default Modules</label>
                    <div class="module-grid">
                        @foreach($availableModules as $key => $mod)
                            <label class="module-check" id="new-mlabel-{{ $key }}">
                                <input type="checkbox" name="modules[]" value="{{ $key }}"
                                       onchange="toggleModuleLabel(this, 'new-mlabel-{{ $key }}')">
                                <i class="{{ $mod['icon'] }}" style="font-size:0.75rem;opacity:0.7;"></i>
                                {{ $mod['label'] }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Role</button>
            </form>
        </div>

        @if($customRoles->count())
        <div class="rm-card">
            <div class="rm-card-title"><i class="fas fa-user-tag"></i> Your Custom Roles</div>
            @foreach($customRoles as $role)
            <div class="role-row">
                <div class="role-row-header">
                    <div>
                        <div class="role-name">{{ $role->display_name }} <span class="badge badge-custom">Custom</span></div>
                        <div class="role-desc">{{ $role->description ?? 'No description' }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="font-size:0.75rem;padding:0.3rem 0.6rem;"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf @method('PUT')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:0.75rem;">
                        <div>
                            <label class="form-label">Role Name</label>
                            <input type="text" name="display_name" class="form-control" value="{{ $role->display_name }}" required>
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" value="{{ $role->description }}">
                        </div>
                    </div>
                    <div class="module-grid" style="margin-bottom:0.75rem;">
                        @foreach($availableModules as $key => $mod)
                            @php $checked = in_array('*', $role->modules ?? []) || in_array($key, $role->modules ?? []); @endphp
                            <label class="module-check {{ $checked ? 'checked' : '' }}" id="cr-mlabel-{{ $role->id }}-{{ $key }}">
                                <input type="checkbox" name="modules[]" value="{{ $key }}"
                                       {{ $checked ? 'checked' : '' }}
                                       onchange="toggleModuleLabel(this, 'cr-mlabel-{{ $role->id }}-{{ $key }}')">
                                <i class="{{ $mod['icon'] }}" style="font-size:0.75rem;opacity:0.7;"></i>
                                {{ $mod['label'] }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary" style="font-size:0.78rem;padding:0.35rem 0.75rem;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
function toggleModuleLabel(checkbox, labelId) {
    const label = document.getElementById(labelId);
    if (label) label.classList.toggle('checked', checkbox.checked);
}
</script>
@endsection
