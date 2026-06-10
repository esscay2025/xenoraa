<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preview: {{ $template->name }}</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: #f1f5f9; font-family: {{ $template->font_family }}; padding: 20px; }
.preview-bar {
    background: #1e293b;
    color: #e2e8f0;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 0.85rem;
}
.preview-bar a { color: #94a3b8; text-decoration: none; }
.preview-bar a:hover { color: #e2e8f0; }
.email-wrapper {
    max-width: 680px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 30px rgba(0,0,0,0.15);
}
</style>
</head>
<body>
<div class="preview-bar">
    <span><strong>Preview:</strong> {{ $template->name }} &nbsp;|&nbsp; Type: {{ ucwords(str_replace('_',' ',$template->type)) }}</span>
    <a href="{{ route('admin.crm2.settings.mail-templates.edit', $template->id) }}">← Back to Edit</a>
</div>

<div class="email-wrapper">
    {{-- Logo / Header --}}
    @if($template->show_logo && $template->logo_path)
    <div style="background:{{ $template->primary_color }};padding:24px 40px;text-align:center;">
        <img src="{{ Storage::url($template->logo_path) }}" alt="{{ $template->header_text ?? 'Logo' }}" style="max-height:60px;max-width:200px;">
    </div>
    @elseif($template->header_text)
    <div style="background:{{ $template->primary_color }};padding:24px 40px;">
        <h2 style="margin:0;color:#fff;font-family:{{ $template->font_family }};font-size:1.3rem;font-weight:700;">{{ $template->header_text }}</h2>
    </div>
    @endif

    {{-- Body --}}
    @if($template->body_html)
    <div style="font-family:{{ $template->font_family }};">
        {!! $template->body_html !!}
    </div>
    @else
    <div style="padding:3rem;text-align:center;color:#94a3b8;">
        <p>No body HTML defined for this template.</p>
    </div>
    @endif

    {{-- Footer --}}
    @if($template->show_footer && $template->footer_text)
    <div style="background:{{ $template->secondary_color }};padding:20px 40px;border-top:1px solid #e2e8f0;">
        <p style="margin:0;font-size:0.8rem;color:#64748b;font-family:{{ $template->font_family }};text-align:center;">{{ $template->footer_text }}</p>
    </div>
    @endif
</div>
</body>
</html>
