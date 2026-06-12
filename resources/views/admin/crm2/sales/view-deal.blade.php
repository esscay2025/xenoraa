@extends('layouts.admin')
@section('title', $deal->name ?? $deal->title ?? 'Deal')
@section('page-title', 'Deal Detail')
@push('styles')
<style>
/* 3-dot action menu */
.xn-bulk-wrap { position: relative; display: inline-block; }
.xn-bulk-btn { width: 34px; height: 34px; border-radius: 7px; border: 1px solid var(--border,#e2e8f0); background: var(--bg-card,#fff); color: var(--text-secondary,#64748b); font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
.xn-bulk-btn:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-drop { display: none; position: absolute; right: 0; top: calc(100% + 4px); min-width: 200px; background: var(--bg-card,#fff); border: 1px solid var(--border,#e2e8f0); border-radius: 9px; box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 999; padding: 5px 0; }
.xn-bulk-drop.open { display: block; }
.xn-bulk-item { display: flex; align-items: center; gap: .6rem; padding: .55rem 1rem; font-size: .84rem; color: var(--text-primary,#1a1a2e); cursor: pointer; transition: background .12s; border: none; background: none; width: 100%; text-align: left; text-decoration: none; }
.xn-bulk-item:hover { background: var(--bg-hover,#f1f5f9); }
.xn-bulk-item i { width: 16px; text-align: center; }
.xn-bulk-item.danger { color: #ef4444; }
</style>
@endpush
@section('content')
<style>
.cv-wrap{max-width:1100px;margin:0 auto;padding:1.5rem}
.cv-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
.cv-identity{display:flex;align-items:center;gap:1rem}
.cv-avatar{width:56px;height:56px;border-radius:10px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;flex-shrink:0}
.cv-name{font-size:1.4rem;font-weight:700;color:var(--text-primary)}
.cv-sub{font-size:.85rem;color:var(--text-muted);margin-top:.2rem}
.cv-badges{display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.4rem}
.cv-badge{padding:.2rem .6rem;border-radius:20px;font-size:.72rem;font-weight:600;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-secondary)}
.cv-actions{display:flex;gap:.5rem;flex-wrap:wrap}
.cv-btn{padding:.5rem 1rem;border-radius:7px;font-size:.82rem;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:.4rem;text-decoration:none;transition:all .2s}
.cv-btn-primary{background:var(--accent);color:#fff}
.cv-btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text-secondary)}
.cv-btn-ghost:hover{background:var(--bg-primary)}
.cv-layout{display:grid;grid-template-columns:1fr 300px;gap:1.2rem}
@media(max-width:768px){.cv-layout{grid-template-columns:1fr}}
.cv-card{background:var(--bg-card);border:1px solid var(--border);border-radius:10px;margin-bottom:1.2rem;overflow:hidden}
.cv-card-header{padding:.75rem 1.2rem;background:var(--bg-primary);border-bottom:1px solid var(--border);font-size:.88rem;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:.5rem}
.cv-card-header i{color:var(--accent)}
.cv-card-body{padding:1.2rem}
.cv-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.8rem}
.cv-field{display:flex;flex-direction:column;gap:.2rem}
.cv-field label{font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em}
.cv-field .val{font-size:.85rem;color:var(--text-primary)}
.cv-field .val.empty{color:var(--text-muted);font-style:italic}
.cv-stage-bar{display:flex;gap:.3rem;margin-bottom:1rem;flex-wrap:wrap}
.cv-stage-step{flex:1;min-width:80px;padding:.4rem .5rem;border-radius:6px;text-align:center;font-size:.72rem;font-weight:600;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-muted)}
.cv-stage-step.active{background:var(--accent);color:#fff;border-color:var(--accent)}
.cv-stage-step.done{background:var(--accent)20;color:var(--accent);border-color:var(--accent)40}
</style>
<div class="cv-wrap">
  <div class="cv-header">
    <div class="cv-identity">
      <div class="cv-avatar"><i class="fas fa-handshake"></i></div>
      <div>
        <div class="cv-name">{{ $deal->name ?? $deal->title ?? 'Deal #'.$deal->id }}</div>
        <div class="cv-sub">
          {{ $deal->account?->name ?? '' }}{{ $deal->account && $deal->contact ? ' · ' : '' }}{{ $deal->contact ? $deal->contact->first_name.' '.$deal->contact->last_name : '' }}
        </div>
        <div class="cv-badges">
          @if($deal->stage)<span class="cv-badge" style="background:var(--accent)20;color:var(--accent);border-color:var(--accent)40">{{ ucfirst(str_replace('_',' ',$deal->stage)) }}</span>@endif
          @if($deal->qualification)<span class="cv-badge">{{ $deal->qualification }}</span>@endif
        </div>
      </div>
    </div>
    <div class="cv-actions">
      <a href="{{ route('admin.crm2.sales.deals.edit', $deal->id) }}" class="cv-btn cv-btn-primary"><i class="fas fa-edit"></i> Edit</a>
      <a href="{{ route('admin.crm2.sales.deals') }}" class="cv-btn cv-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
      {{-- 3-dot action menu --}}
      <div class="xn-bulk-wrap">
        <button class="xn-bulk-btn" id="lvActBtn" onclick="toggleActMenu(event)" title="More actions">&#8942;</button>
        <div class="xn-bulk-drop" id="lvActDrop">
          <form method="POST" action="{{ route('admin.crm2.sales.deals.clone', $deal->id) }}" style="margin:0">
            @csrf
            <button type="submit" class="xn-bulk-item"><i class="fas fa-copy" style="color:#6366f1"></i> Clone Deal</button>
          </form>
          <button class="xn-bulk-item" onclick="window.print()"><i class="fas fa-print" style="color:#10b981"></i> Print Preview</button>
          <div style="border-top:1px solid var(--border,#e2e8f0);margin:4px 0"></div>
          <form method="POST" action="{{ route('admin.crm2.sales.destroy', ['type'=>'deal','id'=>$deal->id]) }}" onsubmit="return confirm('Delete this deal permanently?')" style="margin:0">
            @csrf @method('DELETE')
            <button type="submit" class="xn-bulk-item danger"><i class="fas fa-trash"></i> Delete Deal</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Stage Progress --}}
  @php $stages = ['prospecting','qualification','proposal','negotiation','closed_won','closed_lost']; $currentStage = $deal->stage ?? ''; @endphp
  <div class="cv-stage-bar">
    @foreach($stages as $st)
    <div class="cv-stage-step {{ $currentStage==$st ? 'active' : (array_search($currentStage,$stages)>array_search($st,$stages)?'done':'') }}">{{ ucfirst(str_replace('_',' ',$st)) }}</div>
    @endforeach
  </div>

  <div class="cv-layout">
    <div>
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-handshake"></i> Deal Information</div>
        <div class="cv-card-body">
          <div class="cv-grid">
            <div class="cv-field"><label>Deal Owner</label><div class="val">{{ $deal->owner?->name ?: '—' }}</div></div>
            <div class="cv-field"><label>Deal Name</label><div class="val">{{ $deal->name ?: '—' }}</div></div>
            <div class="cv-field"><label>Account</label><div class="val">@if($deal->account)<a href="{{ route('admin.crm2.sales.accounts.show', $deal->account_id) }}" style="color:var(--accent)">{{ $deal->account->name }}</a>@else<span style="color:var(--text-muted);font-style:italic;">—</span>@endif</div></div>
            <div class="cv-field"><label>Contact</label><div class="val">@if($deal->contact)<a href="{{ route('admin.crm2.sales.contacts.show', $deal->contact_id) }}" style="color:var(--accent)">{{ $deal->contact->first_name }} {{ $deal->contact->last_name }}</a>@else<span style="color:var(--text-muted);font-style:italic;">—</span>@endif</div></div>
            <div class="cv-field"><label>Type</label><div class="val">{{ $deal->type ?: '—' }}</div></div>
            <div class="cv-field"><label>Lead Source</label><div class="val">{{ $deal->lead_source ?: '—' }}</div></div>
            <div class="cv-field"><label>Amount</label><div class="val">{{ $deal->amount ? '$'.number_format($deal->amount,2) : ($deal->value ? '$'.number_format($deal->value,2) : '<span style="color:var(--text-muted);font-style:italic;">—</span>') }}</div></div>
            <div class="cv-field"><label>Closing Date</label><div class="val">{{ $deal->closing_date ? \Carbon\Carbon::parse($deal->closing_date)->format('d M Y') : ($deal->expected_close ? \Carbon\Carbon::parse($deal->expected_close)->format('d M Y') : '<span style="color:var(--text-muted);font-style:italic;">—</span>') }}</div></div>
            <div class="cv-field"><label>Probability</label><div class="val">{{ $deal->probability ? $deal->probability.'%' : '<span style="color:var(--text-muted);font-style:italic;">—</span>' }}</div></div>
            <div class="cv-field"><label>Expected Revenue</label><div class="val">{{ $deal->expected_revenue ? '$'.number_format($deal->expected_revenue,2) : '<span style="color:var(--text-muted);font-style:italic;">—</span>' }}</div></div>
            <div class="cv-field"><label>Campaign Source</label><div class="val">{{ $deal->campaign_source ?: '—' }}</div></div>
            <div class="cv-field"><label>Next Step</label><div class="val">{{ $deal->next_step ?: '—' }}</div></div>
          </div>
          @if($deal->description)
          <hr style="border:none;border-top:1px solid var(--border);margin:1rem 0">
          <div class="cv-field"><label>Description</label><div class="val">{{ $deal->description }}</div></div>
          @endif
          @if($deal->notes)
          <div class="cv-field" style="margin-top:.6rem"><label>Notes</label><div class="val">{{ $deal->notes }}</div></div>
          @endif
        </div>
      </div>
    </div>

    <div>
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-info-circle"></i> Quick Info</div>
        <div class="cv-card-body">
          <div class="cv-field" style="margin-bottom:.6rem"><label>Created</label><div class="val">{{ $deal->created_at->format('d M Y') }}</div></div>
          <div class="cv-field"><label>Last Updated</label><div class="val">{{ $deal->updated_at->format('d M Y') }}</div></div>
        </div>
      </div>
      {{-- Activities --}}
      <div class="cv-card">
        <div class="cv-card-header"><i class="fas fa-tasks"></i> Activities</div>
        <div class="cv-card-body">
          <form method="POST" action="{{ route('admin.newcrm.activities.store') }}" style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:.8rem">
            @csrf
            <input type="hidden" name="related_type" value="deal">
            <input type="hidden" name="related_id" value="{{ $deal->id }}">
            <select name="type" required style="background:var(--bg-primary);border:1px solid var(--border);border-radius:6px;padding:.4rem .6rem;font-size:.82rem;color:var(--text-primary)">
              <option value="">Type...</option>
              <option value="task">Task</option>
              <option value="meeting">Meeting</option>
              <option value="call">Call</option>
            </select>
            <input type="text" name="subject" placeholder="Subject" required style="background:var(--bg-primary);border:1px solid var(--border);border-radius:6px;padding:.4rem .6rem;font-size:.82rem;color:var(--text-primary)">
            <input type="datetime-local" name="due_at" style="background:var(--bg-primary);border:1px solid var(--border);border-radius:6px;padding:.4rem .6rem;font-size:.82rem;color:var(--text-primary)">
            <button type="submit" class="cv-btn cv-btn-primary" style="justify-content:center"><i class="fas fa-plus"></i> Log Activity</button>
          </form>
          @forelse($activities as $act)
          <div style="display:flex;gap:.5rem;align-items:flex-start;padding:.4rem;background:var(--bg-primary);border-radius:6px;border:1px solid var(--border);margin-bottom:.4rem">
            <div style="width:24px;height:24px;border-radius:50%;background:var(--accent)20;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--accent);flex-shrink:0">
              <i class="fas fa-{{ $act->type=='task'?'check':($act->type=='meeting'?'calendar-alt':'phone') }}"></i>
            </div>
            <div>
              <div style="font-size:.8rem;font-weight:600;color:var(--text-primary)">{{ $act->subject }}</div>
              <div style="font-size:.72rem;color:var(--text-muted)">{{ $act->due_at ? \Carbon\Carbon::parse($act->due_at)->format('d M Y') : '' }}</div>
            </div>
          </div>
          @empty
          <p style="font-size:.82rem;color:var(--text-muted);text-align:center">No activities</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function toggleActMenu(e) {
  e.stopPropagation();
  document.getElementById('lvActDrop').classList.toggle('open');
}
document.addEventListener('click', function() {
  const d = document.getElementById('lvActDrop');
  if (d) d.classList.remove('open');
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const d = document.getElementById('lvActDrop');
    if (d) d.classList.remove('open');
  }
});
</script>
@endsection
