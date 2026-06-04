@extends('layouts.superadmin')
@section('title', 'Activity Logs')
@section('page_title', 'Activity Logs')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Activity Logs</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">System and user activity across the platform</p>
</div>

<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">Recent Activity</div>
    </div>
    @if($logs->isEmpty())
    <div style="padding:3rem;text-align:center;color:#3f3f46;">
        <i class="fas fa-terminal" style="font-size:2rem;margin-bottom:1rem;display:block;opacity:0.2;"></i>
        <p>No activity logs yet. Logs will appear here as users interact with the platform.</p>
    </div>
    @else
    <table class="sa-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="color:#a1a1aa;font-size:0.825rem;">{{ $log->user_name ?? 'System' }}</td>
                <td><span class="sa-badge sa-badge-starter">{{ $log->action ?? 'N/A' }}</span></td>
                <td style="color:#71717a;font-size:0.8rem;">{{ $log->description ?? '—' }}</td>
                <td style="font-size:0.75rem;color:#52525b;">{{ $log->ip_address ?? '—' }}</td>
                <td style="font-size:0.75rem;color:#52525b;">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($logs->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #1a1a1a;">{{ $logs->links() }}</div>
    @endif
    @endif
</div>
@endsection
