@extends('layouts.admin')

@section('title', 'CRM — Chatbot Training')

@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = true; $siteActive = false;
@endphp

@section('content')
<div style="padding:2rem;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <a href="{{ route('admin.crm.leads') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;display:block;margin-bottom:0.5rem;"><i class="fas fa-arrow-left"></i> Back to Leads</a>
            <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">Train AI Chatbot</h1>
            <p style="color:#9ca3af;margin:0.25rem 0 0;">Add knowledge entries to improve how Gopi AI responds to visitors.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;">

        {{-- Training Entries List --}}
        <div>
            @php $grouped = $trainings->groupBy('category'); @endphp
            @forelse($grouped as $cat => $entries)
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;margin-bottom:1.25rem;overflow:hidden;">
                <div style="background:#0f172a;padding:0.875rem 1.25rem;display:flex;align-items:center;gap:0.75rem;">
                    <i class="fas fa-tag" style="color:#6366f1;"></i>
                    <span style="color:#fff;font-weight:600;text-transform:capitalize;">{{ str_replace('_',' ',$cat) }}</span>
                    <span style="background:#1e3a5f;color:#60a5fa;padding:0.15rem 0.5rem;border-radius:20px;font-size:0.75rem;margin-left:auto;">{{ $entries->count() }} entries</span>
                </div>
                @foreach($entries as $entry)
                <div style="padding:1rem 1.25rem;border-bottom:1px solid #1e293b;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                        <div style="flex:1;">
                            <div style="color:#a78bfa;font-size:0.8rem;font-weight:600;margin-bottom:0.25rem;">Q: {{ $entry->question }}</div>
                            <div style="color:#e2e8f0;font-size:0.875rem;line-height:1.5;">A: {{ $entry->answer }}</div>
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-shrink:0;align-items:center;">
                            <span style="background:{{ $entry->is_active?'#064e3b':'#450a0a' }};color:{{ $entry->is_active?'#10b981':'#ef4444' }};padding:0.2rem 0.5rem;border-radius:20px;font-size:0.7rem;">
                                {{ $entry->is_active?'Active':'Inactive' }}
                            </span>
                            <button onclick="openEditModal({{ $entry->id }}, '{{ addslashes($entry->category) }}', '{{ addslashes($entry->question) }}', '{{ addslashes($entry->answer) }}', {{ $entry->is_active?'true':'false' }}, {{ $entry->sort_order }})"
                                style="background:#1e3a5f;color:#60a5fa;border:none;padding:0.3rem 0.6rem;border-radius:6px;cursor:pointer;font-size:0.75rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.crm.training.destroy', $entry) }}" onsubmit="return confirm('Delete this entry?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#450a0a;color:#ef4444;border:none;padding:0.3rem 0.6rem;border-radius:6px;cursor:pointer;font-size:0.75rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:3rem;text-align:center;color:#9ca3af;">
                <i class="fas fa-brain" style="font-size:2.5rem;margin-bottom:1rem;display:block;opacity:0.3;"></i>
                No training data yet. Add entries using the form on the right to teach Gopi AI how to respond.
            </div>
            @endforelse
        </div>

        {{-- Add New Entry Form --}}
        <div style="position:sticky;top:1.5rem;">
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;"><i class="fas fa-plus-circle" style="color:#6366f1;margin-right:0.5rem;"></i>Add Training Entry</h3>
                <form method="POST" action="{{ route('admin.crm.training.store') }}">
                    @csrf
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Category</label>
                        <select name="category" required style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Question / Trigger</label>
                        <textarea name="question" rows="2" required placeholder="e.g. What services do you offer?" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;"></textarea>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Answer / Response</label>
                        <textarea name="answer" rows="4" required placeholder="The answer Gopi AI should give..." style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;"></textarea>
                    </div>
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Sort Order</label>
                        <input type="number" name="sort_order" value="0" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                    </div>
                    <button type="submit" style="width:100%;background:#6366f1;color:#fff;padding:0.6rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;">
                        <i class="fas fa-plus"></i> Add Entry
                    </button>
                </form>
            </div>

            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;margin-top:1rem;">
                <h4 style="color:#fff;font-size:0.875rem;font-weight:600;margin:0 0 0.75rem;"><i class="fas fa-lightbulb" style="color:#f59e0b;margin-right:0.5rem;"></i>Training Tips</h4>
                <ul style="color:#9ca3af;font-size:0.8rem;line-height:1.7;padding-left:1.25rem;margin:0;">
                    <li><strong style="color:#e2e8f0;">Greeting:</strong> How Gopi introduces himself</li>
                    <li><strong style="color:#e2e8f0;">Services:</strong> What you offer and how</li>
                    <li><strong style="color:#e2e8f0;">Pricing:</strong> Budget ranges and pricing model</li>
                    <li><strong style="color:#e2e8f0;">Process:</strong> How you work with clients</li>
                    <li><strong style="color:#e2e8f0;">FAQ:</strong> Common questions and answers</li>
                    <li><strong style="color:#e2e8f0;">Objection:</strong> Handle doubts and concerns</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:2rem;width:90%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Edit Training Entry</h3>
        <form id="editForm" method="POST">
            @csrf @method('PATCH')
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Category</label>
                <select id="editCategory" name="category" required style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Question</label>
                <textarea id="editQuestion" name="question" rows="2" required style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;"></textarea>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Answer</label>
                <textarea id="editAnswer" name="answer" rows="4" required style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;"></textarea>
            </div>
            <div style="margin-bottom:1rem;display:flex;align-items:center;gap:0.75rem;">
                <input type="checkbox" id="editActive" name="is_active" value="1" style="width:16px;height:16px;">
                <label for="editActive" style="color:#e2e8f0;font-size:0.875rem;">Active</label>
            </div>
            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Sort Order</label>
                <input type="number" id="editSort" name="sort_order" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
            </div>
            <div style="display:flex;gap:0.75rem;">
                <button type="submit" style="flex:1;background:#6366f1;color:#fff;padding:0.6rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;">Save Changes</button>
                <button type="button" onclick="closeEditModal()" style="background:#374151;color:#e2e8f0;padding:0.6rem 1.25rem;border-radius:8px;border:none;cursor:pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, cat, q, a, active, sort) {
    document.getElementById('editForm').action = '/admin/crm/training/' + id;
    document.getElementById('editCategory').value = cat;
    document.getElementById('editQuestion').value = q;
    document.getElementById('editAnswer').value = a;
    document.getElementById('editActive').checked = active;
    document.getElementById('editSort').value = sort;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection
