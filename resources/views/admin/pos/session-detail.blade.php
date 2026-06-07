@extends('layouts.admin')

@section('title', 'Session Detail')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-layer-group text-primary me-2"></i>Session {{ $session->session_number }}</h4>
            <p class="text-muted mb-0 small">Opened by {{ $session->cashier->name ?? 'Unknown' }} on {{ $session->opened_at->format('d M Y, h:i A') }}</p>
        </div>
        <a href="{{ route('admin.pos.sessions') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Sessions
        </a>
    </div>

    {{-- Session Info Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Status</div>
                    <span class="badge bg-{{ $session->status === 'open' ? 'success' : 'secondary' }} fs-6">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Opening Cash</div>
                    <div class="h6 fw-bold mb-0">{{ $currency }}{{ number_format($session->opening_cash, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Closing Cash</div>
                    <div class="h6 fw-bold mb-0">{{ $session->closing_cash !== null ? $currency.number_format($session->closing_cash, 2) : '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Orders</div>
                    <div class="h6 fw-bold mb-0">{{ $orders->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Sales</div>
                    <div class="h6 fw-bold mb-0 text-success">{{ $currency }}{{ number_format($totalSales, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Duration</div>
                    <div class="h6 fw-bold mb-0">
                        {{ $session->closed_at ? $session->opened_at->diffForHumans($session->closed_at, true) : $session->opened_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders in this session --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">Orders in this Session</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="{{ $order->status === 'void' ? 'table-danger opacity-75' : '' }}">
                            <td class="ps-4 fw-bold">{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name ?: 'Walk-in' }}</td>
                            <td>{{ $order->items->count() }}</td>
                            <td class="fw-bold">{{ $currency }}{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_method === 'cash' ? 'success' : ($order->payment_method === 'card' ? 'primary' : 'warning') }}-subtle text-{{ $order->payment_method === 'cash' ? 'success' : ($order->payment_method === 'card' ? 'primary' : 'warning') }}">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'danger' }}-subtle text-{{ $order->status === 'completed' ? 'success' : 'danger' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="small">{{ $order->created_at->format('h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No orders in this session</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
