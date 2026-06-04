@extends('layouts.admin')

@section('title', 'AI Assistant')
@section('page-title', 'AI Assistant')

@push('styles')
<style>
.ai-toggle-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; }
.ai-status-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.ai-status-badge.on  { background: rgba(34,197,94,0.12); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
.ai-status-badge.off { background: rgba(239,68,68,0.12);  color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
.ai-stat-box { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; text-align: center; }
.ai-stat-box .num { font-size: 2rem; font-weight: 800; color: var(--accent); }
.ai-stat-box .lbl { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.25rem; }
.preview-widget { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); border-radius: 16px; padding: 1.5rem; position: relative; min-height: 120px; }
.preview-btn { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.4rem; box-shadow: 0 4px 20px rgba(99,102,241,0.5); cursor: pointer; }
.preview-header { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 0.75rem; margin-top: 1rem; }
.preview-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; flex-shrink: 0; }
.preview-name { font-weight: 700; color: #fff; font-size: 0.95rem; }
.preview-tagline { color: #a5b4fc; font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="page-header" style="margin-bottom:2rem;">
    <div>
        <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">AI Assistant</h2>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Configure your tenant AI chatbot — name, tagline, and visibility on your site.</p>
    </div>
    <div style="display:flex;align-items:center;gap:0.75rem;">
        @if($chatbotEnabled == '1')
            <span class="ai-status-badge on"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Active</span>
        @else
            <span class="ai-status-badge off"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Inactive</span>
        @endif
    </div>
</div>

<div class="grid" style="grid-template-columns:1fr 1fr;gap:1.5rem;display:grid;">
    {{-- Left: Settings Form --}}
    <div>
        <form method="POST" action="{{ route('admin.crm.ai.toggle.save') }}">
            @csrf
            <div class="ai-toggle-card" style="margin-bottom:1.5rem;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0 0 1.5rem;display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-robot" style="color:var(--accent);"></i> AI Assistant Toggle
                </h3>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:var(--bg-secondary);border-radius:12px;border:1px solid var(--border);">
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);">Show AI Assistant on Your Site</div>
                        <div style="font-size:0.8rem;color:var(--text-secondary);margin-top:0.2rem;">When enabled, a chat button appears on your public site for visitors to interact with your AI.</div>
                    </div>
                    <label class="toggle-switch" style="flex-shrink:0;margin-left:1rem;">
                        <input type="checkbox" name="chatbot_enabled" value="1" {{ $chatbotEnabled == '1' ? 'checked' : '' }}
                            onchange="updatePreview()">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="ai-toggle-card" style="margin-bottom:1.5rem;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0 0 1.5rem;display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-id-card" style="color:var(--accent);"></i> AI Identity
                </h3>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:var(--text-secondary);margin-bottom:0.5rem;">AI Assistant Name</label>
                    <input type="text" name="ai_assistant_name" id="aiName" class="form-control"
                        value="{{ old('ai_assistant_name', $aiName) }}"
                        placeholder="e.g. Gopi AI, Priya Bot, LexBot"
                        oninput="updatePreview()">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.4rem;">This name appears in the chat header on your site.</p>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:var(--text-secondary);margin-bottom:0.5rem;">AI Tagline / Subtitle</label>
                    <input type="text" name="ai_assistant_tagline" id="aiTagline" class="form-control"
                        value="{{ old('ai_assistant_tagline', $aiTagline) }}"
                        placeholder="e.g. Ask me about my services"
                        oninput="updatePreview()">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.4rem;">Shown below the name in the chat header.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.875rem;">
                <i class="fas fa-save"></i> Save AI Settings
            </button>
        </form>

        {{-- Stats --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1.5rem;">
            <div class="ai-stat-box">
                <div class="num">{{ $trainingCount }}</div>
                <div class="lbl">Training Entries</div>
                <a href="{{ route('admin.crm.training') }}" style="font-size:0.75rem;color:var(--accent);text-decoration:none;margin-top:0.5rem;display:block;">Manage Training →</a>
            </div>
            <div class="ai-stat-box">
                <div class="num">{{ $leadCount }}</div>
                <div class="lbl">AI Leads Captured</div>
                <a href="{{ route('admin.crm.leads') }}" style="font-size:0.75rem;color:var(--accent);text-decoration:none;margin-top:0.5rem;display:block;">View Leads →</a>
            </div>
        </div>
    </div>

    {{-- Right: Live Preview --}}
    <div>
        <div class="ai-toggle-card">
            <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0 0 1.5rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-eye" style="color:var(--accent);"></i> Live Preview
            </h3>
            <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1rem;">This is how your AI assistant button and header will appear on your site.</p>

            <div class="preview-widget" id="previewWidget">
                <div style="display:flex;justify-content:flex-end;">
                    <div class="preview-btn" id="previewBtn">
                        <i class="fas fa-robot"></i>
                    </div>
                </div>
                <div class="preview-header" id="previewHeader">
                    <div class="preview-avatar"><i class="fas fa-robot"></i></div>
                    <div>
                        <div class="preview-name" id="previewName">{{ $aiName }}</div>
                        <div class="preview-tagline" id="previewTagline">{{ $aiTagline }}</div>
                    </div>
                    <div style="margin-left:auto;">
                        <span style="background:rgba(34,197,94,0.2);color:#22c55e;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:600;">● Online</span>
                    </div>
                </div>
                <div id="previewOffMsg" style="display:none;text-align:center;padding:2rem 0;color:#9ca3af;font-size:0.9rem;">
                    <i class="fas fa-robot" style="font-size:2rem;opacity:0.3;display:block;margin-bottom:0.5rem;"></i>
                    AI Assistant is currently <strong>disabled</strong>
                </div>
            </div>

            <div style="margin-top:1.5rem;padding:1rem;background:var(--bg-secondary);border-radius:10px;border:1px solid var(--border);">
                <p style="font-size:0.82rem;color:var(--text-secondary);margin:0;">
                    <i class="fas fa-lightbulb" style="color:#f59e0b;margin-right:0.4rem;"></i>
                    <strong>Tip:</strong> Train your AI with FAQs and service details in
                    <a href="{{ route('admin.crm.training') }}" style="color:var(--accent);">Train AI</a>
                    for better responses. All leads from AI conversations appear in
                    <a href="{{ route('admin.crm.leads') }}" style="color:var(--accent);">CRM Leads</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updatePreview() {
    const nameEl    = document.getElementById('aiName');
    const taglineEl = document.getElementById('aiTagline');
    const enabledEl = document.querySelector('input[name="chatbot_enabled"]');
    const previewName    = document.getElementById('previewName');
    const previewTagline = document.getElementById('previewTagline');
    const previewHeader  = document.getElementById('previewHeader');
    const previewBtn     = document.getElementById('previewBtn');
    const previewOffMsg  = document.getElementById('previewOffMsg');

    if (previewName)    previewName.textContent    = nameEl?.value    || 'AI Assistant';
    if (previewTagline) previewTagline.textContent = taglineEl?.value || 'Ask me anything';

    const isOn = enabledEl?.checked;
    if (previewHeader)  previewHeader.style.display  = isOn ? 'flex' : 'none';
    if (previewBtn)     previewBtn.style.opacity      = isOn ? '1'    : '0.3';
    if (previewOffMsg)  previewOffMsg.style.display   = isOn ? 'none' : 'block';
}
document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endpush
