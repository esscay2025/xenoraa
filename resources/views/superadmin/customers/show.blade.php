@extends('layouts.superadmin')
@section('title', $customer->name)
@section('content')
<div class="sa-content">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('superadmin.customers.index') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Customers</a>
    </div>

    @if(session('success'))<div style="background:#22c55e22;border:1px solid #22c55e;color:#86efac;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('success') }}</div>@endif
    @if(session('error'))<div style="background:#ef444422;border:1px solid #ef4444;color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('error') }}</div>@endif

    <div style="display:grid;grid-template-columns:340px 1fr;gap:1.5rem;align-items:start;">

        {{-- Profile Card --}}
        <div>
            <div class="sa-card" style="margin-bottom:1.5rem;">
                <div style="padding:2rem;text-align:center;border-bottom:1px solid #27272a;">
                    <div style="width:80px;height:80px;border-radius:50%;background:#7c3aed;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#fff;margin:0 auto 1rem;overflow:hidden;">
                        @if($customer->avatar)<img src="{{ asset('storage/'.$customer->avatar) }}" style="width:100%;height:100%;object-fit:cover;">@else{{ strtoupper(substr($customer->name,0,1)) }}@endif
                    </div>
                    <div style="font-size:1.1rem;font-weight:800;color:#fff;">{{ $customer->name }}</div>
                    <div style="font-size:0.8rem;color:#71717a;margin-top:0.25rem;">{{ $customer->email }}</div>
                    @if($customer->username)
                    <a href="{{ $customer->getProfileUrl() }}" target="_blank" style="display:inline-block;margin-top:0.75rem;background:#7c3aed22;color:#a78bfa;padding:0.3rem 0.85rem;border-radius:20px;font-size:0.75rem;text-decoration:none;">@{{ $customer->username }} <i class="fas fa-external-link-alt" style="font-size:0.65rem;"></i></a>
                    @endif
                </div>
                <div style="padding:1.25rem;">
                    @php
                        $planColors = ['starter'=>'#3b82f6','professional'=>'#8b5cf6','business'=>'#f59e0b'];
                        $statusColors = ['active'=>'#22c55e','inactive'=>'#f59e0b','suspended'=>'#ef4444'];
                        $plan = $customer->plan ?? 'starter';
                        $status = $customer->status ?? 'active';
                    @endphp
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Plan</span>
                        <span style="background:{{ $planColors[$plan]??'#3b82f6' }}22;color:{{ $planColors[$plan]??'#3b82f6' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $plan }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Status</span>
                        <span style="background:{{ $statusColors[$status]??'#22c55e' }}22;color:{{ $statusColors[$status]??'#22c55e' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $status }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Profession</span>
                        <span style="font-size:0.8rem;color:#fff;text-transform:capitalize;">{{ $customer->profession ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Plan Expires</span>
                        <span style="font-size:0.8rem;color:{{ $customer->plan_expires_at && $customer->plan_expires_at->isPast() ? '#ef4444' : '#fff' }};">
                            {{ $customer->plan_expires_at ? $customer->plan_expires_at->format('d M Y') : '—' }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Joined</span>
                        <span style="font-size:0.8rem;color:#fff;">{{ $customer->created_at->format('d M Y') }}</span>
                    </div>
                    @if($customer->phone)
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Phone</span>
                        <span style="font-size:0.8rem;color:#fff;">{{ $customer->phone }}</span>
                    </div>
                    @endif
                </div>
                <div style="padding:1rem 1.25rem;border-top:1px solid #27272a;display:flex;gap:0.5rem;flex-wrap:wrap;">
                    @if(auth()->user()->hasSaPermission('customers.edit'))
                    <a href="{{ route('superadmin.customers.edit', $customer->id) }}" style="flex:1;text-align:center;background:#27272a;color:#a1a1aa;padding:0.5rem;border-radius:8px;font-size:0.8rem;text-decoration:none;"><i class="fas fa-edit"></i> Edit</a>
                    @endif
                    @if(auth()->user()->hasSaPermission('customers.impersonate'))
                    <a href="{{ route('superadmin.users.impersonate', $customer->id) }}" style="flex:1;text-align:center;background:#f59e0b22;color:#f59e0b;padding:0.5rem;border-radius:8px;font-size:0.8rem;text-decoration:none;"><i class="fas fa-user-secret"></i> Login As</a>
                    @endif
                </div>
            </div>

            {{-- Current Subscription --}}
            @if($subscription)
            <div class="sa-card">
                <div class="sa-card-header"><span class="sa-card-title">Current Subscription</span></div>
                <div style="padding:1.25rem;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Agent</span>
                        <span style="font-size:0.8rem;color:#fff;">{{ $subscription->agent->user->name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Plan Price</span>
                        <span style="font-size:0.8rem;color:#fff;">₹{{ number_format($subscription->plan_price, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:#71717a;">Commission</span>
                        <span style="font-size:0.8rem;color:#22c55e;">₹{{ number_format($subscription->commission_amount, 2) }} ({{ $subscription->commission_rate }}%)</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:0.75rem;color:#71717a;">Commission Status</span>
                        @php $cs = $subscription->commission_status; $csColors = ['pending'=>'#f59e0b','approved'=>'#3b82f6','paid'=>'#22c55e','cancelled'=>'#ef4444']; @endphp
                        <span style="background:{{ $csColors[$cs]??'#f59e0b' }}22;color:{{ $csColors[$cs]??'#f59e0b' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $cs }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div>
            {{-- Assign Subscription --}}
            @if(auth()->user()->hasSaPermission('subscriptions.assign'))
            <div class="sa-card" style="margin-bottom:1.5rem;">
                <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-credit-card" style="color:#7c3aed;margin-right:0.5rem;"></i> Assign / Renew Subscription</span></div>
                <form method="POST" action="{{ route('superadmin.customers.assign-subscription', $customer->id) }}" style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:1rem;align-items:end;">
                    @csrf
                    <div>
                        <label style="display:block;font-size:0.72rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.4rem;">Plan</label>
                        <select name="plan" class="sa-input">
                            <option value="starter">Starter</option>
                            <option value="professional">Professional</option>
                            <option value="business">Business</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.72rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.4rem;">Duration (months)</label>
                        <input type="number" name="duration_months" value="1" min="1" max="24" class="sa-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.72rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.4rem;">Agent (optional)</label>
                        <select name="agent_id" class="sa-input">
                            <option value="">No agent</option>
                            @foreach(\App\Models\Agent::with('user')->where('status','active')->get() as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->user->name }} ({{ $ag->agent_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="sa-btn-primary" style="white-space:nowrap;">Assign</button>
                </form>
            </div>
            @endif

            {{-- Site Settings Summary --}}
            <div class="sa-card">
                <div class="sa-card-header">
                    <span class="sa-card-title"><i class="fas fa-globe" style="color:#7c3aed;margin-right:0.5rem;"></i> Site Information</span>
                    @if(auth()->user()->hasSaPermission('customers.impersonate'))
                    <a href="{{ route('superadmin.users.impersonate', $customer->id) }}" style="font-size:0.75rem;color:#a78bfa;text-decoration:none;">Login as customer to manage site →</a>
                    @endif
                </div>
                <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div style="background:#111;border:1px solid #27272a;border-radius:10px;padding:1rem;">
                        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Site Title</div>
                        <div style="font-size:0.9rem;color:#fff;font-weight:600;">{{ $customer->site_title ?? $customer->name }}</div>
                    </div>
                    <div style="background:#111;border:1px solid #27272a;border-radius:10px;padding:1rem;">
                        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Custom Domain</div>
                        <div style="font-size:0.9rem;color:#fff;font-weight:600;">{{ $customer->custom_domain ?? 'Not set' }}</div>
                    </div>
                    <div style="background:#111;border:1px solid #27272a;border-radius:10px;padding:1rem;">
                        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Profile Template</div>
                        <div style="font-size:0.9rem;color:#fff;font-weight:600;text-transform:capitalize;">{{ $customer->profile_template ?? 'default' }}</div>
                    </div>
                    <div style="background:#111;border:1px solid #27272a;border-radius:10px;padding:1rem;">
                        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Onboarding</div>
                        <div style="font-size:0.9rem;font-weight:600;color:{{ $customer->onboarding_completed ? '#22c55e' : '#f59e0b' }};">{{ $customer->onboarding_completed ? 'Completed' : 'Pending' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sa-input { width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box; }
.sa-input:focus { border-color:#7c3aed; }
.sa-btn-primary { background:#7c3aed;color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none; }
.sa-btn-primary:hover { background:#6d28d9; }
</style>
@endsection
