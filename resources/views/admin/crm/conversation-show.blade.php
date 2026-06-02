@extends('layouts.admin')
@section('title', 'Conversation Detail')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('admin.crm.conversations') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;"><i class="fas fa-arrow-left"></i> Back to Conversations</a>
    </div>
    <h1 style="font-size:1.5rem;font-weight:700;color:#fff;margin:0 0 0.5rem;">Conversation Detail</h1>
    @if($lead)
        <p style="color:#9ca3af;margin:0 0 2rem;">Lead: <a href="{{ route('admin.crm.lead.show', $lead) }}" style="color:#60a5fa;">{{ $lead->name }}</a></p>
    @else
        <p style="color:#9ca3af;margin:0 0 2rem;">Session: <code style="color:#a78bfa;">{{ $sessionId }}</code></p>
    @endif

    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;max-width:700px;display:flex;flex-direction:column;gap:0.75rem;">
        @foreach($messages as $msg)
        <div style="display:flex;{{ $msg->role==='user'?'justify-content:flex-end':'justify-content:flex-start' }}">
            <div style="max-width:80%;background:{{ $msg->role==='user'?'#1e3a5f':'#0f172a' }};border:1px solid {{ $msg->role==='user'?'#3b82f6':'#334155' }};border-radius:12px;padding:0.75rem 1rem;">
                <div style="font-size:0.7rem;color:#9ca3af;margin-bottom:0.25rem;">{{ $msg->role==='user'?'Visitor':'Gopi AI' }} · {{ $msg->created_at->format('H:i d M') }}</div>
                <div style="color:#e2e8f0;font-size:0.875rem;line-height:1.6;">{{ $msg->message }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
