@extends('layouts.admin')
@section('title', 'View Mail Template')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
    $ecomSettingsActive = true;
@endphp
@section('content')
<style>
.vtpl-page{padding:2rem;max-width:1000px;margin:0 auto}
.vtpl-header{display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;flex-wrap:wrap}
.vtpl-header h1{font-size:1.4rem;font-weight:700;color:var(--text-primary);margin:0}
.vtpl-header a.back{color:var(--text-muted);text-decoration:none;font-size:.875rem;display:flex;align-items:center;gap:.4rem}
.vtpl-header a.back:hover{color:var(--accent)}
.vtpl-actions{margin-left:auto;display:flex;gap:.75rem;flex-wrap:wrap}
.vtpl-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.55rem 1.1rem;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
.vtpl-btn.primary{background:var(--accent);color:#fff}
.vtpl-btn.outline{background:transparent;border:1px solid var(--border);color:var(--text-primary)}
.vtpl-btn.outline:hover{background:var(--bg-secondary)}
.vtpl-btn.danger{background:transparent;border:1px solid rgba(239,68,68,.4);color:#ef4444}
.vtpl-btn.danger:hover{background:rgba(239,68,68,.08)}
.vtpl-layout{display:grid;grid-template-columns:260px 1fr;gap:1.5rem;align-items:start}
.vtpl-meta{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.vtpl-meta-header{padding:1rem 1.25rem;border-bottom:1px solid var(--border)}
.vtpl-meta-header h3{margin:0;font-size:.9rem;font-weight:600;color:var(--text-primary)}
.vtpl-meta-body{padding:1.25rem}
.vtpl-meta-row{display:flex;flex-direction:column;gap:.2rem;margin-bottom:1rem}
.vtpl-meta-row:last-child{margin-bottom:0}
.vtpl-meta-label{font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em}
.vtpl-meta-value{font-size:.875rem;color:var(--text-primary)}
.vtpl-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .65rem;border-radius:20px;font-size:.72rem;font-weight:600}
.vtpl-badge.active{background:rgba(16,185,129,.12);color:#10b981}
.vtpl-badge.inactive{background:rgba(100,116,139,.12);color:#64748b}
.vtpl-badge.default{background:rgba(245,158,11,.12);color:#f59e0b}
.vtpl-color-swatch{display:inline-block;width:16px;height:16px;border-radius:4px;border:1px solid var(--border);vertical-align:middle;margin-right:.4rem}
.vtpl-preview-card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.vtpl-preview-header{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.vtpl-preview-header h3{margin:0;font-size:.9rem;font-weight:600;color:var(--text-primary)}
.vtpl-preview-frame{width:100%;height:600px;border:none;background:#fff}
.vtpl-subject-bar{padding:.75rem 1.25rem;background:var(--bg-secondary);border-bottom:1px solid var(--border);font-size:.875rem;color:var(--text-secondary)}
.vtpl-subject-bar strong{color:var(--text-primary)}
@media(max-width:768px){.vtpl-layout{grid-template-columns:1fr}}
</style>

<div class="vtpl-page">
  <div class="vtpl-header">
    <a href="{{ route('admin.ecommerce.settings.mail-templates') }}" class="back">
      <i class="fas fa-arrow-left"></i> Back to Templates
    </a>
    <h1>{{ $template->name }}</h1>
    <div class="vtpl-actions">
      <a href="{{ route('admin.ecommerce.settings.mail-templates.preview', $template->id) }}" target="_blank" class="vtpl-btn outline">
        <i class="fas fa-external-link-alt"></i> Full Preview
      </a>
      <a href="{{ route('admin.ecommerce.settings.mail-templates.edit', $template->id) }}" class="vtpl-btn primary">
        <i class="fas fa-edit"></i> Edit
      </a>
      <form method="POST" action="{{ route('admin.ecommerce.settings.mail-templates.destroy', $template->id) }}" onsubmit="return confirm('Delete this template?')">
        @csrf @method('DELETE')
        <button type="submit" class="vtpl-btn danger"><i class="fas fa-trash"></i></button>
      </form>
    </div>
  </div>

  <div class="vtpl-layout">
    {{-- Meta sidebar --}}
    <div>
      <div class="vtpl-meta">
        <div class="vtpl-meta-header"><h3>Template Details</h3></div>
        <div class="vtpl-meta-body">
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Type</span>
            <span class="vtpl-meta-value">{{ \App\Models\EcomMailTemplate::$types[$template->type] ?? ucfirst($template->type) }}</span>
          </div>
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Status</span>
            <span class="vtpl-badge {{ $template->is_active ? 'active' : 'inactive' }}">
              <i class="fas fa-circle" style="font-size:.45rem"></i>
              {{ $template->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
          @if($template->is_default)
          <div class="vtpl-meta-row">
            <span class="vtpl-badge default"><i class="fas fa-star" style="font-size:.65rem"></i> Default Template</span>
          </div>
          @endif
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Primary Colour</span>
            <span class="vtpl-meta-value">
              <span class="vtpl-color-swatch" style="background:{{ $template->primary_color }}"></span>
              {{ $template->primary_color }}
            </span>
          </div>
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Secondary Colour</span>
            <span class="vtpl-meta-value">
              <span class="vtpl-color-swatch" style="background:{{ $template->secondary_color }}"></span>
              {{ $template->secondary_color }}
            </span>
          </div>
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Font</span>
            <span class="vtpl-meta-value" style="font-size:.8rem">{{ $template->font_family }}</span>
          </div>
          @if($template->logo_path)
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Logo</span>
            <img src="{{ Storage::url($template->logo_path) }}" alt="Logo" style="max-height:40px;border-radius:4px;margin-top:.25rem">
          </div>
          @endif
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Created</span>
            <span class="vtpl-meta-value" style="font-size:.8rem">{{ $template->created_at->format('d M Y') }}</span>
          </div>
          <div class="vtpl-meta-row">
            <span class="vtpl-meta-label">Last Updated</span>
            <span class="vtpl-meta-value" style="font-size:.8rem">{{ $template->updated_at->diffForHumans() }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Preview --}}
    <div>
      <div class="vtpl-preview-card">
        <div class="vtpl-preview-header">
          <h3><i class="fas fa-eye" style="color:var(--accent);margin-right:.5rem"></i> Email Preview</h3>
          <a href="{{ route('admin.ecommerce.settings.mail-templates.preview', $template->id) }}" target="_blank" style="font-size:.8rem;color:var(--accent);text-decoration:none">
            <i class="fas fa-expand"></i> Open full page
          </a>
        </div>
        <div class="vtpl-subject-bar">
          <strong>Subject:</strong> {{ $template->subject }}
        </div>
        <iframe class="vtpl-preview-frame" srcdoc="{{ htmlspecialchars($template->body_html) }}"></iframe>
      </div>
    </div>
  </div>
</div>
@endsection
