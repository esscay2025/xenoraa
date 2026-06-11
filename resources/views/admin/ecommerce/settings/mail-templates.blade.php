@extends('layouts.admin')
@section('title', 'E-commerce Mail Templates')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
    $ecomSettingsActive = true;
@endphp
@section('content')
<style>
.mt-page{padding:2rem}
.mt-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem}
.mt-header h1{font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0}
.mt-header p{color:var(--text-muted);margin:.25rem 0 0;font-size:.875rem}
.mt-actions{display:flex;gap:.75rem;flex-wrap:wrap}
.mt-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.55rem 1.1rem;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
.mt-btn.primary{background:var(--accent);color:#fff}
.mt-btn.primary:hover{opacity:.9}
.mt-btn.outline{background:transparent;border:1px solid var(--border);color:var(--text-primary)}
.mt-btn.outline:hover{background:var(--bg-secondary)}
.mt-btn.sm{padding:.35rem .75rem;font-size:.8rem}
.mt-btn.danger{background:transparent;border:1px solid rgba(239,68,68,.4);color:#ef4444}
.mt-btn.danger:hover{background:rgba(239,68,68,.08)}
.mt-filters{display:flex;align-items:center;gap:.75rem;margin-bottom:1.25rem;flex-wrap:wrap}
.mt-filter-btn{padding:.35rem .85rem;border-radius:20px;font-size:.8rem;font-weight:500;cursor:pointer;border:1px solid var(--border);background:transparent;color:var(--text-secondary);transition:all .2s}
.mt-filter-btn.active{background:var(--accent);color:#fff;border-color:var(--accent)}
.mt-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem}
.mt-card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:box-shadow .2s}
.mt-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.12)}
.mt-card-top{padding:1.25rem 1.25rem .75rem;position:relative}
.mt-card-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .65rem;border-radius:20px;font-size:.72rem;font-weight:600;margin-bottom:.75rem}
.mt-card-name{font-size:1rem;font-weight:700;color:var(--text-primary);margin:0 0 .25rem}
.mt-card-type{font-size:.78rem;color:var(--text-muted)}
.mt-card-subject{font-size:.82rem;color:var(--text-secondary);margin-top:.5rem;padding:.5rem .75rem;background:var(--bg-secondary);border-radius:6px;font-style:italic}
.mt-card-actions{padding:.75rem 1.25rem;border-top:1px solid var(--border);display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
.mt-card-dot{position:absolute;top:1rem;right:1rem;width:10px;height:10px;border-radius:50%}
.mt-card-dot.active{background:#10b981}
.mt-card-dot.inactive{background:#94a3b8}
.mt-empty{text-align:center;padding:4rem 2rem;color:var(--text-muted)}
.mt-empty i{font-size:3rem;margin-bottom:1rem;opacity:.3}
.mt-empty h3{font-size:1.1rem;font-weight:600;color:var(--text-primary);margin:0 0 .5rem}
.mt-alert{padding:.75rem 1rem;border-radius:8px;font-size:.875rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.6rem}
.mt-alert.success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#10b981}
.mt-type-colors{order_confirmation:'#6366f1',order_shipped:'#0ea5e9',order_delivered:'#10b981',order_cancelled:'#ef4444',payment_received:'#10b981',payment_failed:'#ef4444',refund_processed:'#f59e0b',cart_abandoned:'#f59e0b',review_request:'#6366f1',welcome:'#6366f1',general:'#64748b'}
</style>

<div class="mt-page">
  <div class="mt-header">
    <div>
      <h1><i class="fas fa-envelope-open-text" style="color:var(--accent);margin-right:.5rem"></i> Mail Templates</h1>
      <p>Create and manage email templates for your E-commerce transactional emails.</p>
    </div>
    <div class="mt-actions">
      @if($templates->isEmpty())
      <form method="POST" action="{{ route('admin.ecommerce.settings.mail-templates.load-defaults') }}" style="display:inline">
        @csrf
        <button type="submit" class="mt-btn outline">
          <i class="fas fa-magic"></i> Load Default Templates
        </button>
      </form>
      @endif
      <a href="{{ route('admin.ecommerce.settings.mail-templates.create') }}" class="mt-btn primary">
        <i class="fas fa-plus"></i> New Template
      </a>
    </div>
  </div>

  @if(session('success'))
  <div class="mt-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  @if(!$templates->isEmpty())
  {{-- Filter by type --}}
  <div class="mt-filters">
    <button class="mt-filter-btn active" onclick="filterTemplates('all', this)">All ({{ $templates->count() }})</button>
    @foreach($templates->groupBy('type') as $type => $group)
    <button class="mt-filter-btn" onclick="filterTemplates('{{ $type }}', this)">
      {{ $types[$type] ?? ucfirst($type) }} ({{ $group->count() }})
    </button>
    @endforeach
  </div>

  <div class="mt-grid" id="templatesGrid">
    @foreach($templates as $tpl)
    @php
      $typeColors = [
        'order_confirmation'=>'#6366f1','order_shipped'=>'#0ea5e9','order_delivered'=>'#10b981',
        'order_cancelled'=>'#ef4444','payment_received'=>'#10b981','payment_failed'=>'#ef4444',
        'refund_processed'=>'#f59e0b','cart_abandoned'=>'#f59e0b','review_request'=>'#6366f1',
        'welcome'=>'#6366f1','general'=>'#64748b',
      ];
      $color = $typeColors[$tpl->type] ?? '#64748b';
    @endphp
    <div class="mt-card" data-type="{{ $tpl->type }}">
      <div class="mt-card-top">
        <div class="mt-card-dot {{ $tpl->is_active ? 'active' : 'inactive' }}" title="{{ $tpl->is_active ? 'Active' : 'Inactive' }}"></div>
        <div class="mt-card-badge" style="background:{{ $color }}1a;color:{{ $color }}">
          <i class="fas fa-tag" style="font-size:.65rem"></i>
          {{ $types[$tpl->type] ?? ucfirst($tpl->type) }}
        </div>
        @if($tpl->is_default)
        <div class="mt-card-badge" style="background:rgba(245,158,11,.12);color:#f59e0b;margin-left:.4rem">
          <i class="fas fa-star" style="font-size:.65rem"></i> Default
        </div>
        @endif
        <h3 class="mt-card-name">{{ $tpl->name }}</h3>
        <div class="mt-card-subject">{{ Str::limit($tpl->subject, 60) }}</div>
      </div>
      <div class="mt-card-actions">
        <a href="{{ route('admin.ecommerce.settings.mail-templates.show', $tpl->id) }}" class="mt-btn outline sm">
          <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('admin.ecommerce.settings.mail-templates.edit', $tpl->id) }}" class="mt-btn outline sm">
          <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.ecommerce.settings.mail-templates.preview', $tpl->id) }}" target="_blank" class="mt-btn outline sm">
          <i class="fas fa-external-link-alt"></i> Preview
        </a>
        <form method="POST" action="{{ route('admin.ecommerce.settings.mail-templates.destroy', $tpl->id) }}" style="margin-left:auto" onsubmit="return confirm('Delete this template?')">
          @csrf @method('DELETE')
          <button type="submit" class="mt-btn danger sm"><i class="fas fa-trash"></i></button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="mt-empty">
    <i class="fas fa-envelope-open-text"></i>
    <h3>No mail templates yet</h3>
    <p style="margin:.5rem 0 1.5rem">Load the default e-commerce templates or create your own.</p>
    <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap">
      <form method="POST" action="{{ route('admin.ecommerce.settings.mail-templates.load-defaults') }}">
        @csrf
        <button type="submit" class="mt-btn primary"><i class="fas fa-magic"></i> Load Default Templates</button>
      </form>
      <a href="{{ route('admin.ecommerce.settings.mail-templates.create') }}" class="mt-btn outline">
        <i class="fas fa-plus"></i> Create Template
      </a>
    </div>
  </div>
  @endif
</div>

<script>
function filterTemplates(type, btn) {
    document.querySelectorAll('.mt-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.mt-card').forEach(card => {
        card.style.display = (type === 'all' || card.dataset.type === type) ? '' : 'none';
    });
}
</script>
@endsection
