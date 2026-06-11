<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Preview: {{ $template->name }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#e2e8f0;font-family:Inter,Arial,sans-serif;min-height:100vh}
.preview-bar{background:#1e293b;color:#e2e8f0;padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,.3)}
.preview-bar h2{font-size:.9rem;font-weight:600;color:#fff}
.preview-bar p{font-size:.75rem;color:#94a3b8;margin-top:.1rem}
.preview-bar-actions{display:flex;gap:.75rem;align-items:center}
.preview-bar-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.4rem .9rem;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
.preview-bar-btn.primary{background:#6366f1;color:#fff}
.preview-bar-btn.outline{background:transparent;border:1px solid #475569;color:#e2e8f0}
.preview-bar-btn.outline:hover{background:#334155}
.preview-meta{background:#1e293b;border-bottom:1px solid #334155;padding:.6rem 1.5rem;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap}
.preview-meta-item{font-size:.78rem;color:#94a3b8}
.preview-meta-item strong{color:#e2e8f0}
.preview-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .55rem;border-radius:20px;font-size:.7rem;font-weight:600}
.preview-badge.active{background:rgba(16,185,129,.15);color:#10b981}
.preview-badge.inactive{background:rgba(100,116,139,.15);color:#94a3b8}
.preview-badge.default{background:rgba(245,158,11,.15);color:#f59e0b}
.preview-container{max-width:680px;margin:2rem auto;padding:0 1rem 3rem}
.preview-subject{background:#fff;border-radius:8px 8px 0 0;padding:.75rem 1.25rem;border:1px solid #e2e8f0;border-bottom:none;font-size:.875rem;color:#475569}
.preview-subject strong{color:#1e293b}
.preview-email-wrap{border:1px solid #e2e8f0;border-radius:0 0 8px 8px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08)}
@media(max-width:600px){.preview-container{padding:0 .5rem 2rem}.preview-bar{flex-direction:column;gap:.5rem;text-align:center}}
</style>
</head>
<body>
<div class="preview-bar">
  <div>
    <h2><i class="fas fa-envelope" style="margin-right:.4rem;color:#6366f1"></i> {{ $template->name }}</h2>
    <p>{{ \App\Models\EcomMailTemplate::$types[$template->type] ?? ucfirst($template->type) }} Template Preview</p>
  </div>
  <div class="preview-bar-actions">
    <a href="{{ route('admin.ecommerce.settings.mail-templates.edit', $template->id) }}" class="preview-bar-btn primary">
      <i class="fas fa-edit"></i> Edit
    </a>
    <a href="{{ route('admin.ecommerce.settings.mail-templates.show', $template->id) }}" class="preview-bar-btn outline">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>
</div>

<div class="preview-meta">
  <div class="preview-meta-item"><strong>Type:</strong> {{ \App\Models\EcomMailTemplate::$types[$template->type] ?? ucfirst($template->type) }}</div>
  <div class="preview-meta-item"><strong>Font:</strong> {{ explode(',', $template->font_family)[0] }}</div>
  <div class="preview-meta-item">
    <span class="preview-badge {{ $template->is_active ? 'active' : 'inactive' }}">
      <i class="fas fa-circle" style="font-size:.4rem"></i>
      {{ $template->is_active ? 'Active' : 'Inactive' }}
    </span>
  </div>
  @if($template->is_default)
  <div class="preview-meta-item">
    <span class="preview-badge default"><i class="fas fa-star" style="font-size:.65rem"></i> Default</span>
  </div>
  @endif
  <div class="preview-meta-item" style="display:flex;align-items:center;gap:.4rem">
    <span style="display:inline-block;width:14px;height:14px;border-radius:3px;background:{{ $template->primary_color }};border:1px solid rgba(255,255,255,.2)"></span>
    <strong>Primary:</strong> {{ $template->primary_color }}
  </div>
</div>

<div class="preview-container">
  <div class="preview-subject">
    <strong>Subject:</strong> {{ $template->subject }}
  </div>
  <div class="preview-email-wrap">
    {!! $template->body_html !!}
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</body>
</html>
