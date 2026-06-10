@extends('layouts.admin')
@section('title', $template->name . ' — Mail Template')
@section('content')
<link rel="stylesheet" href="{{ asset('css/crm2.css') }}?v={{ filemtime(public_path('css/crm2.css')) }}">

<div class="crm2-page">
    <div class="crm2-page-header">
        <div>
            <a href="{{ route('admin.crm2.settings.mail-templates') }}" style="font-size:0.8rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;margin-bottom:0.5rem;">
                <i class="fas fa-arrow-left"></i> Back to Templates
            </a>
            <h1 class="crm2-page-title">
                <i class="fas fa-envelope" style="color:var(--accent);margin-right:0.5rem;"></i>{{ $template->name }}
            </h1>
            <p class="crm2-page-subtitle">{{ $types[$template->type] ?? $template->type }} Template</p>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('admin.crm2.settings.mail-templates.preview', $template->id) }}" target="_blank" class="crm2-btn crm2-btn-secondary">
                <i class="fas fa-external-link-alt"></i> Full Preview
            </a>
            <a href="{{ route('admin.crm2.settings.mail-templates.edit', $template->id) }}" class="crm2-btn crm2-btn-primary">
                <i class="fas fa-edit"></i> Edit Template
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

        {{-- Preview Panel --}}
        <div class="crm2-card">
            <div class="crm2-card-header">
                <h3 class="crm2-card-title"><i class="fas fa-eye"></i> Email Preview</h3>
                <span style="font-size:0.75rem;color:var(--text-muted);">Sample data used for preview</span>
            </div>
            <div class="crm2-card-body" style="padding:0;">
                <div style="background:#f1f5f9;padding:1.5rem;border-radius:0 0 12px 12px;">
                    {{-- Email wrapper --}}
                    <div style="max-width:680px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
                        {{-- Logo header --}}
                        @if($template->show_logo && $template->logo_path)
                        <div style="background:{{ $template->primary_color }};padding:20px 40px;text-align:center;">
                            <img src="{{ Storage::url($template->logo_path) }}" alt="{{ $template->header_text ?? 'Logo' }}" style="max-height:60px;max-width:200px;">
                        </div>
                        @elseif($template->header_text)
                        <div style="background:{{ $template->primary_color }};padding:20px 40px;">
                            <h2 style="margin:0;color:#fff;font-family:{{ $template->font_family }};font-size:1.2rem;">{{ $template->header_text }}</h2>
                        </div>
                        @endif

                        {{-- Body --}}
                        @if($template->body_html)
                        <div style="font-family:{{ $template->font_family }};">
                            {!! $template->body_html !!}
                        </div>
                        @else
                        <div style="padding:2rem;text-align:center;color:#94a3b8;">
                            <i class="fas fa-file-alt" style="font-size:2rem;margin-bottom:0.5rem;display:block;"></i>
                            No body HTML defined yet.
                        </div>
                        @endif

                        {{-- Footer --}}
                        @if($template->show_footer && $template->footer_text)
                        <div style="background:{{ $template->secondary_color }};padding:20px 40px;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:0.8rem;color:#64748b;font-family:{{ $template->font_family }};text-align:center;">{{ $template->footer_text }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Details Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:80px;">
            <div class="crm2-card">
                <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-info-circle"></i> Details</h3></div>
                <div class="crm2-card-body" style="padding:1.25rem;">
                    <div style="display:flex;flex-direction:column;gap:0.85rem;">
                        <div>
                            <p style="margin:0 0 2px;font-size:0.7rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Type</p>
                            <p style="margin:0;font-size:0.875rem;font-weight:600;">{{ $types[$template->type] ?? $template->type }}</p>
                        </div>
                        <div>
                            <p style="margin:0 0 2px;font-size:0.7rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Default Subject</p>
                            <p style="margin:0;font-size:0.8rem;color:var(--text-primary);">{{ $template->subject ?? '—' }}</p>
                        </div>
                        <div>
                            <p style="margin:0 0 2px;font-size:0.7rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Font</p>
                            <p style="margin:0;font-size:0.8rem;">{{ explode(',', $template->font_family)[0] }}</p>
                        </div>
                        <div>
                            <p style="margin:0 0 6px;font-size:0.7rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Colours</p>
                            <div style="display:flex;gap:0.5rem;align-items:center;">
                                <div style="width:28px;height:28px;border-radius:6px;background:{{ $template->primary_color }};border:1px solid var(--border);" title="{{ $template->primary_color }}"></div>
                                <div style="width:28px;height:28px;border-radius:6px;background:{{ $template->secondary_color }};border:1px solid var(--border);" title="{{ $template->secondary_color }}"></div>
                                <span style="font-size:0.75rem;color:var(--text-muted);">{{ $template->primary_color }}</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                            <span style="font-size:0.75rem;padding:0.2rem 0.6rem;border-radius:10px;background:{{ $template->is_active ? '#dcfce7' : '#fee2e2' }};color:{{ $template->is_active ? '#166534' : '#991b1b' }};">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($template->is_default)
                            <span style="font-size:0.75rem;padding:0.2rem 0.6rem;border-radius:10px;background:#fef3c7;color:#92400e;">Default</span>
                            @endif
                            @if($template->show_logo)
                            <span style="font-size:0.75rem;padding:0.2rem 0.6rem;border-radius:10px;background:var(--bg-secondary);color:var(--text-muted);">Logo On</span>
                            @endif
                        </div>
                        <div>
                            <p style="margin:0 0 2px;font-size:0.7rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Created</p>
                            <p style="margin:0;font-size:0.8rem;">{{ $template->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p style="margin:0 0 2px;font-size:0.7rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Last Updated</p>
                            <p style="margin:0;font-size:0.8rem;">{{ $template->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="crm2-card">
                <div class="crm2-card-body" style="padding:1.25rem;display:flex;flex-direction:column;gap:0.75rem;">
                    <a href="{{ route('admin.crm2.settings.mail-templates.edit', $template->id) }}" class="crm2-btn crm2-btn-primary" style="width:100%;text-align:center;">
                        <i class="fas fa-edit"></i> Edit Template
                    </a>
                    <a href="{{ route('admin.crm2.settings.mail-templates') }}" class="crm2-btn crm2-btn-secondary" style="width:100%;text-align:center;">
                        <i class="fas fa-list"></i> All Templates
                    </a>
                    <form method="POST" action="{{ route('admin.crm2.settings.mail-templates.destroy', $template->id) }}" onsubmit="return confirm('Delete this template permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="crm2-btn" style="width:100%;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;">
                            <i class="fas fa-trash"></i> Delete Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
