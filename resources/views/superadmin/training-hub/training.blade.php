@extends('layouts.superadmin')
@section('title', 'AI Training Hub')
@section('page_title', 'AI Training Hub')

@section('content')
<style>
.th-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
.th-stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.th-stat { background:var(--sa-card-bg,#1e293b); border:1px solid var(--sa-border,#334155); border-radius:12px; padding:1.25rem; text-align:center; }
.th-stat-val { font-size:2rem; font-weight:700; color:#6366f1; }
.th-stat-lbl { font-size:0.78rem; color:#94a3b8; margin-top:0.25rem; text-transform:uppercase; letter-spacing:.05em; }
.th-filter-bar { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.th-filter-bar input, .th-filter-bar select { background:#0f172a; border:1px solid #334155; color:#e2e8f0; border-radius:8px; padding:.5rem .9rem; font-size:.875rem; }
.th-filter-bar input:focus, .th-filter-bar select:focus { outline:none; border-color:#6366f1; }
.th-table-wrap { background:var(--sa-card-bg,#1e293b); border:1px solid var(--sa-border,#334155); border-radius:12px; overflow:hidden; }
.th-table { width:100%; border-collapse:collapse; }
.th-table th { background:#0f172a; color:#94a3b8; font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; padding:.85rem 1rem; text-align:left; border-bottom:1px solid #334155; }
.th-table td { padding:.85rem 1rem; border-bottom:1px solid #1e293b; font-size:.875rem; color:#cbd5e1; vertical-align:top; }
.th-table tr:last-child td { border-bottom:none; }
.th-table tr:hover td { background:rgba(99,102,241,.06); }
.th-badge { display:inline-block; padding:.2rem .65rem; border-radius:20px; font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
.th-badge-sales { background:rgba(16,185,129,.15); color:#10b981; }
.th-badge-support { background:rgba(245,158,11,.15); color:#f59e0b; }
.th-badge-general { background:rgba(99,102,241,.15); color:#818cf8; }
.th-badge-xenoraa { background:rgba(139,92,246,.15); color:#a78bfa; }
.th-badge-pricing { background:rgba(236,72,153,.15); color:#ec4899; }
.th-badge-onboarding { background:rgba(14,165,233,.15); color:#38bdf8; }
.th-badge-active { background:rgba(16,185,129,.15); color:#10b981; }
.th-badge-inactive { background:rgba(239,68,68,.15); color:#f87171; }
.th-q { font-weight:600; color:#e2e8f0; margin-bottom:.25rem; }
.th-a { color:#94a3b8; font-size:.82rem; line-height:1.5; max-height:3.5rem; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; }
.th-actions { display:flex; gap:.5rem; }
.th-btn { padding:.35rem .7rem; border-radius:6px; font-size:.75rem; font-weight:600; border:none; cursor:pointer; transition:opacity .15s; }
.th-btn-edit { background:rgba(99,102,241,.2); color:#818cf8; }
.th-btn-del { background:rgba(239,68,68,.15); color:#f87171; }
.th-btn-toggle { background:rgba(16,185,129,.15); color:#10b981; }
.th-btn:hover { opacity:.8; }
.th-add-btn { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border:none; padding:.6rem 1.25rem; border-radius:8px; font-size:.875rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:.5rem; }
.th-add-btn:hover { opacity:.9; }
/* Modal */
.th-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.7); z-index:9999; align-items:center; justify-content:center; }
.th-modal-overlay.open { display:flex; }
.th-modal { background:#1e293b; border:1px solid #334155; border-radius:16px; width:100%; max-width:640px; max-height:90vh; overflow-y:auto; padding:2rem; }
.th-modal h3 { font-size:1.1rem; font-weight:700; color:#e2e8f0; margin:0 0 1.5rem; }
.th-form-group { margin-bottom:1rem; }
.th-form-group label { display:block; font-size:.8rem; color:#94a3b8; margin-bottom:.4rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
.th-form-group input, .th-form-group select, .th-form-group textarea { width:100%; background:#0f172a; border:1px solid #334155; color:#e2e8f0; border-radius:8px; padding:.65rem .9rem; font-size:.875rem; box-sizing:border-box; }
.th-form-group textarea { min-height:120px; resize:vertical; }
.th-form-group input:focus, .th-form-group select:focus, .th-form-group textarea:focus { outline:none; border-color:#6366f1; }
.th-modal-footer { display:flex; justify-content:flex-end; gap:.75rem; margin-top:1.5rem; }
.th-modal-cancel { background:#334155; color:#94a3b8; border:none; padding:.6rem 1.25rem; border-radius:8px; cursor:pointer; font-size:.875rem; }
.th-modal-save { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border:none; padding:.6rem 1.25rem; border-radius:8px; cursor:pointer; font-size:.875rem; font-weight:600; }
.th-tab-bar { display:flex; gap:.5rem; margin-bottom:1.25rem; flex-wrap:wrap; }
.th-tab { padding:.45rem 1rem; border-radius:20px; font-size:.8rem; font-weight:600; border:1px solid #334155; background:transparent; color:#94a3b8; cursor:pointer; text-decoration:none; transition:all .15s; }
.th-tab.active, .th-tab:hover { background:#6366f1; color:#fff; border-color:#6366f1; }
</style>

{{-- Header --}}
<div class="th-header">
    <div>
        <h2 style="font-size:1.3rem;font-weight:700;color:#e2e8f0;margin:0;">AI Training Hub</h2>
        <p style="color:#64748b;font-size:.875rem;margin:.25rem 0 0;">Manage Xenoraa's AI assistant knowledge base</p>
    </div>
    <button class="th-add-btn" onclick="openAddModal()">
        <i class="fas fa-plus"></i> Add Training Entry
    </button>
</div>

{{-- Tab Bar --}}
<div class="th-tab-bar">
    <a href="{{ route('superadmin.training-hub.training') }}" class="th-tab active">
        <i class="fas fa-brain"></i> AI Training
    </a>
    <a href="{{ route('superadmin.training-hub.conversations') }}" class="th-tab">
        <i class="fas fa-comments"></i> AI Conversations
    </a>
</div>

{{-- Stats --}}
<div class="th-stat-grid">
    <div class="th-stat">
        <div class="th-stat-val">{{ $stats['total'] }}</div>
        <div class="th-stat-lbl">Total Entries</div>
    </div>
    <div class="th-stat">
        <div class="th-stat-val" style="color:#10b981;">{{ $stats['active'] }}</div>
        <div class="th-stat-lbl">Active</div>
    </div>
    <div class="th-stat">
        <div class="th-stat-val" style="color:#f59e0b;">{{ $stats['inactive'] }}</div>
        <div class="th-stat-lbl">Inactive</div>
    </div>
    <div class="th-stat">
        <div class="th-stat-val" style="color:#8b5cf6;">{{ $stats['cats'] }}</div>
        <div class="th-stat-lbl">Categories</div>
    </div>
</div>

@if(session('success'))
<div style="background:rgba(16,185,129,.15);border:1px solid #10b981;color:#10b981;padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.875rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Filter Bar --}}
<form method="GET" class="th-filter-bar">
    <input type="text" name="search" placeholder="Search questions or answers..." value="{{ $search }}" style="flex:1;min-width:200px;">
    <select name="category">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->category }}" @selected($category === $cat->category)>
            {{ ucfirst($cat->category) }} ({{ $cat->count }})
        </option>
        @endforeach
    </select>
    <button type="submit" style="background:#6366f1;color:#fff;border:none;padding:.5rem 1rem;border-radius:8px;cursor:pointer;font-size:.875rem;">
        <i class="fas fa-search"></i> Filter
    </button>
    @if($search || $category)
    <a href="{{ route('superadmin.training-hub.training') }}" style="background:#334155;color:#94a3b8;padding:.5rem 1rem;border-radius:8px;font-size:.875rem;text-decoration:none;">
        <i class="fas fa-times"></i> Clear
    </a>
    @endif
</form>

{{-- Table --}}
<div class="th-table-wrap">
    <table class="th-table">
        <thead>
            <tr>
                <th style="width:35%;">Question</th>
                <th style="width:40%;">Answer</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainings as $t)
            <tr>
                <td>
                    <div class="th-q">{{ $t->question }}</div>
                </td>
                <td>
                    <div class="th-a">{{ $t->answer }}</div>
                </td>
                <td>
                    <span class="th-badge th-badge-{{ $t->category }}">{{ ucfirst($t->category) }}</span>
                </td>
                <td>
                    <span class="th-badge {{ $t->is_active ? 'th-badge-active' : 'th-badge-inactive' }}"
                          id="status-badge-{{ $t->id }}">
                        {{ $t->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="th-actions">
                        <button class="th-btn th-btn-edit" onclick="openEditModal({{ $t->id }}, '{{ addslashes($t->category) }}', '{{ addslashes($t->question) }}', '{{ addslashes($t->answer) }}', {{ $t->is_active ? 'true' : 'false' }}, {{ $t->sort_order ?? 0 }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="th-btn th-btn-toggle" onclick="toggleEntry({{ $t->id }})" title="Toggle Active">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <form method="POST" action="{{ route('superadmin.training-hub.training.destroy', $t->id) }}" style="display:inline;" onsubmit="return confirm('Delete this entry?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="th-btn th-btn-del"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:2.5rem;color:#475569;">
                    <i class="fas fa-brain" style="font-size:2rem;margin-bottom:.75rem;display:block;opacity:.3;"></i>
                    No training entries found. Add your first entry to train Xena!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($trainings->hasPages())
<div style="margin-top:1rem;">{{ $trainings->links() }}</div>
@endif

{{-- Add Modal --}}
<div class="th-modal-overlay" id="addModal">
    <div class="th-modal">
        <h3><i class="fas fa-plus-circle" style="color:#6366f1;"></i> Add Training Entry</h3>
        <form method="POST" action="{{ route('superadmin.training-hub.training.store') }}">
            @csrf
            <div class="th-form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="sales">Sales</option>
                    <option value="support">Support</option>
                    <option value="general">General</option>
                    <option value="xenoraa">Xenoraa Platform</option>
                    <option value="pricing">Pricing</option>
                    <option value="onboarding">Onboarding</option>
                    <option value="features">Features</option>
                    <option value="technical">Technical</option>
                </select>
            </div>
            <div class="th-form-group">
                <label>Question / Trigger</label>
                <input type="text" name="question" required placeholder="e.g. What plans do you offer?" maxlength="1000">
            </div>
            <div class="th-form-group">
                <label>Answer / Response</label>
                <textarea name="answer" required placeholder="Write a comprehensive, helpful answer..." maxlength="5000"></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="th-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="0" min="0">
                </div>
                <div class="th-form-group" style="display:flex;align-items:center;gap:.5rem;padding-top:1.5rem;">
                    <input type="checkbox" name="is_active" value="1" checked id="addActive" style="width:auto;">
                    <label for="addActive" style="margin:0;text-transform:none;font-size:.875rem;color:#e2e8f0;">Active</label>
                </div>
            </div>
            <div class="th-modal-footer">
                <button type="button" class="th-modal-cancel" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="th-modal-save"><i class="fas fa-save"></i> Save Entry</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="th-modal-overlay" id="editModal">
    <div class="th-modal">
        <h3><i class="fas fa-edit" style="color:#6366f1;"></i> Edit Training Entry</h3>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="th-form-group">
                <label>Category</label>
                <select name="category" id="editCategory" required>
                    <option value="sales">Sales</option>
                    <option value="support">Support</option>
                    <option value="general">General</option>
                    <option value="xenoraa">Xenoraa Platform</option>
                    <option value="pricing">Pricing</option>
                    <option value="onboarding">Onboarding</option>
                    <option value="features">Features</option>
                    <option value="technical">Technical</option>
                </select>
            </div>
            <div class="th-form-group">
                <label>Question / Trigger</label>
                <input type="text" name="question" id="editQuestion" required maxlength="1000">
            </div>
            <div class="th-form-group">
                <label>Answer / Response</label>
                <textarea name="answer" id="editAnswer" required maxlength="5000"></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="th-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" id="editSortOrder" min="0">
                </div>
                <div class="th-form-group" style="display:flex;align-items:center;gap:.5rem;padding-top:1.5rem;">
                    <input type="checkbox" name="is_active" value="1" id="editActive" style="width:auto;">
                    <label for="editActive" style="margin:0;text-transform:none;font-size:.875rem;color:#e2e8f0;">Active</label>
                </div>
            </div>
            <div class="th-modal-footer">
                <button type="button" class="th-modal-cancel" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="th-modal-save"><i class="fas fa-save"></i> Update Entry</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.add('open');
}
function openEditModal(id, category, question, answer, isActive, sortOrder) {
    document.getElementById('editForm').action = '/superadmin/training-hub/training/' + id;
    document.getElementById('editCategory').value = category;
    document.getElementById('editQuestion').value = question;
    document.getElementById('editAnswer').value = answer;
    document.getElementById('editActive').checked = isActive;
    document.getElementById('editSortOrder').value = sortOrder;
    document.getElementById('editModal').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.th-modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});
async function toggleEntry(id) {
    const res = await fetch('/superadmin/training-hub/training/' + id + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '', 'Accept': 'application/json' }
    });
    const data = await res.json();
    const badge = document.getElementById('status-badge-' + id);
    if (badge) {
        badge.textContent = data.is_active ? 'Active' : 'Inactive';
        badge.className = 'th-badge ' + (data.is_active ? 'th-badge-active' : 'th-badge-inactive');
    }
}
</script>
@endsection
