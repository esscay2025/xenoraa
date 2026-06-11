@extends('layouts.admin')
@section('title', 'Edit Mail Template')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
    $ecomSettingsActive = true;
@endphp
@section('content')
<style>
.tpl-page{padding:2rem;max-width:1100px;margin:0 auto}
.tpl-header{display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;flex-wrap:wrap}
.tpl-header h1{font-size:1.4rem;font-weight:700;color:var(--text-primary);margin:0}
.tpl-header a{color:var(--text-muted);text-decoration:none;font-size:.875rem;display:flex;align-items:center;gap:.4rem}
.tpl-header a:hover{color:var(--accent)}
.tpl-layout{display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start}
.tpl-card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.25rem}
.tpl-card-header{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.6rem}
.tpl-card-header h3{margin:0;font-size:.9rem;font-weight:600;color:var(--text-primary)}
.tpl-card-body{padding:1.25rem}
.tpl-field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1rem}
.tpl-field label{font-size:.78rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.04em}
.tpl-field input,.tpl-field select,.tpl-field textarea{background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;font-size:.875rem;color:var(--text-primary);outline:none;transition:border-color .2s;width:100%;box-sizing:border-box}
.tpl-field input:focus,.tpl-field select:focus,.tpl-field textarea:focus{border-color:var(--accent)}
.tpl-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.tpl-editor-toolbar{display:flex;flex-wrap:wrap;gap:.3rem;padding:.6rem .75rem;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px 8px 0 0;border-bottom:none}
.tpl-editor-btn{padding:.3rem .55rem;border:1px solid var(--border);border-radius:5px;background:var(--bg-card);color:var(--text-secondary);cursor:pointer;font-size:.78rem;transition:all .15s}
.tpl-editor-btn:hover{background:var(--accent);color:#fff;border-color:var(--accent)}
.tpl-editor-area{background:var(--bg-secondary);border:1px solid var(--border);border-radius:0 0 8px 8px;padding:.75rem;min-height:320px;font-family:monospace;font-size:.82rem;color:var(--text-primary);outline:none;resize:vertical;width:100%;box-sizing:border-box}
.tpl-vars{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.5rem}
.tpl-var-chip{padding:.2rem .6rem;background:rgba(99,102,241,.1);color:var(--accent);border-radius:20px;font-size:.72rem;font-weight:600;cursor:pointer;border:1px solid rgba(99,102,241,.2);transition:all .15s;font-family:monospace}
.tpl-var-chip:hover{background:var(--accent);color:#fff}
.tpl-toggle-row{display:flex;align-items:center;justify-content:space-between;padding:.6rem 0}
.tpl-toggle-row label{font-size:.875rem;color:var(--text-primary);font-weight:500}
.tpl-toggle-row small{display:block;font-size:.75rem;color:var(--text-muted)}
.toggle-switch{position:relative;display:inline-block;width:44px;height:24px}
.toggle-switch input{opacity:0;width:0;height:0}
.toggle-slider{position:absolute;cursor:pointer;inset:0;background:#475569;border-radius:24px;transition:.3s}
.toggle-slider:before{content:"";position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s}
input:checked+.toggle-slider{background:var(--accent)}
input:checked+.toggle-slider:before{transform:translateX(20px)}
.tpl-actions{display:flex;gap:.75rem;flex-wrap:wrap;margin-top:1.25rem}
.tpl-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.25rem;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
.tpl-btn.primary{background:var(--accent);color:#fff}
.tpl-btn.primary:hover{opacity:.9}
.tpl-btn.outline{background:transparent;border:1px solid var(--border);color:var(--text-primary)}
.tpl-btn.outline:hover{background:var(--bg-secondary)}
.tpl-preview-frame{width:100%;height:500px;border:none;border-radius:8px;background:#fff}
.tpl-color-row{display:flex;align-items:center;gap:.75rem}
.tpl-color-row input[type=color]{width:40px;height:36px;border-radius:6px;border:1px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-secondary)}
.tpl-color-row input[type=text]{flex:1}
.tpl-logo-current{display:flex;align-items:center;gap:.75rem;padding:.6rem .9rem;background:var(--bg-secondary);border-radius:8px;margin-bottom:.75rem}
.tpl-logo-current img{max-height:40px;border-radius:4px}
@media(max-width:900px){.tpl-layout{grid-template-columns:1fr}.tpl-grid-2{grid-template-columns:1fr}}
</style>

<div class="tpl-page">
  <div class="tpl-header">
    <a href="{{ route('admin.ecommerce.settings.mail-templates') }}">
      <i class="fas fa-arrow-left"></i> Back to Templates
    </a>
    <h1>Edit: {{ $template->name }}</h1>
    <a href="{{ route('admin.ecommerce.settings.mail-templates.preview', $template->id) }}" target="_blank" style="margin-left:auto" class="tpl-btn outline" style="padding:.4rem .9rem;font-size:.8rem">
      <i class="fas fa-external-link-alt"></i> Full Preview
    </a>
  </div>

  <form method="POST" action="{{ route('admin.ecommerce.settings.mail-templates.update', $template->id) }}" enctype="multipart/form-data" id="tplForm">
    @csrf @method('PUT')
    <div class="tpl-layout">
      {{-- Left: Editor --}}
      <div>
        <div class="tpl-card">
          <div class="tpl-card-header">
            <i class="fas fa-info-circle" style="color:var(--accent)"></i>
            <h3>Template Information</h3>
          </div>
          <div class="tpl-card-body">
            <div class="tpl-grid-2">
              <div class="tpl-field">
                <label>Template Name *</label>
                <input type="text" name="name" value="{{ old('name', $template->name) }}" required>
              </div>
              <div class="tpl-field">
                <label>Template Type *</label>
                <select name="type" id="typeSelect" onchange="updateVariables()">
                  @foreach($types as $val => $lbl)
                  <option value="{{ $val }}" {{ (old('type', $template->type)) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="tpl-field">
              <label>Email Subject *</label>
              <input type="text" name="subject" value="{{ old('subject', $template->subject) }}" required>
            </div>
          </div>
        </div>

        <div class="tpl-card">
          <div class="tpl-card-header">
            <i class="fas fa-code" style="color:var(--accent)"></i>
            <h3>Email Body (HTML)</h3>
          </div>
          <div class="tpl-card-body" style="padding-bottom:.5rem">
            <div class="tpl-editor-toolbar">
              <button type="button" class="tpl-editor-btn" onclick="wrapTag('b')"><b>B</b></button>
              <button type="button" class="tpl-editor-btn" onclick="wrapTag('i')"><i>I</i></button>
              <button type="button" class="tpl-editor-btn" onclick="wrapTag('u')"><u>U</u></button>
              <button type="button" class="tpl-editor-btn" onclick="insertTag('h2')">H2</button>
              <button type="button" class="tpl-editor-btn" onclick="insertTag('h3')">H3</button>
              <button type="button" class="tpl-editor-btn" onclick="insertTag('p')">P</button>
              <button type="button" class="tpl-editor-btn" onclick="insertLink()"><i class="fas fa-link"></i></button>
              <button type="button" class="tpl-editor-btn" onclick="insertBtn()"><i class="fas fa-square"></i> Button</button>
              <button type="button" class="tpl-editor-btn" onclick="insertTable()"><i class="fas fa-table"></i> Table</button>
              <button type="button" class="tpl-editor-btn" onclick="insertDivider()"><i class="fas fa-minus"></i> Divider</button>
              <button type="button" class="tpl-editor-btn" style="margin-left:auto" onclick="livePreview()"><i class="fas fa-eye"></i> Preview</button>
            </div>
            <textarea class="tpl-editor-area" name="body_html" id="bodyHtml">{{ old('body_html', $template->body_html) }}</textarea>
            <div style="margin-top:.75rem">
              <div style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem">Click to insert variable:</div>
              <div class="tpl-vars" id="varChips"></div>
            </div>
          </div>
        </div>

        <div class="tpl-card">
          <div class="tpl-card-header">
            <i class="fas fa-image" style="color:var(--accent)"></i>
            <h3>Logo</h3>
          </div>
          <div class="tpl-card-body">
            @if($template->logo_path)
            <div class="tpl-logo-current">
              <img src="{{ Storage::url($template->logo_path) }}" alt="Current logo">
              <span style="font-size:.8rem;color:var(--text-muted)">Current logo — upload a new one to replace</span>
            </div>
            @endif
            <div class="tpl-field">
              <label>Upload New Logo (optional)</label>
              <input type="file" name="logo" accept="image/*" onchange="previewLogo(this)">
            </div>
            <img id="logoPreview" src="" alt="" style="max-height:60px;display:none;margin-top:.5rem;border-radius:6px">
          </div>
        </div>
      </div>

      {{-- Right: Settings + Preview --}}
      <div>
        <div class="tpl-card">
          <div class="tpl-card-header">
            <i class="fas fa-palette" style="color:var(--accent)"></i>
            <h3>Styling</h3>
          </div>
          <div class="tpl-card-body">
            <div class="tpl-field">
              <label>Primary Colour</label>
              <div class="tpl-color-row">
                <input type="color" id="primaryColorPicker" value="{{ old('primary_color', $template->primary_color) }}" oninput="document.getElementById('primaryColorText').value=this.value">
                <input type="text" name="primary_color" id="primaryColorText" value="{{ old('primary_color', $template->primary_color) }}" oninput="document.getElementById('primaryColorPicker').value=this.value">
              </div>
            </div>
            <div class="tpl-field">
              <label>Secondary / Background Colour</label>
              <div class="tpl-color-row">
                <input type="color" id="secondaryColorPicker" value="{{ old('secondary_color', $template->secondary_color) }}" oninput="document.getElementById('secondaryColorText').value=this.value">
                <input type="text" name="secondary_color" id="secondaryColorText" value="{{ old('secondary_color', $template->secondary_color) }}" oninput="document.getElementById('secondaryColorPicker').value=this.value">
              </div>
            </div>
            <div class="tpl-field">
              <label>Font Family</label>
              <select name="font_family">
                @foreach(['Inter, Arial, sans-serif'=>'Inter (Modern)','Arial, sans-serif'=>'Arial (Classic)','Georgia, serif'=>'Georgia (Elegant)','Trebuchet MS, sans-serif'=>'Trebuchet MS','Verdana, sans-serif'=>'Verdana'] as $val=>$lbl)
                <option value="{{ $val }}" {{ (old('font_family', $template->font_family)) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="tpl-card">
          <div class="tpl-card-header">
            <i class="fas fa-cog" style="color:var(--accent)"></i>
            <h3>Options</h3>
          </div>
          <div class="tpl-card-body">
            <div class="tpl-toggle-row">
              <div>
                <label>Set as Default</label>
                <small>Use this template by default for this type</small>
              </div>
              <label class="toggle-switch">
                <input type="checkbox" name="is_default" value="1" {{ $template->is_default ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
              </label>
            </div>
            <div class="tpl-toggle-row">
              <div>
                <label>Active</label>
                <small>Make this template available for use</small>
              </div>
              <label class="toggle-switch">
                <input type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
              </label>
            </div>
          </div>
        </div>

        <div class="tpl-card">
          <div class="tpl-card-header">
            <i class="fas fa-eye" style="color:var(--accent)"></i>
            <h3>Live Preview</h3>
          </div>
          <div class="tpl-card-body" style="padding:.75rem">
            <iframe class="tpl-preview-frame" id="previewFrame" srcdoc=""></iframe>
          </div>
        </div>

        <div class="tpl-actions">
          <button type="submit" class="tpl-btn primary"><i class="fas fa-save"></i> Update Template</button>
          <a href="{{ route('admin.ecommerce.settings.mail-templates') }}" class="tpl-btn outline">Cancel</a>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
const typeVars = {
    order_confirmation: ['{{customer_name}}','{{order_number}}','{{order_date}}','{{total_amount}}','{{payment_method}}','{{shipping_address}}','{{line_items}}','{{order_url}}','{{store_name}}'],
    order_shipped:      ['{{customer_name}}','{{order_number}}','{{tracking_number}}','{{carrier}}','{{estimated_delivery}}','{{tracking_url}}','{{store_name}}'],
    order_delivered:    ['{{customer_name}}','{{order_number}}','{{delivery_date}}','{{review_url}}','{{store_name}}'],
    order_cancelled:    ['{{customer_name}}','{{order_number}}','{{cancellation_reason}}','{{shop_url}}','{{store_name}}'],
    payment_received:   ['{{customer_name}}','{{order_number}}','{{transaction_id}}','{{amount_paid}}','{{payment_date}}','{{order_url}}','{{store_name}}'],
    payment_failed:     ['{{customer_name}}','{{order_number}}','{{amount}}','{{retry_url}}','{{store_name}}'],
    refund_processed:   ['{{customer_name}}','{{order_number}}','{{refund_amount}}','{{refund_method}}','{{store_name}}'],
    cart_abandoned:     ['{{customer_name}}','{{cart_items}}','{{cart_url}}','{{store_name}}'],
    review_request:     ['{{customer_name}}','{{order_number}}','{{review_url}}','{{store_name}}'],
    welcome:            ['{{customer_name}}','{{shop_url}}','{{store_name}}'],
    general:            ['{{customer_name}}','{{heading}}','{{message_body}}','{{cta_text}}','{{cta_url}}','{{store_name}}'],
};
function updateVariables() {
    const type = document.getElementById('typeSelect').value;
    const vars = typeVars[type] || typeVars.general;
    document.getElementById('varChips').innerHTML = vars.map(v =>
        `<span class="tpl-var-chip" onclick="insertVar('${v}')">${v}</span>`
    ).join('');
}
function insertVar(v) {
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart, e = ta.selectionEnd;
    ta.value = ta.value.substring(0, s) + v + ta.value.substring(e);
    ta.selectionStart = ta.selectionEnd = s + v.length;
    ta.focus();
}
function wrapTag(tag) {
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart, e = ta.selectionEnd;
    const sel = ta.value.substring(s, e) || 'text';
    ta.value = ta.value.substring(0, s) + `<${tag}>${sel}</${tag}>` + ta.value.substring(e);
    ta.focus();
}
function insertTag(tag) {
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart;
    ta.value = ta.value.substring(0, s) + `<${tag}>Content</${tag}>\n` + ta.value.substring(s);
    ta.focus();
}
function insertLink() {
    const url = prompt('Enter URL:', 'https://');
    if (!url) return;
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart, e = ta.selectionEnd;
    const text = ta.value.substring(s, e) || 'Click here';
    ta.value = ta.value.substring(0, s) + `<a href="${url}" style="color:#6366f1">${text}</a>` + ta.value.substring(e);
    ta.focus();
}
function insertBtn() {
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart;
    const color = document.getElementById('primaryColorText').value || '#6366f1';
    ta.value = ta.value.substring(0, s) + `<div style="text-align:center;margin:24px 0;">\n  <a href="{{cta_url}}" style="background:${color};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">{{cta_text}}</a>\n</div>\n` + ta.value.substring(s);
    ta.focus();
}
function insertTable() {
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart;
    ta.value = ta.value.substring(0, s) + `<table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:8px;padding:16px;margin-bottom:16px;">\n  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Label:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{value}}</td></tr>\n</table>\n` + ta.value.substring(s);
    ta.focus();
}
function insertDivider() {
    const ta = document.getElementById('bodyHtml');
    const s = ta.selectionStart;
    ta.value = ta.value.substring(0, s) + `<hr style="border:none;border-top:1px solid #e2e8f0;margin:20px 0;">\n` + ta.value.substring(s);
    ta.focus();
}
function livePreview() {
    const html = document.getElementById('bodyHtml').value;
    document.getElementById('previewFrame').srcdoc = html || '<p style="color:#94a3b8;text-align:center;padding:2rem;font-family:sans-serif">No content yet.</p>';
}
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
updateVariables();
// Auto-load preview on page load
livePreview();
</script>
@endsection
