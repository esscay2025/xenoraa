@extends('layouts.admin')
@section('title', 'Menu Builder')
@section('page-title', 'Menu Builder')

@section('content')
<style>
.mb-layout { display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start; }
.mb-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.5rem; }
.mb-section-title { font-size:0.85rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.06em; margin:0 0 1rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border); }
.mb-item {
    background:var(--bg-secondary);
    border:1px solid var(--border);
    border-radius:10px;
    padding:0.75rem 1rem;
    margin-bottom:0.5rem;
    display:flex;
    align-items:center;
    gap:0.75rem;
    cursor:grab;
    transition:all 0.15s;
    user-select:none;
}
.mb-item:active { cursor:grabbing; opacity:0.8; }
.mb-item.dragging { opacity:0.4; border-style:dashed; }
.mb-item.drag-over { border-color:var(--accent,#6366f1); background:rgba(99,102,241,0.05); }
.mb-drag-handle { color:var(--text-muted); font-size:0.9rem; cursor:grab; }
.mb-item-icon { width:28px; height:28px; border-radius:6px; background:var(--bg-hover); display:flex; align-items:center; justify-content:center; font-size:0.75rem; color:var(--text-muted); }
.mb-item-label { flex:1; font-size:0.875rem; font-weight:600; }
.mb-item-url { font-size:0.72rem; color:var(--text-muted); }
.mb-item-actions { display:flex; gap:0.35rem; }
.mb-item-btn { background:none; border:none; color:var(--text-muted); cursor:pointer; padding:0.25rem; font-size:0.8rem; border-radius:4px; transition:all 0.15s; }
.mb-item-btn:hover { color:var(--text-primary); background:var(--bg-hover); }
.mb-item-btn.del:hover { color:var(--danger); }
.mb-empty-state { text-align:center; padding:3rem 1rem; color:var(--text-muted); }
.mb-empty-state i { font-size:2.5rem; margin-bottom:0.75rem; display:block; opacity:0.3; }
.mb-page-btn { display:flex; align-items:center; gap:0.6rem; padding:0.5rem 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-secondary); cursor:pointer; font-size:0.82rem; color:var(--text-secondary); transition:all 0.15s; width:100%; text-align:left; margin-bottom:0.4rem; }
.mb-page-btn:hover { border-color:var(--accent,#6366f1); color:var(--text-primary); background:rgba(99,102,241,0.05); }
.mb-save-bar { position:sticky; bottom:0; background:var(--bg-secondary); border-top:1px solid var(--border); padding:1rem 0; margin-top:1.5rem; display:flex; align-items:center; gap:1rem; }
@media(max-width:768px) { .mb-layout { grid-template-columns:1fr; } }
</style>

<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.site.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Site Builder</a>
</div>

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:800;margin:0;">Menu Builder</h1>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;font-size:0.9rem;">Drag to reorder. Add system pages, custom pages, or any URL.</p>
    </div>
    <button onclick="saveMenu()" class="btn btn-primary" id="saveBtn">
        <i class="fas fa-save"></i> Save Menu
    </button>
</div>

<div class="mb-layout">
    {{-- Menu Items List --}}
    <div>
        <div class="mb-card">
            <div class="mb-section-title"><i class="fas fa-bars" style="margin-right:0.4rem;"></i> Navigation Items <span id="itemCount" style="background:var(--bg-hover);padding:0.1rem 0.5rem;border-radius:10px;font-size:0.72rem;margin-left:0.5rem;">{{ $items->count() }}</span></div>

            <div id="menuList">
                @forelse($items as $item)
                <div class="mb-item" draggable="true" data-id="{{ $item->id }}"
                    data-label="{{ $item->label }}" data-url="{{ $item->url }}"
                    data-target="{{ $item->target }}" data-icon="{{ $item->icon }}">
                    <i class="fas fa-grip-vertical mb-drag-handle"></i>
                    <div class="mb-item-icon">
                        <i class="{{ $item->icon ?: 'fas fa-link' }}"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="mb-item-label">{{ $item->label }}</div>
                        <div class="mb-item-url">{{ $item->url }}</div>
                    </div>
                    @if($item->target === '_blank')
                        <span style="font-size:0.65rem;color:var(--text-muted);background:var(--bg-hover);padding:0.1rem 0.4rem;border-radius:4px;">new tab</span>
                    @endif
                    <div class="mb-item-actions">
                        <button class="mb-item-btn" onclick="editItem(this)" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                        <button class="mb-item-btn del" onclick="removeItem(this)" title="Remove"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                @empty
                <div class="mb-empty-state" id="emptyState">
                    <i class="fas fa-bars"></i>
                    <div style="font-size:0.9rem;font-weight:600;margin-bottom:0.35rem;">No menu items yet</div>
                    <p style="font-size:0.8rem;max-width:250px;margin:0 auto;">Add pages from the panel on the right, or add a custom link.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Edit Item Modal (inline) --}}
        <div id="editPanel" class="mb-card" style="display:none;margin-top:1.25rem;">
            <div class="mb-section-title"><i class="fas fa-pencil-alt" style="margin-right:0.4rem;"></i> Edit Menu Item</div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Label <span style="color:var(--danger);">*</span></label>
                <input type="text" id="editLabel" class="form-control" placeholder="e.g. Home, About, Blog">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">URL <span style="color:var(--danger);">*</span></label>
                <input type="text" id="editUrl" class="form-control" placeholder="/username/about or https://...">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Icon (Font Awesome class)</label>
                    <input type="text" id="editIcon" class="form-control" placeholder="fas fa-home">
                </div>
                <div class="form-group">
                    <label class="form-label">Open In</label>
                    <select id="editTarget" class="form-control">
                        <option value="_self">Same Tab</option>
                        <option value="_blank">New Tab</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;">
                <button onclick="applyEdit()" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Apply</button>
                <button onclick="cancelEdit()" class="btn btn-outline btn-sm">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Add Items Panel --}}
    <div>
        {{-- System Pages --}}
        <div class="mb-card">
            <div class="mb-section-title"><i class="fas fa-globe" style="margin-right:0.4rem;"></i> System Pages</div>
            @foreach($systemPages as $sp)
            <button class="mb-page-btn" onclick="addItem('{{ $sp['label'] }}', '{{ $sp['url'] }}', 'fas fa-link')">
                <i class="fas fa-plus" style="color:var(--accent,#6366f1);"></i>
                <span>{{ $sp['label'] }}</span>
                <span style="margin-left:auto;font-size:0.7rem;opacity:0.5;">{{ $sp['url'] }}</span>
            </button>
            @endforeach
        </div>

        {{-- Custom Pages --}}
        @if($customPages->isNotEmpty())
        <div class="mb-card" style="margin-top:1rem;">
            <div class="mb-section-title"><i class="fas fa-file-alt" style="margin-right:0.4rem;"></i> Your Pages</div>
            @foreach($customPages as $cp)
            <button class="mb-page-btn" onclick="addItem('{{ $cp->title }}', '{{ $cp->public_url }}', 'fas fa-file-alt')">
                <i class="fas fa-plus" style="color:var(--success);"></i>
                <span>{{ $cp->title }}</span>
            </button>
            @endforeach
        </div>
        @endif

        {{-- Custom Link --}}
        <div class="mb-card" style="margin-top:1rem;">
            <div class="mb-section-title"><i class="fas fa-link" style="margin-right:0.4rem;"></i> Custom Link</div>
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label">Label</label>
                <input type="text" id="customLabel" class="form-control" placeholder="Link text">
            </div>
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label">URL</label>
                <input type="text" id="customUrl" class="form-control" placeholder="https://... or /path">
            </div>
            <button onclick="addCustomLink()" class="btn btn-primary btn-sm" style="width:100%;">
                <i class="fas fa-plus"></i> Add to Menu
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let editingItem = null;

