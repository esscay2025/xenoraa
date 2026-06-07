@extends('layouts.admin')

@section('title', 'POS Sessions')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-layer-group text-primary me-2"></i>POS Sessions</h4>
            <p class="text-muted mb-0 small">Track all cash register sessions and daily summaries</p>
        </div>
        <a href="{{ route('admin.pos.terminal') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-cash-register me-1"></i> Open POS Terminal
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Sessions</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_sessions'] }}</div>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-layer-group text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Revenue</div>
                            <div class="h4 fw-bold mb-0">{{ $currency }}{{ number_format($stats['total_revenue'], 2) }}</div>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-rupee-sign text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Orders</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_orders'] }}</div>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-receipt text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Active Session</div>
                            <div class="h4 fw-bold mb-0 {{ $activeSession ? 'text-success' : 'text-danger' }}">
                                {{ $activeSession ? 'Open' : 'None' }}
                            </div>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-circle text-{{ $activeSession ? 'success' : 'danger' }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sessions Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">All Sessions</h6>
                <div class="d-flex gap-2">
                    <input type="month" id="filterMonth" class="form-control form-control-sm" value="{{ date('Y-m') }}" onchange="filterSessions()">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Session #</th>
                            <th>Cashier</th>
                            <th>Opened At</th>
                            <th>Closed At</th>
                            <th>Opening Cash</th>
                            <th>Closing Cash</th>
                            <th>Orders</th>
                            <th>Total Sales</th>
                            <th>Status</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $sess)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $sess->session_number }}</td>
                            <td>{{ $sess->cashier->name ?? '—' }}</td>
                            <td class="small">{{ $sess->opened_at->format('d M Y, h:i A') }}</td>
                            <td class="small">{{ $sess->closed_at ? $sess->closed_at->format('d M Y, h:i A') : '—' }}</td>
                            <td>{{ $currency }}{{ number_format($sess->opening_cash, 2) }}</td>
                            <td>{{ $sess->closing_cash !== null ? $currency.number_format($sess->closing_cash, 2) : '—' }}</td>
                            <td>{{ $sess->orders_count ?? $sess->posOrders()->count() }}</td>
                            <td class="fw-bold text-success">{{ $currency }}{{ number_format($sess->total_sales ?? $sess->posOrders()->sum('total'), 2) }}</td>
                            <td>
                                @if($sess->status === 'open')
                                <span class="badge bg-success-subtle text-success">Open</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary">Closed</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <a href="{{ route('admin.pos.session-detail', $sess->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-layer-group fa-2x mb-3 d-block opacity-25"></i>
                                No sessions found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sessions->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
