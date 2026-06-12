@extends('layouts.admin')
@section('title', 'Chart of Accounts')
@push('styles')
<style>
.acc-page{padding:1.5rem 2rem;}
.acc-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.75rem;}
.acc-page-title{font-size:1.5rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:.6rem;}
.acc-page-title i{color:#6366f1;}
.acc-type-section{margin-bottom:1.5rem;}
.acc-type-header{display:flex;align-items:center;gap:.6rem;padding:.6rem 1rem;background:var(--bg-secondary,rgba(255,255,255,.03));border-radius:8px 8px 0 0;border:1px solid var(--border-color);border-bottom:none;font-size:.85rem;font-weight:700;color:var(--text-primary);}
.acc-type-header i{color:#6366f1;}
.acc-type-badge{margin-left:auto;font-size:.72rem;font-weight:600;padding:.2rem .6rem;border-radius:20px;background:rgba(99,102,241,.15);color:#6366f1;}
.acc-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:0 0 12px 12px;overflow:hidden;}
.acc-table{width:100%;border-collapse:collapse;font-size:.83rem;}
.acc-table th{padding:.6rem 1rem;text-align:left;color:var(--text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;border-bottom:1px solid var(--border-color);}
.acc-table td{padding:.65rem 1rem;border-bottom:1px solid var(--border-color);color:var(--text-primary);}
.acc-table tr:last-child td{border-bottom:none;}
.acc-table tr:hover td{background:rgba(99,102,241,.04);}
.acc-badge{display:inline-flex;align-items:center;padding:.2rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;}
.acc-badge.asset{background:rgba(59,130,246,.15);color:#3b82f6;}
.acc-badge.liability{background:rgba(239,68,68,.15);color:#ef4444;}
.acc-badge.equity{background:rgba(139,92,246,.15);color:#8b5cf6;}
.acc-badge.income{background:rgba(34,197,94,.15);color:#22c55e;}
.acc-badge.expense{background:rgba(245,158,11,.15);color:#f59e0b;}
.acc-badge.system{background:rgba(107,114,128,.15);color:#6b7280;}
/* Modal */
.acc-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;}
.acc-modal-overlay.open{display:flex;}
.acc-modal{background:var(--card-bg);border-radius:14px;padding:1.8rem;width:100%;max-width:480px;border:1px solid var(--border-color);}
.acc-modal-title{font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:1.2rem;}
.acc-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.8rem;}
.acc-form-group{display:flex;flex-direction:column;gap:.3rem;}
.acc-form-group.full{grid-column:1/-1;}
.acc-form-label{font-size:.78rem;font-weight:600;color:var(--text-muted);}
.acc-form-control{padding:.5rem .75rem;border-radius:7px;border:1px solid var(--border-color);background:var(--input-bg,var(--card-bg));color:var(--text-primary);font-size:.85rem;width:100%;}
.acc-form-control:focus{outline:none;border-color:#6366f1;}
.acc-modal-footer{display:flex;justify-content:flex-end;gap:.6rem;margin-top:1.2rem;}
.acc-btn-primary{padding:.5rem 1.2rem;border-radius:7px;background:#6366f1;color:#fff;border:none;font-size:.85rem;font-weight:600;cursor:pointer;}
.acc-btn-cancel{padding:.5rem 1.2rem;border-radius:7px;background:transparent;color:var(--text-muted);border:1px solid var(--border-color);font-size:.85rem;cursor:pointer;}
</style>
@endpush

@section('content')
<div class="acc-page">
    <div class="acc-page-header">
        <div class="acc-page-title"><i class="fas fa-sitemap"></i> Chart of Accounts</div>
        <button class="xn-btn" onclick="openCoaModal()" style="background:#6366f1;color:#fff;border:none;"><i class="fas fa-plus"></i> Add Account</button>
    </div>

    @foreach(['asset' => ['Asset Accounts','fa-coins','asset'], 'liability' => ['Liability Accounts','fa-hand-holding-usd','liability'], 'equity' => ['Equity Accounts','fa-balance-scale','equity'], 'income' => ['Income Accounts','fa-arrow-down','income'], 'expense' => ['Expense Accounts','fa-arrow-up','expense']] as $type => [$label, $icon, $badgeClass])
    @php $typeAccounts = $accounts->where('type', $type); @endphp
    <div class="acc-type-section">
        <div class="acc-type-header">
            <i class="fas {{ $icon }}"></i> {{ $label }}
            <span class="acc-type-badge">{{ $typeAccounts->count() }} accounts</span>
        </div>
        <div class="acc-card">
            <table class="acc-table">
                <thead><tr><th>Code</th><th>Name</th><th>Sub-Type</th><th>Opening Balance</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($typeAccounts as $acc)
                <tr>
                    <td style="font-size:.75rem;color:var(--text-muted);">{{ $acc->code ?? '—' }}</td>
                    <td style="font-weight:600;">{{ $acc->name }}
                        @if($acc->is_system)<span class="acc-badge system" style="margin-left:.4rem;font-size:.65rem;">System</span>@endif
                    </td>
                    <td>{{ $acc->sub_type ?? '—' }}</td>
                    <td>₹{{ number_format($acc->opening_balance, 2) }}</td>
                    <td>
                        @if($acc->is_active)
                            <span style="color:#22c55e;font-size:.75rem;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Active</span>
                        @else
                            <span style="color:#6b7280;font-size:.75rem;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if(!$acc->is_system)
                        <button onclick="editCoa({{ $acc->id }},'{{ addslashes($acc->name) }}','{{ $acc->type }}','{{ $acc->code }}','{{ $acc->sub_type }}','{{ $acc->opening_balance }}','{{ (int)$acc->is_active }}')" style="background:none;border:none;color:#6366f1;cursor:pointer;font-size:.8rem;"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admin.accounts.coa.delete', $acc->id) }}" onsubmit="return confirm('Delete?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:1rem;color:var(--text-muted);">No {{ strtolower($label) }} yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

{{-- Modal --}}
<div class="acc-modal-overlay" id="coaModal">
    <div class="acc-modal">
        <div class="acc-modal-title" id="coaModalTitle">Add Account</div>
        <form method="POST" id="coaModalForm" action="{{ route('admin.accounts.coa.store') }}">
            @csrf
            <input type="hidden" name="_method" id="coaMethod" value="POST">
            <input type="hidden" name="coa_id" id="coaId" value="">
            <div class="acc-form-grid">
                <div class="acc-form-group full">
                    <label class="acc-form-label">Account Name *</label>
                    <input type="text" name="name" id="coaName" class="acc-form-control" required>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Type *</label>
                    <select name="type" id="coaType" class="acc-form-control" required>
                        <option value="asset">Asset</option>
                        <option value="liability">Liability</option>
                        <option value="equity">Equity</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Code</label>
                    <input type="text" name="code" id="coaCode" class="acc-form-control" placeholder="e.g. 1001">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Sub-Type</label>
                    <input type="text" name="sub_type" id="coaSubType" class="acc-form-control" placeholder="e.g. bank, receivable">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Opening Balance</label>
                    <input type="number" name="opening_balance" id="coaOpenBal" class="acc-form-control" value="0" step="0.01">
                </div>
                <div class="acc-form-group">
                    <label class="acc-form-label">Status</label>
                    <select name="is_active" id="coaActive" class="acc-form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="acc-form-group full">
                    <label class="acc-form-label">Description</label>
                    <textarea name="description" id="coaDesc" class="acc-form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="acc-modal-footer">
                <button type="button" class="acc-btn-cancel" onclick="closeCoaModal()">Cancel</button>
                <button type="submit" class="acc-btn-primary">Save Account</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openCoaModal() {
    document.getElementById('coaModalTitle').textContent = 'Add Account';
    document.getElementById('coaMethod').value = 'POST';
    document.getElementById('coaModalForm').action = '{{ route("admin.accounts.coa.store") }}';
    document.getElementById('coaId').value = '';
    ['coaName','coaCode','coaSubType','coaDesc'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('coaType').value = 'asset';
    document.getElementById('coaOpenBal').value = '0';
    document.getElementById('coaActive').value = '1';
    document.getElementById('coaModal').classList.add('open');
}
function editCoa(id,name,type,code,subType,openBal,active) {
    document.getElementById('coaModalTitle').textContent = 'Edit Account';
    document.getElementById('coaMethod').value = 'PUT';
    document.getElementById('coaModalForm').action = '/admin/accounts/coa/' + id;
    document.getElementById('coaId').value = id;
    document.getElementById('coaName').value = name;
    document.getElementById('coaType').value = type;
    document.getElementById('coaCode').value = code;
    document.getElementById('coaSubType').value = subType;
    document.getElementById('coaOpenBal').value = openBal;
    document.getElementById('coaActive').value = active;
    document.getElementById('coaModal').classList.add('open');
}
function closeCoaModal() { document.getElementById('coaModal').classList.remove('open'); }
document.getElementById('coaModal').addEventListener('click', function(e) { if(e.target===this) closeCoaModal(); });
</script>
@endpush