// Sortable drag-and-drop
const menuList = document.getElementById('menuList');
const sortable = Sortable.create(menuList, {
    animation: 150,
    ghostClass: 'dragging',
    handle: '.mb-drag-handle',
    onEnd: updateCount
});

function updateCount() {
    const count = menuList.querySelectorAll('.mb-item').length;
    document.getElementById('itemCount').textContent = count;
    const empty = document.getElementById('emptyState');
    if (empty) empty.style.display = count > 0 ? 'none' : 'block';
}

function addItem(label, url, icon = 'fas fa-link', target = '_self') {
    const empty = document.getElementById('emptyState');
    if (empty) empty.style.display = 'none';

    const div = document.createElement('div');
    div.className = 'mb-item';
    div.draggable = true;
    div.dataset.label = label;
    div.dataset.url = url;
    div.dataset.target = target;
    div.dataset.icon = icon;
    div.innerHTML = `
        <i class="fas fa-grip-vertical mb-drag-handle"></i>
        <div class="mb-item-icon"><i class="${icon}"></i></div>
        <div style="flex:1;min-width:0;">
            <div class="mb-item-label">${label}</div>
            <div class="mb-item-url">${url}</div>
        </div>
        <div class="mb-item-actions">
            <button class="mb-item-btn" onclick="editItem(this)" title="Edit"><i class="fas fa-pencil-alt"></i></button>
            <button class="mb-item-btn del" onclick="removeItem(this)" title="Remove"><i class="fas fa-trash"></i></button>
        </div>
    `;
    menuList.appendChild(div);
    updateCount();
}

