@extends('layouts.superadmin')
@section('title', 'Email Templates')
@section('page_title', 'Email Templates')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Email Templates</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">System email templates sent from support@xenoraa.com</p>
</div>

@php
$templates = [
    ['name'=>'Welcome Email','trigger'=>'On Registration','from'=>'support@xenoraa.com','status'=>'active','icon'=>'fa-hand-wave','color'=>'#22c55e'],
    ['name'=>'Forgot Password','trigger'=>'Password Reset Request','from'=>'support@xenoraa.com','status'=>'active','icon'=>'fa-key','color'=>'#3b82f6'],
    ['name'=>'Trial Expiry Reminder','trigger'=>'3 days before trial ends','from'=>'support@xenoraa.com','status'=>'active','icon'=>'fa-clock','color'=>'#f59e0b'],
    ['name'=>'Subscription Confirmation','trigger'=>'On Payment Success','from'=>'support@xenoraa.com','status'=>'active','icon'=>'fa-credit-card','color'=>'#a855f7'],
    ['name'=>'Account Suspended','trigger'=>'On Suspension','from'=>'support@xenoraa.com','status'=>'active','icon'=>'fa-ban','color'=>'#ef4444'],
    ['name'=>'Newsletter Confirmation','trigger'=>'On Newsletter Signup','from'=>'support@xenoraa.com','status'=>'active','icon'=>'fa-envelope','color'=>'#06b6d4'],
];
@endphp

<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">System Email Templates</div>
        <span style="font-size:0.75rem;color:#52525b;">All emails sent from support@xenoraa.com</span>
    </div>
    <table class="sa-table">
        <thead>
            <tr>
                <th>Template</th>
                <th>Trigger</th>
                <th>From</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $t)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:32px;height:32px;background:rgba(255,255,255,0.04);border-radius:8px;display:flex;align-items:center;justify-content:center;color:{{ $t['color'] }};font-size:0.8rem;">
                            <i class="fas {{ $t['icon'] }}"></i>
                        </div>
                        <span style="font-weight:600;color:#fff;font-size:0.825rem;">{{ $t['name'] }}</span>
                    </div>
                </td>
                <td style="color:#71717a;font-size:0.8rem;">{{ $t['trigger'] }}</td>
                <td style="color:#7c3aed;font-size:0.8rem;">{{ $t['from'] }}</td>
                <td><span class="sa-badge sa-badge-active"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
