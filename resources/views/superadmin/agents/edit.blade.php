@extends('layouts.superadmin')
@section('title', 'Edit Agent')
@section('content')
<div class="sa-content" style="max-width:760px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('superadmin.agents.show', $agent->id) }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to {{ $agent->user->name }}</a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Edit Agent</h1>
    </div>

    @if(session('success'))<div style="background:#22c55e22;border:1px solid #22c55e;color:#86efac;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('superadmin.agents.update', $agent->id) }}">
        @csrf @method('PUT')
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title">Agent Details</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><label class="sa-label">Full Name</label><input type="text" name="name" value="{{ old('name', $agent->user->name) }}" class="sa-input"></div>
                <div><label class="sa-label">Phone</label><input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" class="sa-input"></div>
                <div><label class="sa-label">Company Name</label><input type="text" name="company_name" value="{{ old('company_name', $agent->company_name) }}" class="sa-input"></div>
                <div><label class="sa-label">City</label><input type="text" name="city" value="{{ old('city', $agent->city) }}" class="sa-input"></div>
                <div><label class="sa-label">State</label><input type="text" name="state" value="{{ old('state', $agent->state) }}" class="sa-input"></div>
                <div>
                    <label class="sa-label">Status</label>
                    <select name="status" class="sa-input">
                        @foreach(['active','inactive','suspended'] as $s)
                        <option value="{{ $s }}" {{ old('status',$agent->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sa-label">Commission Rate (%)</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $agent->commission_rate) }}" min="0" max="100" step="0.5" class="sa-input">
                </div>
            </div>
        </div>
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title">Banking Details</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><label class="sa-label">PAN Number</label><input type="text" name="pan_number" value="{{ old('pan_number', $agent->pan_number) }}" class="sa-input"></div>
                <div><label class="sa-label">GST Number</label><input type="text" name="gst_number" value="{{ old('gst_number', $agent->gst_number) }}" class="sa-input"></div>
                <div><label class="sa-label">Bank Name</label><input type="text" name="bank_name" value="{{ old('bank_name', $agent->bank_name) }}" class="sa-input"></div>
                <div><label class="sa-label">Account Number</label><input type="text" name="bank_account_no" value="{{ old('bank_account_no', $agent->bank_account_no) }}" class="sa-input"></div>
                <div><label class="sa-label">IFSC Code</label><input type="text" name="bank_ifsc" value="{{ old('bank_ifsc', $agent->bank_ifsc) }}" class="sa-input"></div>
            </div>
        </div>
        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <a href="{{ route('superadmin.agents.show', $agent->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.75rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Cancel</a>
            <button type="submit" class="sa-btn-primary">Save Changes</button>
        </div>
    </form>
</div>
<style>
.sa-input{width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box;}
.sa-input:focus{border-color:#7c3aed;}
.sa-label{display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;}
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
@endsection
