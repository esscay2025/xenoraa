@extends('layouts.superadmin')
@section('title', 'Conversation Detail')
@section('page_title', 'Conversation Detail')

@section('content')
<style>
.conv-wrap { max-width:800px; }
.conv-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.conv-back { background:#334155; color:#94a3b8; padding:.5rem 1rem; border-radius:8px; text-decoration:none; font-size:.875rem; }
.conv-back:hover { background:#475569; color:#e2e8f0; }
.conv-lead-card { background:#1e293b; border:1px solid #334155; border-radius:12px; padding:1.25rem; margin-bottom:1.5rem; display:flex; gap:1.5rem; flex-wrap:wrap; }
.conv-lead-field { display:flex; flex-direction:column; gap:.2rem; }
.conv-lead-label { font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.05em; }
.conv-lead-val { font-size:.9rem; color:#e2e8f0; font-weight:600; }
.conv-messages { background:#0f172a; border:1px solid #1e293b; border-radius:12px; padding:1.5rem; display:flex; flex-direction:column; gap:1rem; min-height:200px; }
.conv-msg { display:flex; gap:.75rem; max-width:85%; }
.conv-msg.user { align-self:flex-end; flex-direction:row-reverse; }
.conv-msg.assistant { align-self:flex-start; }
.conv-avatar { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; }
.conv-msg.user .conv-avatar { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.conv-msg.assistant .conv-avatar { background:linear-gradient(135deg,#0ea5e9,#6366f1); color:#fff; }
.conv-bubble { padding:.75rem 1rem; border-radius:12px; font-size:.875rem; line-height:1.6; }
.conv-msg.user .conv-bubble { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border-radius:12px 4px 12px 12px; }
.conv-msg.assistant .conv-bubble { background:#1e293b; color:#cbd5e1; border:1px solid #334155; border-radius:4px 12px 12px 12px; }
.conv-time { font-size:.7rem; color:#475569; margin-top:.25rem; }
.conv-msg.user .conv-time { text-align:right; }
</style>

<div class="conv-wrap">
    {{-- Header --}}
    <div class="conv-header">
        <a href="{{ route('superadmin.training-hub.conversations') }}" class="conv-back">
            <i class="fas fa-arrow-left"></i> Back to Conversations
        </a>
        <div>
            <h2 style="font-size:1.1rem;font-weight:700;color:#e2e8f0;margin:0;">Conversation Detail</h2>
            <p style="color:#64748b;font-size:.78rem;margin:.15rem 0 0;">Session: {{ $sessionId }}</p>
        </div>
    </div>

    {{-- Lead Card --}}
    @if($lead)
    <div class="conv-lead-card">
        <div class="conv-lead-field">
            <span class="conv-lead-label">Visitor Name</span>
            <span class="conv-lead-val">{{ $lead->name ?? '—' }}</span>
        </div>
        <div class="conv-lead-field">
            <span class="conv-lead-label">Email</span>
            <span class="conv-lead-val">{{ $lead->email ?? '—' }}</span>
        </div>
        <div class="conv-lead-field">
            <span class="conv-lead-label">Mobile</span>
            <span class="conv-lead-val">{{ $lead->mobile ?? '—' }}</span>
        </div>
        <div class="conv-lead-field">
            <span class="conv-lead-label">Intent</span>
            <span class="conv-lead-val">{{ ucfirst(str_replace('_', ' ', $lead->notes ?? 'General')) }}</span>
        </div>
        <div class="conv-lead-field">
            <span class="conv-lead-label">Lead Status</span>
            <span class="conv-lead-val">{{ ucfirst($lead->status ?? 'new') }}</span>
        </div>
        <div class="conv-lead-field">
            <span class="conv-lead-label">Messages</span>
            <span class="conv-lead-val">{{ $messages->count() }}</span>
        </div>
    </div>
    @else
    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1rem;margin-bottom:1.5rem;color:#64748b;font-size:.875rem;">
        <i class="fas fa-user-secret"></i> Anonymous visitor — no contact details captured yet.
    </div>
    @endif

    {{-- Messages --}}
    <div class="conv-messages">
        @forelse($messages as $msg)
        <div class="conv-msg {{ $msg->role }}">
            <div class="conv-avatar">
                {{ $msg->role === 'user' ? 'V' : 'X' }}
            </div>
            <div>
                <div class="conv-bubble">{{ $msg->message }}</div>
                <div class="conv-time">{{ $msg->created_at->format('d M Y, h:i A') }}</div>
            </div>
        </div>
        @empty
        <div style="text-align:center;color:#475569;padding:2rem;">No messages in this conversation.</div>
        @endforelse
    </div>
</div>
@endsection
