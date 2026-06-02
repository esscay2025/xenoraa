@extends('layouts.admin')

@section('title', 'Lead — ' . $lead->name)

@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = true; $siteActive = false;
@endphp

@section('content')
<div style="padding:2rem;">

    {{-- Breadcrumb --}}
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('admin.crm.leads') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;"><i class="fas fa-arrow-left"></i> Back to Leads</a>
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;">

        {{-- Left: Lead Info + Conversations --}}
        <div>
            {{-- Lead Header --}}
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:1.25rem;flex-shrink:0;">
                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 style="color:#fff;font-size:1.25rem;font-weight:700;margin:0;">{{ $lead->name }}</h2>
                        <div style="color:#9ca3af;font-size:0.875rem;margin-top:0.25rem;">
                            @if($lead->email) <span><i class="fas fa-envelope" style="margin-right:4px;"></i>{{ $lead->email }}</span> @endif
                            @if($lead->mobile) <span style="margin-left:1rem;"><i class="fas fa-phone" style="margin-right:4px;"></i>{{ $lead->mobile }}</span> @endif
                        </div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
                    <div style="background:#0f172a;border-radius:8px;padding:0.875rem;">
                        <div style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;margin-bottom:0.25rem;">Source</div>
                        <div style="color:#60a5fa;font-weight:600;text-transform:capitalize;">{{ $lead->source }}</div>
                    </div>
                    <div style="background:#0f172a;border-radius:8px;padding:0.875rem;">
                        <div style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;margin-bottom:0.25rem;">Created</div>
                        <div style="color:#e2e8f0;font-weight:600;">{{ $lead->created_at->format('d M Y') }}</div>
                    </div>
                    <div style="background:#0f172a;border-radius:8px;padding:0.875rem;">
                        <div style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;margin-bottom:0.25rem;">Requirements</div>
                        <div style="color:#10b981;font-weight:700;font-size:1.25rem;">{{ $lead->requirements->count() }}</div>
                    </div>
                </div>
            </div>

            {{-- Requirements --}}
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
                <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1rem;">Captured Requirements</h3>
                @forelse($lead->requirements as $req)
                <div style="background:#0f172a;border:1px solid #334155;border-radius:8px;padding:1rem;margin-bottom:0.75rem;">
                    <p style="color:#e2e8f0;margin:0 0 0.75rem;line-height:1.6;">{{ $req->requirement }}</p>
                    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
                        @if($req->category) <span style="background:#1e3a5f;color:#60a5fa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">{{ ucwords(str_replace('_',' ',$req->category)) }}</span> @endif
                        @if($req->budget_range) <span style="background:#3d2a00;color:#f59e0b;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">Budget: {{ $req->budget_range }}</span> @endif
                        @if($req->timeline) <span style="background:#1a1a3e;color:#a78bfa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">Timeline: {{ $req->timeline }}</span> @endif
                        <div style="margin-left:auto;">
                            @if(!$req->scope_sent)
                                <form method="POST" action="{{ route('admin.crm.requirement.scope-sent', $req) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background:#064e3b;color:#10b981;border:1px solid #10b981;padding:0.25rem 0.75rem;border-radius:6px;font-size:0.75rem;cursor:pointer;">
                                        <i class="fas fa-paper-plane"></i> Mark Scope Sent
                                    </button>
                                </form>
                            @else
                                <span style="background:#064e3b;color:#10b981;padding:0.25rem 0.75rem;border-radius:6px;font-size:0.75rem;">
                                    <i class="fas fa-check"></i> Scope Sent {{ $req->scope_sent_at?->format('d M') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($req->pain_points)
                        <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid #334155;">
                            <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:0.25rem;">Pain Points:</div>
                            <div style="color:#fca5a5;font-size:0.85rem;">{{ $req->pain_points }}</div>
                        </div>
                    @endif
                </div>
                @empty
                <p style="color:#9ca3af;text-align:center;padding:1.5rem 0;">No requirements captured yet.</p>
                @endforelse
            </div>

            {{-- Conversation History --}}
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1rem;">Chatbot Conversation History</h3>
                <div style="max-height:400px;overflow-y:auto;display:flex;flex-direction:column;gap:0.5rem;">
                    @forelse($lead->conversations as $msg)
                    <div style="display:flex;{{ $msg->role==='user'?'justify-content:flex-end':'justify-content:flex-start' }}">
                        <div style="max-width:80%;background:{{ $msg->role==='user'?'#1e3a5f':'#1e293b' }};border:1px solid {{ $msg->role==='user'?'#3b82f6':'#334155' }};border-radius:12px;padding:0.625rem 0.875rem;">
                            <div style="font-size:0.7rem;color:#9ca3af;margin-bottom:0.25rem;">{{ $msg->role==='user'?'Visitor':'Gopi AI' }} · {{ $msg->created_at->format('H:i') }}</div>
                            <div style="color:#e2e8f0;font-size:0.875rem;line-height:1.5;">{{ $msg->message }}</div>
                        </div>
                    </div>
                    @empty
                    <p style="color:#9ca3af;text-align:center;padding:1.5rem 0;">No conversation history.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right: Update Status --}}
        <div>
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;position:sticky;top:1.5rem;">
                <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Update Lead</h3>
                <form method="POST" action="{{ route('admin.crm.lead.update', $lead) }}">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Status</label>
                        <select name="status" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                            @foreach(['new','contacted','qualified','proposal_sent','won','lost'] as $s)
                                <option value="{{ $s }}" {{ $lead->status===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Priority</label>
                        <select name="priority" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                            @foreach(['high','medium','low'] as $p)
                                <option value="{{ $p }}" {{ $lead->priority===$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Assigned To</label>
                        <input type="text" name="assigned_to" value="{{ $lead->assigned_to }}" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                    </div>
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Admin Notes</label>
                        <textarea name="notes" rows="5" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;">{{ $lead->notes }}</textarea>
                    </div>
                    <button type="submit" style="width:100%;background:#6366f1;color:#fff;padding:0.6rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;">Save Changes</button>
                </form>

                @if($lead->summary)
                <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #334155;">
                    <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:0.5rem;text-transform:uppercase;">AI Summary</div>
                    <p style="color:#e2e8f0;font-size:0.875rem;line-height:1.6;margin:0;">{{ $lead->summary }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
