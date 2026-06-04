@extends('layouts.admin')
@section('title', $deal ? 'Edit Deal' : 'New Deal')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $deal ? 'Edit Deal' : 'New Deal' }}</h1></div>
    <a href="{{ route('admin.newcrm.deals') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ $deal ? route('admin.newcrm.deals.update', $deal) : route('admin.newcrm.deals.store') }}">
    @csrf @if($deal) @method('PUT') @endif
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Deal Information</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Deal Title *</label>
                    <input type="text" name="title" value="{{ old('title', $deal?->title) }}" class="form-control" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Value</label>
                        <input type="number" name="value" value="{{ old('value', $deal?->value) }}" class="form-control" min="0" step="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-control">
                            @foreach(['INR','USD','EUR','GBP','AED'] as $c)
                            <option value="{{ $c }}" {{ old('currency', $deal?->currency ?? 'INR') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Stage *</label>
                        <select name="stage" class="form-control" required id="stageSelect" onchange="updateProbability(this.value)">
                            @foreach(\App\Models\CrmDeal::STAGES as $k => $info)
                            <option value="{{ $k }}" {{ old('stage', $deal?->stage ?? 'prospecting') == $k ? 'selected' : '' }}>{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Probability (%)</label>
                        <input type="number" name="probability" id="probInput" value="{{ old('probability', $deal?->probability ?? 10) }}" class="form-control" min="0" max="100">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Account</label>
                        <select name="account_id" class="form-control">
                            <option value="">— None —</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('account_id', $deal?->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact</label>
                        <select name="contact_id" class="form-control">
                            <option value="">— None —</option>
                            @foreach($contacts as $c)
                            <option value="{{ $c->id }}" {{ old('contact_id', $deal?->contact_id) == $c->id ? 'selected' : '' }}>{{ $c->first_name }} {{ $c->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Close Date</label>
                    <input type="date" name="expected_close" value="{{ old('expected_close', $deal?->expected_close?->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $deal?->notes) }}</textarea>
                </div>
                <div class="form-group" id="lostReasonGroup" style="{{ old('stage', $deal?->stage) === 'closed_lost' ? '' : 'display:none' }}">
                    <label class="form-label">Lost Reason</label>
                    <input type="text" name="lost_reason" value="{{ old('lost_reason', $deal?->lost_reason) }}" class="form-control" placeholder="Why was this deal lost?">
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3 class="card-title">Stage Guide</h3></div>
                <div class="card-body" style="padding:.75rem">
                    @foreach(\App\Models\CrmDeal::STAGES as $k => $info)
                    <div style="display:flex;align-items:center;gap:.5rem;padding:.4rem 0;border-bottom:1px solid var(--border-color)">
                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $info['color'] }};flex-shrink:0"></div>
                        <span style="font-size:.8rem;flex:1">{{ $info['label'] }}</span>
                        <span style="font-size:.75rem;color:var(--text-muted)">{{ $info['prob'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">
                <i class="fas fa-save"></i> {{ $deal ? 'Update Deal' : 'Create Deal' }}
            </button>
        </div>
    </div>
</form>

<script>
const stageProbabilities = @json(array_map(fn($s) => $s['prob'], \App\Models\CrmDeal::STAGES));
function updateProbability(stage) {
    const prob = stageProbabilities[stage] ?? 10;
    document.getElementById('probInput').value = prob;
    const lostGroup = document.getElementById('lostReasonGroup');
    lostGroup.style.display = stage === 'closed_lost' ? '' : 'none';
}
</script>
@endsection
