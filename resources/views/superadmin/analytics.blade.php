@extends('layouts.superadmin')
@section('title', 'Analytics')
@section('page_title', 'Analytics')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Platform Analytics</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">User signups and platform growth for {{ now()->year }}</p>
</div>

<div class="sa-card" style="margin-bottom:1.5rem;">
    <div class="sa-card-header">
        <div class="sa-card-title">Monthly Signups — {{ now()->year }}</div>
    </div>
    <div style="padding:1.5rem;">
        @php
        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $signupData = [];
        foreach($months as $i => $m) {
            $signupData[$i+1] = 0;
        }
        foreach($signupsByMonth as $row) {
            $signupData[$row->month] = $row->count;
        }
        $maxVal = max($signupData) ?: 1;
        @endphp
        <div style="display:flex;align-items:flex-end;gap:0.5rem;height:160px;padding-bottom:1.5rem;position:relative;">
            @foreach($months as $i => $month)
            @php $val = $signupData[$i+1]; $height = max(4, round(($val/$maxVal)*140)); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.3rem;">
                <div style="font-size:0.7rem;color:#52525b;margin-bottom:0.2rem;">{{ $val > 0 ? $val : '' }}</div>
                <div style="width:100%;height:{{ $height }}px;background:{{ $val > 0 ? 'linear-gradient(180deg,#7c3aed,#a855f7)' : '#1a1a1a' }};border-radius:4px 4px 0 0;transition:height 0.5s;"></div>
                <div style="font-size:0.65rem;color:#52525b;white-space:nowrap;">{{ $month }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="sa-grid-2">
    <div class="sa-card">
        <div class="sa-card-header"><div class="sa-card-title">Signups by Month</div></div>
        <table class="sa-table">
            <thead><tr><th>Month</th><th>Signups</th><th>Growth</th></tr></thead>
            <tbody>
                @foreach($months as $i => $month)
                @php
                $val = $signupData[$i+1];
                $prev = $i > 0 ? $signupData[$i] : 0;
                $growth = $prev > 0 ? round((($val-$prev)/$prev)*100) : ($val > 0 ? 100 : 0);
                @endphp
                <tr>
                    <td style="color:#a1a1aa;">{{ $month }} {{ now()->year }}</td>
                    <td style="font-weight:700;color:#fff;">{{ $val }}</td>
                    <td>
                        @if($growth > 0)
                        <span style="color:#22c55e;font-size:0.75rem;"><i class="fas fa-arrow-up"></i> +{{ $growth }}%</span>
                        @elseif($growth < 0)
                        <span style="color:#ef4444;font-size:0.75rem;"><i class="fas fa-arrow-down"></i> {{ $growth }}%</span>
                        @else
                        <span style="color:#52525b;font-size:0.75rem;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="sa-card">
        <div class="sa-card-header"><div class="sa-card-title">Platform Summary</div></div>
        <div style="padding:1.5rem;display:grid;gap:1rem;">
            @php
            $summary = [
                ['label'=>'Total Users','value'=>\App\Models\User::count(),'icon'=>'fa-users','color'=>'#a855f7'],
                ['label'=>'Blog Posts','value'=>\Illuminate\Support\Facades\DB::table('blog_posts')->count(),'icon'=>'fa-pen-nib','color'=>'#3b82f6'],
                ['label'=>'Chat Conversations','value'=>\Illuminate\Support\Facades\DB::table('chatbot_conversations')->count(),'icon'=>'fa-comments','color'=>'#22c55e'],
                ['label'=>'CRM Leads','value'=>\Illuminate\Support\Facades\DB::table('crm_leads')->count(),'icon'=>'fa-funnel-dollar','color'=>'#f59e0b'],
                ['label'=>'Newsletter Subscribers','value'=>\Illuminate\Support\Facades\DB::table('newsletter_subscribers')->count(),'icon'=>'fa-envelope','color'=>'#06b6d4'],
                ['label'=>'Custom Domains','value'=>\App\Models\User::whereNotNull('custom_domain')->count(),'icon'=>'fa-globe','color'=>'#ec4899'],
            ];
            @endphp
            @foreach($summary as $item)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.6rem 0.75rem;background:rgba(255,255,255,0.02);border:1px solid #1a1a1a;border-radius:8px;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:28px;height:28px;background:rgba(255,255,255,0.04);border-radius:6px;display:flex;align-items:center;justify-content:center;color:{{ $item['color'] }};font-size:0.75rem;">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </div>
                    <span style="font-size:0.8rem;color:#71717a;">{{ $item['label'] }}</span>
                </div>
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#fff;">{{ $item['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
