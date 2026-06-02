@extends('layouts.admin')
@section('title', 'CRM — Conversations')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('admin.crm.leads') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;"><i class="fas fa-arrow-left"></i> Back to Leads</a>
    </div>
    <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0 0 0.5rem;">Chatbot Conversations</h1>
    <p style="color:#9ca3af;margin:0 0 2rem;">All chatbot sessions with visitors.</p>

    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0f172a;border-bottom:1px solid #334155;">
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Session</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Lead</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Messages</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Started</th>
                    <th style="padding:0.875rem 1rem;text-align:center;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $conv)
                <tr style="border-bottom:1px solid #1e293b;" onmouseover="this.style.background='#0f172a'" onmouseout="this.style.background='transparent'">
                    <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.8rem;font-family:monospace;">{{ substr($conv->session_id, 0, 16) }}...</td>
                    <td style="padding:0.875rem 1rem;">
                        @if($conv->lead)
                            <a href="{{ route('admin.crm.lead.show', $conv->lead_id) }}" style="color:#60a5fa;text-decoration:none;font-size:0.875rem;">{{ $conv->lead->name }}</a>
                        @else
                            <span style="color:#9ca3af;font-size:0.875rem;">Anonymous</span>
                        @endif
                    </td>
                    <td style="padding:0.875rem 1rem;"><span style="background:#1e3a5f;color:#60a5fa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.8rem;">{{ $conv->message_count }}</span></td>
                    <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.8rem;">{{ \Carbon\Carbon::parse($conv->started_at)->format('d M Y H:i') }}</td>
                    <td style="padding:0.875rem 1rem;text-align:center;">
                        <a href="{{ route('admin.crm.conversation.show', $conv->session_id) }}" style="background:#1e3a5f;color:#60a5fa;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;"><i class="fas fa-eye"></i> View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding:3rem;text-align:center;color:#9ca3af;">No conversations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($conversations->hasPages())
    <div style="margin-top:1.5rem;">{{ $conversations->links() }}</div>
    @endif
</div>
@endsection
