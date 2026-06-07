@extends('layouts.admin')

@section('title', 'POS Settings')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-cog text-primary me-2"></i>POS Settings</h4>
            <p class="text-muted mb-0 small">Configure your Point of Sale preferences</p>
        </div>
        <a href="{{ route('admin.pos.terminal') }}" class="btn btn-primary btn-sm" target="_blank">
            <i class="fas fa-cash-register me-1"></i> Open POS Terminal
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">General Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pos.settings.save') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="{{ $currency }}" maxlength="5" placeholder="₹">
                            <div class="form-text">Symbol shown on receipts and POS screen (e.g. ₹, $, €)</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tax Rate (%)</label>
                            <div class="input-group">
                                <input type="number" name="pos_tax_rate" class="form-control" value="{{ $taxRate }}" min="0" max="100" step="0.01" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Applied to all POS orders. Set to 0 for no tax.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Receipt Footer Message</label>
                            <textarea name="pos_receipt_footer" class="form-control" rows="2" placeholder="Thank you for shopping with us!">{{ $receiptFooter }}</textarea>
                            <div class="form-text">Printed at the bottom of every receipt</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Store Manager Access</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        To give a staff member POS-only access, create a role with only the <strong>Point of Sale</strong> module enabled, then assign it to the user.
                    </p>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary btn-sm mb-2 d-block">
                        <i class="fas fa-user-shield me-1"></i> Manage Roles
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm d-block">
                        <i class="fas fa-users me-1"></i> Manage Users
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Keyboard Shortcuts</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><td><kbd>F2</kbd></td><td class="text-muted small">Focus search bar</td></tr>
                            <tr><td><kbd>F4</kbd></td><td class="text-muted small">Place order</td></tr>
                            <tr><td><kbd>Esc</kbd></td><td class="text-muted small">Close modals</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
