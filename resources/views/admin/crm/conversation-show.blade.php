@extends('layouts.admin')
@section('title', 'Conversation Detail')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;max-width:900px;">

    {{-- Header --}}
    <div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <a href="{{ route('admin.crm.conversations') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;"><i class="fas fa-arrow-left"></i> Back to Chat Monitor</a>
            <h1 style="font-size:1.5rem;font-weight:700;color:#fff;margin:0.5rem 0 0.25rem;">Conversation Detail</h1>
            @if($lead)
                <p style="color:#9ca3af;margin:0;">
                    Lead: <a href="{{ route('admin.crm.lead.show', $lead) }}" style="color:#60a5fa;">{{ $lead->name }}</a>
                    @if($lead->email) &middot; <span style="color:#a78bfa;">{{ $lead->email }}</span> @endif
                </p>
            @else
                <p style="color:#9ca3af;margin:0;">Session: <code style="color:#a78bfa;font-size:0.8rem;">{{ $sessionId }}</code></p>
            @endif
        </div>
        @if($lead)
        <a href="{{ route('admin.crm.lead.show', $lead) }}" style="background:#1e3a5f;color:#60a5fa;padding:0.5rem 1rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">
            <i class="fas fa-user"></i> View Lead Profile
        </a>
        @endif
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;">
            <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#450a0a;border:1px solid #ef4444;color:#fca5a5;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;">
            <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Conversation Thread --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;display:flex;flex-direction:column;gap:0.875rem;margin-bottom:1.5rem;max-height:550px;overflow-y:auto;" id="conversation-thread">
        @forelse($messages as $msg)
        @php
            $isUser  = $msg->role === 'user';
            $isAdmin = str_starts_with($msg->message, '[Admin Reply]');
            $displayMsg = $isAdmin ? substr($msg->message, 14) : $msg->message;
        @endphp
        <div style="display:flex;{{ $isUser ? 'justify-content:flex-end' : 'justify-content:flex-start' }}">
            <div style="max-width:78%;background:{{ $isUser ? '#1e3a5f' : ($isAdmin ? '#1a2e1a' : '#0f172a') }};border:1px solid {{ $isUser ? '#3b82f6' : ($isAdmin ? '#16a34a' : '#334155') }};border-radius:12px;padding:0.75rem 1rem;">
                <div style="font-size:0.7rem;color:#9ca3af;margin-bottom:0.3rem;display:flex;align-items:center;gap:0.4rem;">
                    @if($isAdmin)
                        <i class="fas fa-user-shield" style="color:#22c55e;"></i>
                        <span style="color:#22c55e;font-weight:600;">Admin Reply</span>
                    @elseif($isUser)
                        <i class="fas fa-user" style="color:#60a5fa;"></i>
                        <span>{{ $lead?->name ?? 'Visitor' }}</span>
                    @else
                        <i class="fas fa-robot" style="color:#a78bfa;"></i>
                        <span>Gopi AI</span>
                    @endif
                    <span style="margin-left:auto;">{{ $msg->created_at->format('H:i · d M Y') }}</span>
                </div>
                <div style="color:#e2e8f0;font-size:0.875rem;line-height:1.65;white-space:pre-wrap;">{{ $displayMsg }}</div>
            </div>
        </div>
        @empty
        <div style="text-align:center;color:#9ca3af;padding:2rem;">No messages in this conversation.</div>
        @endforelse
    </div>

    {{-- Admin Reply Box --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
        <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1rem;">
            <i class="fas fa-reply" style="color:#6366f1;margin-right:8px;"></i>
            Send Reply
            @if($lead && $lead->email)
                <span style="font-size:0.75rem;font-weight:400;color:#9ca3af;margin-left:8px;">Will also be emailed to {{ $lead->email }}</span>
            @else
                <span style="font-size:0.75rem;font-weight:400;color:#9ca3af;margin-left:8px;">No email on file &mdash; reply saved to conversation only</span>
            @endif
        </h3>
        <form method="POST" action="{{ route('admin.crm.conversation.reply', $sessionId) }}">
            @csrf
            <textarea name="message" rows="4" required
                placeholder="Type your reply here... This will appear in the conversation and be emailed to the visitor if they provided an email."
                style="width:100%;background:#0f172a;border:1px solid #334155;color:#e2e8f0;padding:0.75rem 1rem;border-radius:8px;font-size:0.875rem;resize:vertical;box-sizing:border-box;line-height:1.6;outline:none;"
                onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#334155'"></textarea>
            <div style="display:flex;justify-content:flex-end;margin-top:0.75rem;gap:0.75rem;">
                <a href="{{ route('admin.crm.conversations') }}" style="background:#374151;color:#e2e8f0;padding:0.55rem 1.25rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">
                    Cancel
                </a>
                <button type="submit" style="background:#6366f1;color:#fff;padding:0.55rem 1.5rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:0.875rem;">
                    <i class="fas fa-paper-plane" style="margin-right:6px;"></i>Send Reply
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Conversation --}}
    <div style="margin-top:1rem;text-align:right;">
        <form method="POST" action="{{ route('admin.crm.conversation.destroy', $sessionId) }}" onsubmit="return confirm('Delete this entire conversation?')">
            @csrf @method('DELETE')
            <button type="submit" style="background:transparent;color:#ef4444;border:1px solid #450a0a;padding:0.4rem 1rem;border-radius:8px;cursor:pointer;font-size:0.8rem;">
                <i class="fas fa-trash" style="margin-right:4px;"></i>Delete Conversation
            </button>
        </form>
    </div>
</div>

<script>
// Auto-scroll to bottom of conversation thread
const thread = document.getElementById('conversation-thread');
if (thread) thread.scrollTop = thread.scrollHeight;
</script>
@endsection
