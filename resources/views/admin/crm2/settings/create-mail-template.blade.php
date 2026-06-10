@extends('layouts.admin')
@section('title', 'Create Mail Template — CRM Settings')
@section('content')
<link rel="stylesheet" href="{{ asset('css/crm2.css') }}?v={{ filemtime(public_path('css/crm2.css')) }}">

<div class="crm2-page">
    <div class="crm2-page-header">
        <div>
            <a href="{{ route('admin.crm2.settings.mail-templates') }}" style="font-size:0.8rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;margin-bottom:0.5rem;">
                <i class="fas fa-arrow-left"></i> Back to Templates
            </a>
            <h1 class="crm2-page-title"><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:0.5rem;"></i>Create Mail Template</h1>
        </div>
    </div>

    @if($errors->any())
    <div class="crm2-alert crm2-alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.crm2.settings.mail-templates.store') }}" enctype="multipart/form-data" id="tplForm">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

            {{-- Main Form --}}
            <div style="display:flex;flex-direction:column;gap:1.25rem;">

                {{-- Basic Info --}}
                <div class="crm2-card">
                    <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-info-circle"></i> Template Info</h3></div>
                    <div class="crm2-card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                            <div class="crm2-field">
                                <label class="crm2-label">Template Name <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="name" class="crm2-input" value="{{ old('name') }}" placeholder="e.g. Invoice Template" required>
                            </div>
                            <div class="crm2-field">
                                <label class="crm2-label">Template Type <span style="color:var(--danger);">*</span></label>
                                <select name="type" class="crm2-select" required>
                                    <option value="">— Select Type —</option>
                                    @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="crm2-field" style="grid-column:1/-1;">
                                <label class="crm2-label">Default Email Subject</label>
                                <input type="text" name="subject" class="crm2-input" value="{{ old('subject') }}"
                                    placeholder="e.g. Invoice {{invoice_number}} from {{company_name}}">
                                <small class="crm2-hint">Use <code style="background:var(--bg-secondary);padding:0.1rem 0.3rem;border-radius:3px;">{{variable_name}}</code> for dynamic values</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Design --}}
                <div class="crm2-card">
                    <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-palette"></i> Design & Branding</h3></div>
                    <div class="crm2-card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem;">
                            <div class="crm2-field">
                                <label class="crm2-label">Primary Colour</label>
                                <div style="display:flex;gap:0.5rem;align-items:center;">
                                    <input type="color" name="primary_color" id="primaryColor" value="{{ old('primary_color','#6366f1') }}"
                                        style="width:44px;height:36px;border:1px solid var(--border);border-radius:6px;cursor:pointer;padding:2px;">
                                    <input type="text" id="primaryColorText" value="{{ old('primary_color','#6366f1') }}"
                                        class="crm2-input" style="flex:1;" oninput="document.getElementById('primaryColor').value=this.value">
                                </div>
                            </div>
                            <div class="crm2-field">
                                <label class="crm2-label">Secondary Colour</label>
                                <div style="display:flex;gap:0.5rem;align-items:center;">
                                    <input type="color" name="secondary_color" id="secondaryColor" value="{{ old('secondary_color','#f8fafc') }}"
                                        style="width:44px;height:36px;border:1px solid var(--border);border-radius:6px;cursor:pointer;padding:2px;">
                                    <input type="text" id="secondaryColorText" value="{{ old('secondary_color','#f8fafc') }}"
                                        class="crm2-input" style="flex:1;" oninput="document.getElementById('secondaryColor').value=this.value">
                                </div>
                            </div>
                            <div class="crm2-field">
                                <label class="crm2-label">Font Family</label>
                                <select name="font_family" class="crm2-select">
                                    @foreach(['Inter, sans-serif'=>'Inter','Arial, sans-serif'=>'Arial','Georgia, serif'=>'Georgia','Trebuchet MS, sans-serif'=>'Trebuchet MS','Verdana, sans-serif'=>'Verdana'] as $v=>$l)
                                    <option value="{{ $v }}" {{ old('font_family','Inter, sans-serif') == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="crm2-field">
                                <label class="crm2-label">Company / Header Text</label>
                                <input type="text" name="header_text" class="crm2-input" value="{{ old('header_text') }}"
                                    placeholder="{{company_name}}">
                            </div>
                            <div class="crm2-field" style="grid-column:2/-1;">
                                <label class="crm2-label">Logo</label>
                                <input type="file" name="logo" accept="image/*" class="crm2-input" style="padding:0.4rem;">
                                <small class="crm2-hint">PNG, JPG, SVG — max 2MB. Recommended: 200×60px</small>
                            </div>
                        </div>
                        <div style="display:flex;gap:1.5rem;margin-top:1rem;">
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.875rem;">
                                <input type="checkbox" name="show_logo" value="1" {{ old('show_logo',1) ? 'checked' : '' }} style="accent-color:var(--accent);">
                                Show logo in email
                            </label>
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.875rem;">
                                <input type="checkbox" name="show_footer" value="1" {{ old('show_footer',1) ? 'checked' : '' }} style="accent-color:var(--accent);">
                                Show footer
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Body HTML --}}
                <div class="crm2-card">
                    <div class="crm2-card-header" style="justify-content:space-between;">
                        <h3 class="crm2-card-title"><i class="fas fa-code"></i> Email Body HTML</h3>
                        <div style="display:flex;gap:0.5rem;">
                            <button type="button" class="crm2-btn crm2-btn-secondary" style="font-size:0.75rem;padding:0.3rem 0.75rem;" onclick="togglePreview()">
                                <i class="fas fa-eye" id="previewIcon"></i> <span id="previewLabel">Preview</span>
                            </button>
                        </div>
                    </div>
                    <div class="crm2-card-body">
                        <div id="editorWrap">
                            <textarea name="body_html" id="bodyHtml" class="crm2-input"
                                style="width:100%;min-height:400px;font-family:monospace;font-size:0.8rem;resize:vertical;line-height:1.5;"
                                placeholder="Enter your HTML email body here...">{{ old('body_html') }}</textarea>
                        </div>
                        <div id="previewWrap" style="display:none;border:1px solid var(--border);border-radius:8px;overflow:hidden;">
                            <iframe id="previewFrame" style="width:100%;height:500px;border:none;background:#fff;"></iframe>
                        </div>
                        <div style="margin-top:0.75rem;padding:0.75rem;background:var(--bg-secondary);border-radius:8px;">
                            <p style="margin:0 0 0.4rem;font-size:0.75rem;font-weight:600;color:var(--text-muted);">Available Variables:</p>
                            <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                @foreach(['{{company_name}}','{{client_name}}','{{invoice_number}}','{{quote_number}}','{{so_number}}','{{po_number}}','{{invoice_date}}','{{due_date}}','{{total}}','{{subtotal}}','{{tax_amount}}','{{discount_amount}}','{{line_items}}','{{notes}}','{{status}}','{{primary_color}}','{{secondary_color}}','{{font_family}}'] as $var)
                                <code onclick="insertVar('{{ $var }}')" style="background:var(--bg-card);border:1px solid var(--border);padding:0.15rem 0.4rem;border-radius:4px;font-size:0.7rem;cursor:pointer;transition:background 0.15s;" onmouseover="this.style.background='var(--accent)20'" onmouseout="this.style.background='var(--bg-card)'">{{ $var }}</code>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="crm2-card">
                    <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-align-center"></i> Footer Text</h3></div>
                    <div class="crm2-card-body">
                        <textarea name="footer_text" class="crm2-input" rows="3"
                            placeholder="e.g. Thank you for your business. Payment due by {{due_date}}.">{{ old('footer_text') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:80px;">
                <div class="crm2-card">
                    <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-cog"></i> Options</h3></div>
                    <div class="crm2-card-body" style="padding:1.25rem;display:flex;flex-direction:column;gap:1rem;">
                        <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                            <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} style="accent-color:var(--accent);width:16px;height:16px;">
                            <div>
                                <p style="margin:0;font-size:0.875rem;font-weight:500;">Set as Default</p>
                                <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">Use this template by default for the selected type</p>
                            </div>
                        </label>
                        <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--accent);width:16px;height:16px;">
                            <div>
                                <p style="margin:0;font-size:0.875rem;font-weight:500;">Active</p>
                                <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">Template is available for use in CRM flows</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="crm2-card">
                    <div class="crm2-card-body" style="padding:1.25rem;display:flex;flex-direction:column;gap:0.75rem;">
                        <button type="submit" class="crm2-btn crm2-btn-primary" style="width:100%;">
                            <i class="fas fa-save"></i> Create Template
                        </button>
                        <a href="{{ route('admin.crm2.settings.mail-templates') }}" class="crm2-btn crm2-btn-secondary" style="width:100%;text-align:center;">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('primaryColor').addEventListener('input', function() {
    document.getElementById('primaryColorText').value = this.value;
});
document.getElementById('secondaryColor').addEventListener('input', function() {
    document.getElementById('secondaryColorText').value = this.value;
});

function insertVar(v) {
    const ta = document.getElementById('bodyHtml');
    const start = ta.selectionStart, end = ta.selectionEnd;
    ta.value = ta.value.substring(0, start) + v + ta.value.substring(end);
    ta.selectionStart = ta.selectionEnd = start + v.length;
    ta.focus();
}

let previewMode = false;
function togglePreview() {
    previewMode = !previewMode;
    document.getElementById('editorWrap').style.display = previewMode ? 'none' : 'block';
    document.getElementById('previewWrap').style.display = previewMode ? 'block' : 'none';
    document.getElementById('previewIcon').className = previewMode ? 'fas fa-code' : 'fas fa-eye';
    document.getElementById('previewLabel').textContent = previewMode ? 'Edit' : 'Preview';
    if (previewMode) {
        const html = document.getElementById('bodyHtml').value;
        const frame = document.getElementById('previewFrame');
        frame.contentDocument.open();
        frame.contentDocument.write('<html><body style="margin:0;padding:20px;background:#f1f5f9;">' + html + '</body></html>');
        frame.contentDocument.close();
    }
}
</script>
@endsection
