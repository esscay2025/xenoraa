@extends('layouts.admin')
@section('title', 'Mail Templates — CRM Settings')
@section('content')
<link rel="stylesheet" href="{{ asset('css/crm2.css') }}?v={{ filemtime(public_path('css/crm2.css')) }}">

<div class="crm2-page">
    {{-- Page Header --}}
    <div class="crm2-page-header">
        <div>
            <h1 class="crm2-page-title"><i class="fas fa-envelope" style="color:var(--accent);margin-right:0.5rem;"></i>Mail Templates</h1>
            <p class="crm2-page-subtitle">Create and manage professional email templates for all CRM flows</p>
        </div>
        <div style="display:flex;gap:0.75rem;">
            @if($templates->total() == 0)
            <a href="{{ route('admin.crm2.settings.mail-templates.seed') }}" class="crm2-btn crm2-btn-secondary">
                <i class="fas fa-magic"></i> Load Default Templates
            </a>
            @endif
            <a href="{{ route('admin.crm2.settings.mail-templates.create') }}" class="crm2-btn crm2-btn-primary">
                <i class="fas fa-plus"></i> New Template
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="crm2-alert crm2-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('info'))
    <div class="crm2-alert" style="background:#eff6ff;color:#1e40af;border-left:4px solid #3b82f6;"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
    @endif

    {{-- Type Filter --}}
    <div class="crm2-card" style="margin-bottom:1.25rem;">
        <div class="crm2-card-body" style="padding:1rem 1.25rem;">
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                <span style="font-size:0.8rem;color:var(--text-muted);font-weight:600;margin-right:0.25rem;">Filter:</span>
                <a href="{{ route('admin.crm2.settings.mail-templates') }}"
                   style="padding:0.35rem 0.9rem;border-radius:20px;font-size:0.8rem;font-weight:500;text-decoration:none;transition:all 0.2s;
                          background:{{ !$type ? 'var(--accent)' : 'var(--bg-secondary)' }};
                          color:{{ !$type ? '#fff' : 'var(--text-primary)' }};">All</a>
                @foreach($types as $key => $label)
                <a href="{{ route('admin.crm2.settings.mail-templates', ['type' => $key]) }}"
                   style="padding:0.35rem 0.9rem;border-radius:20px;font-size:0.8rem;font-weight:500;text-decoration:none;transition:all 0.2s;
                          background:{{ $type === $key ? 'var(--accent)' : 'var(--bg-secondary)' }};
                          color:{{ $type === $key ? '#fff' : 'var(--text-primary)' }};">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </div>

    @if($templates->isEmpty())
    <div class="crm2-card">
        <div class="crm2-card-body" style="text-align:center;padding:4rem 2rem;">
            <i class="fas fa-envelope-open" style="font-size:3rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <h3 style="margin:0 0 0.5rem;color:var(--text-primary);">No Templates Yet</h3>
            <p style="margin:0 0 1.5rem;color:var(--text-muted);">Click "Load Default Templates" to get started with 6 professionally designed templates, or create your own.</p>
            <div style="display:flex;gap:0.75rem;justify-content:center;">
                <a href="{{ route('admin.crm2.settings.mail-templates.seed') }}" class="crm2-btn crm2-btn-primary">
                    <i class="fas fa-magic"></i> Load Default Templates
                </a>
                <a href="{{ route('admin.crm2.settings.mail-templates.create') }}" class="crm2-btn crm2-btn-secondary">
                    <i class="fas fa-plus"></i> Create Manually
                </a>
            </div>
        </div>
    </div>
    @else

    {{-- Templates Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
        @foreach($templates as $tpl)
        @php
        $typeColors = [
            'invoice'=>['bg'=>'#eff6ff','text'=>'#1d4ed8','icon'=>'fa-file-invoice'],
            'quote'=>['bg'=>'#f0fdf4','text'=>'#15803d','icon'=>'fa-file-contract'],
            'sales_order'=>['bg'=>'#fff7ed','text'=>'#c2410c','icon'=>'fa-shopping-cart'],
            'purchase_order'=>['bg'=>'#fdf4ff','text'=>'#7e22ce','icon'=>'fa-shopping-bag'],
            'general'=>['bg'=>'#f8fafc','text'=>'#475569','icon'=>'fa-envelope'],
            'all_in_one'=>['bg'=>'#fef3c7','text'=>'#92400e','icon'=>'fa-layer-group'],
        ];
        $tc = $typeColors[$tpl->type] ?? $typeColors['general'];
        @endphp
        <div class="crm2-card" style="position:relative;transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            {{-- Color bar --}}
            <div style="height:4px;background:{{ $tpl->primary_color }};border-radius:12px 12px 0 0;"></div>

            <div class="crm2-card-body" style="padding:1.25rem;">
                {{-- Header row --}}
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:40px;height:40px;border-radius:10px;background:{{ $tc['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas {{ $tc['icon'] }}" style="color:{{ $tc['text'] }};font-size:1rem;"></i>
                        </div>
                        <div>
                            <h4 style="margin:0;font-size:0.95rem;font-weight:600;color:var(--text-primary);">{{ $tpl->name }}</h4>
                            <span style="font-size:0.75rem;padding:0.15rem 0.5rem;border-radius:10px;background:{{ $tc['bg'] }};color:{{ $tc['text'] }};font-weight:500;">
                                {{ $types[$tpl->type] ?? $tpl->type }}
                            </span>
                        </div>
                    </div>
                    <div style="display:flex;gap:0.25rem;">
                        @if($tpl->is_default)
                        <span title="Default template for this type" style="font-size:0.7rem;padding:0.2rem 0.5rem;border-radius:10px;background:#fef3c7;color:#92400e;font-weight:600;">DEFAULT</span>
                        @endif
                        @if(!$tpl->is_active)
                        <span style="font-size:0.7rem;padding:0.2rem 0.5rem;border-radius:10px;background:#fee2e2;color:#991b1b;font-weight:600;">INACTIVE</span>
                        @endif
                    </div>
                </div>

                {{-- Subject --}}
                @if($tpl->subject)
                <p style="margin:0 0 0.75rem;font-size:0.8rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <i class="fas fa-tag" style="margin-right:0.3rem;"></i>{{ $tpl->subject }}
                </p>
                @endif

                {{-- Color swatch --}}
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;">
                    <div style="width:20px;height:20px;border-radius:4px;background:{{ $tpl->primary_color }};border:1px solid var(--border);" title="Primary: {{ $tpl->primary_color }}"></div>
                    <div style="width:20px;height:20px;border-radius:4px;background:{{ $tpl->secondary_color }};border:1px solid var(--border);" title="Secondary: {{ $tpl->secondary_color }}"></div>
                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $tpl->primary_color }}</span>
                    @if($tpl->show_logo)
                    <span style="margin-left:auto;font-size:0.75rem;color:var(--text-muted);"><i class="fas fa-image"></i> Logo</span>
                    @endif
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:0.5rem;border-top:1px solid var(--border);padding-top:1rem;">
                    <a href="{{ route('admin.crm2.settings.mail-templates.show', $tpl->id) }}" class="crm2-btn crm2-btn-secondary" style="flex:1;text-align:center;font-size:0.8rem;padding:0.4rem 0.5rem;">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('admin.crm2.settings.mail-templates.edit', $tpl->id) }}" class="crm2-btn crm2-btn-secondary" style="flex:1;text-align:center;font-size:0.8rem;padding:0.4rem 0.5rem;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.crm2.settings.mail-templates.preview', $tpl->id) }}" target="_blank" class="crm2-btn crm2-btn-secondary" style="font-size:0.8rem;padding:0.4rem 0.6rem;" title="Preview in browser">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.crm2.settings.mail-templates.destroy', $tpl->id) }}" onsubmit="return confirm('Delete this template?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="crm2-btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;font-size:0.8rem;padding:0.4rem 0.6rem;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($templates->hasPages())
    <div style="margin-top:1.5rem;">{{ $templates->links() }}</div>
    @endif
    @endif
</div>
@endsection
