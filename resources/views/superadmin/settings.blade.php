@extends('layouts.superadmin')
@section('title', 'Platform Settings')
@section('page_title', 'Platform Settings')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">Platform Settings</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">Configure global Xenoraa platform settings</p>
</div>

<form method="POST" action="{{ route('superadmin.settings.update') }}">
@csrf
<div style="display:grid;gap:1.5rem;">

    <div class="sa-card">
        <div class="sa-card-header"><div class="sa-card-title">General Settings</div></div>
        <div style="padding:1.5rem;display:grid;gap:1rem;">
            @foreach([
                ['key'=>'site_name','label'=>'Platform Name','placeholder'=>'Xenoraa','type'=>'text'],
                ['key'=>'site_tagline','label'=>'Tagline','placeholder'=>'Build Your Digital Identity','type'=>'text'],
                ['key'=>'site_email','label'=>'Support Email','placeholder'=>'support@xenoraa.com','type'=>'email'],
                ['key'=>'site_url','label'=>'Platform URL','placeholder'=>'https://xenoraa.com','type'=>'url'],
            ] as $field)
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">{{ $field['label'] }}</label>
                <input type="{{ $field['type'] }}" name="{{ $field['key'] }}" value="{{ $settings[$field['key']] ?? '' }}" placeholder="{{ $field['placeholder'] }}"
                    style="width:100%;background:#0a0a0a;border:1px solid #1a1a1a;border-radius:8px;padding:0.6rem 0.9rem;color:#fff;font-size:0.875rem;font-family:'Inter',sans-serif;outline:none;">
            </div>
            @endforeach
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header"><div class="sa-card-title">Pricing Settings</div></div>
        <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            @foreach([
                ['key'=>'price_starter','label'=>'Starter Plan (₹/mo)','default'=>'499'],
                ['key'=>'price_professional','label'=>'Professional Plan (₹/mo)','default'=>'999'],
                ['key'=>'price_business','label'=>'Business Pro (₹/mo)','default'=>'1999'],
            ] as $field)
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">{{ $field['label'] }}</label>
                <input type="number" name="{{ $field['key'] }}" value="{{ $settings[$field['key']] ?? $field['default'] }}"
                    style="width:100%;background:#0a0a0a;border:1px solid #1a1a1a;border-radius:8px;padding:0.6rem 0.9rem;color:#fff;font-size:0.875rem;font-family:'Inter',sans-serif;outline:none;">
            </div>
            @endforeach
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header"><div class="sa-card-title">Trial Settings</div></div>
        <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Trial Duration (days)</label>
                <input type="number" name="trial_days" value="{{ $settings['trial_days'] ?? '14' }}"
                    style="width:100%;background:#0a0a0a;border:1px solid #1a1a1a;border-radius:8px;padding:0.6rem 0.9rem;color:#fff;font-size:0.875rem;font-family:'Inter',sans-serif;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#a1a1aa;margin-bottom:0.4rem;">Max Users (0 = unlimited)</label>
                <input type="number" name="max_users" value="{{ $settings['max_users'] ?? '0' }}"
                    style="width:100%;background:#0a0a0a;border:1px solid #1a1a1a;border-radius:8px;padding:0.6rem 0.9rem;color:#fff;font-size:0.875rem;font-family:'Inter',sans-serif;outline:none;">
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:flex-end;">
        <button type="submit" style="background:#7c3aed;color:#fff;border:none;padding:0.7rem 1.5rem;border-radius:8px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
            <i class="fas fa-save" style="margin-right:0.5rem;"></i> Save Settings
        </button>
    </div>
</div>
</form>
@endsection