function addCustomLink() {
    const label = document.getElementById('customLabel').value.trim();
    const url = document.getElementById('customUrl').value.trim();
    if (!label || !url) { alert('Please enter both label and URL.'); return; }
    addItem(label, url, 'fas fa-external-link-alt');
    document.getElementById('customLabel').value = '';
    document.getElementById('customUrl').value = '';
}

function removeItem(btn) {
    if (!confirm('Remove this menu item?')) return;
    btn.closest('.mb-item').remove();
    updateCount();
}

function editItem(btn) {
    editingItem = btn.closest('.mb-item');
    document.getElementById('editLabel').value = editingItem.dataset.label;
    document.getElementById('editUrl').value = editingItem.dataset.url;
    document.getElementById('editIcon').value = editingItem.dataset.icon || '';
    document.getElementById('editTarget').value = editingItem.dataset.target || '_self';
    document.getElementById('editPanel').style.display = 'block';
    document.getElementById('editPanel').scrollIntoView({ behavior:'smooth', block:'nearest' });
}

function applyEdit() {
    if (!editingItem) return;
    const label = document.getElementById('editLabel').value.trim();
    const url = document.getElementById('editUrl').value.trim();
    const icon = document.getElementById('editIcon').value.trim() || 'fas fa-link';
    const target = document.getElementById('editTarget').value;
    if (!label || !url) { alert('Label and URL are required.'); return; }

    editingItem.dataset.label = label;
    editingItem.dataset.url = url;
    editingItem.dataset.icon = icon;
    editingItem.dataset.target = target;
    editingItem.querySelector('.mb-item-label').textContent = label;
    editingItem.querySelector('.mb-item-url').textContent = url;
    editingItem.querySelector('.mb-item-icon i').className = icon;

    // Update new tab badge
    const existing = editingItem.querySelector('.new-tab-badge');
    if (existing) existing.remove();
    if (target === '_blank') {
        const badge = document.createElement('span');
        badge.className = 'new-tab-badge';
        badge.style.cssText = 'font-size:0.65rem;color:var(--text-muted);background:var(--bg-hover);padding:0.1rem 0.4rem;border-radius:4px;';
        badge.textContent = 'new tab';
        editingItem.querySelector('.mb-item-actions').before(badge);
    }

    cancelEdit();
}

function cancelEdit() {
    editingItem = null;
    document.getElementById('editPanel').style.display = 'none';
}

function saveMenu() {
    const items = [];
    menuList.querySelectorAll('.mb-item').forEach((el, i) => {
        items.push({
            label: el.dataset.label,
            url: el.dataset.url,
            target: el.dataset.target || '_self',
            icon: el.dataset.icon || null,
            parent_id: null,
            sort_order: i
        });
    });

    if (items.length === 0) {
        if (!confirm('Save an empty menu? This will clear all navigation items.')) return;
    }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    fetch('{{ route("admin.site.menu.save") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ items })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Save Menu';
        if (data.success) {
            showToast(`Menu saved — ${data.count} item${data.count !== 1 ? 's' : ''}`, 'success');
        } else {
            showToast('Failed to save menu.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Save Menu';
        showToast('Error saving menu.', 'error');
    });
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;bottom:2rem;right:2rem;background:${type==='success'?'#22c55e':'#ef4444'};color:#fff;padding:0.75rem 1.25rem;border-radius:10px;font-weight:600;font-size:0.85rem;z-index:99999;box-shadow:0 8px 24px rgba(0,0,0,0.3);`;
    toast.innerHTML = `<i class="fas fa-${type==='success'?'check':'times'}-circle" style="margin-right:0.5rem;"></i>${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; toast.style.transition='opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

updateCount();
</script>
@endsection
