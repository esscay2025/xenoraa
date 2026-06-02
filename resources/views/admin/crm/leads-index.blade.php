@extends('layouts.admin')

@section('title', 'CRM — Leads')

@php
    $contentActive = false;
    $recruitmentActive = false;
    $financeActive = false;
    $administrationActive = false;
    $communityActive = false;
    $crmActive = true;
    $siteActive = false;
@endphp

@section('content')
<div style="padding:2rem;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">CRM — Leads</h1>
            <p style="color:#9ca3af;margin:0.25rem 0 0;">Manage all captured leads and requirements from the chatbot and other sources.</p>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
            <a href="{{ route('admin.crm.training') }}" style="background:#6366f1;color:#fff;padding:0.5rem 1.25rem;border-radius:8px;text-decoration:none;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-brain"></i> Train Chatbot
            </a>
            <a href="{{ route('admin.crm.conversations') }}" style="background:#0f172a;border:1px solid #334155;color:#e2e8f0;padding:0.5rem 1.25rem;border-radius:8px;text-decoration:none;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-comments"></i> Conversations
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:2rem;">
        @foreach([
            ['label'=>'Total Leads','value'=>$stats['total'],'color'=>'#6366f1','icon'=>'fa-users'],
            ['label'=>'New','value'=>$stats['new'],'color'=>'#06b6d4','icon'=>'fa-star'],
            ['label'=>'Qualified','value'=>$stats['qualified'],'color'=>'#f59e0b','icon'=>'fa-check-circle'],
            ['label'=>'Proposal Sent','value'=>$stats['proposal_sent'],'color'=>'#8b5cf6','icon'=>'fa-file-alt'],
            ['label'=>'Won','value'=>$stats['won'],'color'=>'#10b981','icon'=>'fa-trophy'],
        ] as $stat)
        <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;text-align:center;">
            <i class="fas {{ $stat['icon'] }}" style="font-size:1.5rem;color:{{ $stat['color'] }};margin-bottom:0.5rem;display:block;"></i>
            <div style="font-size:1.75rem;font-weight:700;color:#fff;">{{ $stat['value'] }}</div>
            <div style="font-size:0.75rem;color:#9ca3af;">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:180px;">
            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, mobile..." style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.5rem 0.75rem;border-radius:8px;font-size:0.875rem;">
        </div>
        <div style="min-width:140px;">
            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.5rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                <option value="">All Statuses</option>
                @foreach(['new','contacted','qualified','proposal_sent','won','lost'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:130px;">
            <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Priority</label>
            <select name="priority" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.5rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                <option value="">All Priorities</option>
                @foreach(['high','medium','low'] as $p)
                    <option value="{{ $p }}" {{ request('priority')===$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" style="background:#6366f1;color:#fff;padding:0.5rem 1.25rem;border-radius:8px;border:none;cursor:pointer;font-size:0.875rem;">Filter</button>
        <a href="{{ route('admin.crm.leads') }}" style="background:#374151;color:#e2e8f0;padding:0.5rem 1rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Reset</a>
    </form>

    {{-- Leads Table --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0f172a;border-bottom:1px solid #334155;">
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Lead</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Contact</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Source</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Status</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Priority</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Reqs</th>
                    <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Date</th>
                    <th style="padding:0.875rem 1rem;text-align:center;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr style="border-bottom:1px solid #1e293b;transition:background 0.15s;" onmouseover="this.style.background='#0f172a'" onmouseout="this.style.background='transparent'">
                    <td style="padding:0.875rem 1rem;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.875rem;flex-shrink:0;">
                                {{ strtoupper(substr($lead->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="color:#fff;font-weight:600;font-size:0.875rem;">{{ $lead->name }}</div>
                                @if($lead->summary)
                                    <div style="color:#9ca3af;font-size:0.75rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $lead->summary }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @if($lead->email)
                            <div style="color:#e2e8f0;font-size:0.8rem;">{{ $lead->email }}</div>
                        @endif
                        @if($lead->mobile)
                            <div style="color:#9ca3af;font-size:0.8rem;">{{ $lead->mobile }}</div>
                        @endif
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        <span style="background:#1e3a5f;color:#60a5fa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;text-transform:capitalize;">{{ $lead->source }}</span>
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @php
                            $statusColors = ['new'=>'#06b6d4','contacted'=>'#3b82f6','qualified'=>'#f59e0b','proposal_sent'=>'#8b5cf6','won'=>'#10b981','lost'=>'#ef4444'];
                            $statusBg = ['new'=>'#0c2a3a','contacted'=>'#1e3a5f','qualified'=>'#3d2a00','proposal_sent'=>'#2d1b69','won'=>'#064e3b','lost'=>'#450a0a'];
                        @endphp
                        <span style="background:{{ $statusBg[$lead->status] ?? '#1e293b' }};color:{{ $statusColors[$lead->status] ?? '#9ca3af' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;text-transform:capitalize;">
                            {{ ucwords(str_replace('_',' ',$lead->status)) }}
                        </span>
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @php $pColors = ['high'=>'#ef4444','medium'=>'#f59e0b','low'=>'#10b981']; @endphp
                        <span style="color:{{ $pColors[$lead->priority] ?? '#9ca3af' }};font-size:0.8rem;font-weight:600;">
                            <i class="fas fa-circle" style="font-size:0.5rem;margin-right:4px;"></i>{{ ucfirst($lead->priority) }}
                        </span>
                    </td>
                    <td style="padding:0.875rem 1rem;text-align:center;">
                        <span style="background:#1e3a5f;color:#60a5fa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.8rem;font-weight:600;">{{ $lead->requirements->count() }}</span>
                    </td>
                    <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.8rem;">{{ $lead->created_at->format('d M Y') }}</td>
                    <td style="padding:0.875rem 1rem;text-align:center;">
                        <div style="display:flex;gap:0.5rem;justify-content:center;">
                            <a href="{{ route('admin.crm.lead.show', $lead) }}" style="background:#1e3a5f;color:#60a5fa;padding:0.35rem 0.65rem;border-radius:6px;font-size:0.75rem;text-decoration:none;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.crm.lead.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#450a0a;color:#ef4444;padding:0.35rem 0.65rem;border-radius:6px;border:none;cursor:pointer;font-size:0.75rem;" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:3rem;text-align:center;color:#9ca3af;">
                        <i class="fas fa-users" style="font-size:2rem;margin-bottom:0.75rem;display:block;opacity:0.3;"></i>
                        No leads yet. Leads will appear here when visitors chat with your AI assistant.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($leads->hasPages())
    <div style="margin-top:1.5rem;">{{ $leads->links() }}</div>
    @endif

</div>
@endsection
