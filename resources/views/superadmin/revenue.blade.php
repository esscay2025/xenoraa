@extends('layouts.superadmin')
@section('title', 'Revenue')
@section('page_title', 'Revenue Overview')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Revenue</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">Monthly recurring revenue and plan breakdown</p>
</div>

<div class="sa-stat-grid" style="margin-bottom:1.5rem;">
    <div class="sa-stat-card" style="border-color:rgba(124,58,237,0.3);">
        <div class="sa-stat-label">Monthly Recurring Revenue</div>
        <div class="sa-stat-value" style="color:#a855f7;">₹{{ number_format($mrr) }}</div>
        <div class="sa-stat-change sa-stat-up"><i class="fas fa-arrow-up"></i> MRR</div>
    </div>
    <div class="sa-stat-card">
        <div class="sa-stat-label">Annual Recurring Revenue</div>
        <div class="sa-stat-value">₹{{ number_format($arr) }}</div>
        <div class="sa-stat-change" style="color:#71717a;">ARR (projected)</div>
    </div>
    <div class="sa-stat-card">
        <div class="sa-stat-label">Paying Users</div>
        <div class="sa-stat-value">{{ array_sum(array_column($revenueByPlan, 'count')) }}</div>
        <div class="sa-stat-change" style="color:#71717a;">Active subscriptions</div>
    </div>
    <div class="sa-stat-card">
        <div class="sa-stat-label">Avg Revenue / User</div>
        <div class="sa-stat-value">₹{{ array_sum(array_column($revenueByPlan,'count')) > 0 ? number_format($mrr / array_sum(array_column($revenueByPlan,'count'))) : 0 }}</div>
        <div class="sa-stat-change" style="color:#71717a;">ARPU</div>
    </div>
</div>

<div class="sa-grid-2">
    <div class="sa-card">
        <div class="sa-card-header">
            <div class="sa-card-title">Revenue by Plan</div>
        </div>
        <div style="padding:1.5rem;">
            @php $totalRev = array_sum(array_column($revenueByPlan,'total')) ?: 1; @endphp
            @foreach($revenueByPlan as $plan => $data)
            @php
            $colors = ['starter'=>'#7c3aed','professional'=>'#a855f7','business'=>'#fbbf24'];
            $color = $colors[$plan] ?? '#7c3aed';
            @endphp
            <div style="margin-bottom:1.5rem;">
                <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                    <div>
                        <span style="font-size:0.875rem;font-weight:600;color:#fff;">{{ ucfirst($plan) }}</span>
                        <span style="font-size:0.75rem;color:#52525b;margin-left:0.5rem;">{{ $data['count'] }} users × ₹{{ number_format($data['price']) }}</span>
                    </div>
                    <span style="font-size:0.875rem;font-weight:700;color:{{ $color }};">₹{{ number_format($data['total']) }}</span>
                </div>
                <div style="height:8px;background:#1a1a1a;border-radius:4px;overflow:hidden;">
                    <div style="height:100%;width:{{ round(($data['total']/$totalRev)*100) }}%;background:{{ $color }};border-radius:4px;"></div>
                </div>
                <div style="font-size:0.72rem;color:#3f3f46;margin-top:0.3rem;">{{ round(($data['total']/$totalRev)*100) }}% of total revenue</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <div class="sa-card-title">Revenue Summary</div>
        </div>
        <div style="padding:1.5rem;">
            <div style="display:grid;gap:1rem;">
                @foreach([
                    ['label'=>'Daily Revenue (est.)','value'=>'₹'.number_format($mrr/30),'icon'=>'fa-calendar-day','color'=>'#22c55e'],
                    ['label'=>'Weekly Revenue (est.)','value'=>'₹'.number_format($mrr/4),'icon'=>'fa-calendar-week','color'=>'#3b82f6'],
                    ['label'=>'Monthly Revenue','value'=>'₹'.number_format($mrr),'icon'=>'fa-calendar-alt','color'=>'#a855f7'],
                    ['label'=>'Annual Revenue (proj.)','value'=>'₹'.number_format($arr),'icon'=>'fa-calendar','color'=>'#fbbf24'],
                ] as $item)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem;background:rgba(255,255,255,0.02);border:1px solid #1a1a1a;border-radius:8px;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:32px;height:32px;background:rgba(255,255,255,0.04);border-radius:8px;display:flex;align-items:center;justify-content:center;color:{{ $item['color'] }};font-size:0.8rem;">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <span style="font-size:0.825rem;color:#71717a;">{{ $item['label'] }}</span>
                    </div>
                    <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#fff;">{{ $item['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
