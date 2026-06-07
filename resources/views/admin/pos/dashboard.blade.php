@extends('layouts.admin')

@section('title', 'POS Reports')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-chart-line text-primary me-2"></i>POS Reports</h4>
            <p class="text-muted mb-0 small">Sales analytics and performance overview</p>
        </div>
        <a href="{{ route('admin.pos.terminal') }}" class="btn btn-primary btn-sm" target="_blank">
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
                            <div class="text-muted small mb-1">Today's Orders</div>
                            <div class="h3 fw-bold mb-0">{{ $stats['today_orders'] }}</div>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-receipt text-primary fa-lg"></i>
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
                            <div class="text-muted small mb-1">Today's Sales</div>
                            <div class="h3 fw-bold mb-0 text-success">{{ $currency }}{{ number_format($stats['today_sales'], 2) }}</div>
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
                            <div class="text-muted small mb-1">This Month</div>
                            <div class="h3 fw-bold mb-0">{{ $currency }}{{ number_format($stats['month_sales'], 2) }}</div>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-calendar text-info fa-lg"></i>
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
                            <div class="text-muted small mb-1">All Time Sales</div>
                            <div class="h3 fw-bold mb-0">{{ $currency }}{{ number_format($stats['total_sales'], 2) }}</div>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-trophy text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Sales Chart --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Last 7 Days Sales</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Payment Breakdown --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Payment Methods</h6>
                </div>
                <div class="card-body">
                    @foreach($paymentBreakdown as $pm)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:32px;height:32px;background:{{ $pm->payment_method === 'cash' ? '#d1fae5' : ($pm->payment_method === 'card' ? '#dbeafe' : '#fef3c7') }}">
                                <i class="fas fa-{{ $pm->payment_method === 'cash' ? 'money-bill-wave' : ($pm->payment_method === 'card' ? 'credit-card' : 'qrcode') }}"
                                   style="color:{{ $pm->payment_method === 'cash' ? '#059669' : ($pm->payment_method === 'card' ? '#2563eb' : '#d97706') }};font-size:12px;"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">{{ strtoupper($pm->payment_method) }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $pm->count }} orders</div>
                            </div>
                        </div>
                        <div class="fw-bold">{{ $currency }}{{ number_format($pm->total, 2) }}</div>
                    </div>
                    @endforeach
                    @if($paymentBreakdown->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-pie fa-2x mb-2 d-block opacity-25"></i>
                        No data yet
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Top Products --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Top Selling Products</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Product</th>
                                <th>Qty Sold</th>
                                <th class="pe-4">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $i => $p)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                <td class="fw-bold small">{{ $p->product_name }}</td>
                                <td>{{ $p->total_qty }}</td>
                                <td class="pe-4 text-success fw-bold">{{ $currency }}{{ number_format($p->total_revenue, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No sales data yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">Recent Orders</h6>
                    <a href="{{ route('admin.pos.orders') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th class="pe-4">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td class="ps-4 fw-bold small">{{ $order->order_number }}</td>
                                <td class="small">{{ $order->customer_name ?: 'Walk-in' }}</td>
                                <td class="fw-bold text-success">{{ $currency }}{{ number_format($order->total, 2) }}</td>
                                <td class="pe-4 small text-muted">{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.date),
        datasets: [{
            label: 'Sales',
            data: chartData.map(d => d.sales),
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
@endsection
