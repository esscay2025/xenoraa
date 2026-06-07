<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosSession;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    // ─── Tenant Helper ────────────────────────────────────────────────────────
    private function tenantId(): int
    {
        $user = auth()->user();
        // If this user is a sub-user (store manager), use their tenant_owner_id
        if ($user->tenant_owner_id) {
            return (int) $user->tenant_owner_id;
        }
        return (int) $user->id;
    }

    // ─── Main POS Terminal ────────────────────────────────────────────────────
    public function terminal()
    {
        $tid      = $this->tenantId();
        $cashier  = auth()->user();

        // Get or create open session for this cashier
        $session = PosSession::where('tenant_id', $tid)
            ->where('cashier_id', $cashier->id)
            ->where('status', 'open')
            ->latest()
            ->first();

        // Load categories and products for the product grid
        $categories = ProductCategory::where('user_id', $tid)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::where('user_id', $tid)
            ->where('is_active', true)
            ->whereIn('stock_status', ['in_stock', 'on_backorder'])
            ->with('category')
            ->orderBy('name')
            ->get();

        // Tenant info for receipt header
        $tenantUser = User::find($tid);
        $storeName  = \App\Models\SiteSetting::getValueForTenant($tid, 'site_name', $tenantUser->name ?? 'Store');
        $storePhone = \App\Models\SiteSetting::getValueForTenant($tid, 'contact_phone', '');
        $storeEmail = \App\Models\SiteSetting::getValueForTenant($tid, 'contact_email', $tenantUser->email ?? '');
        $storeAddress = \App\Models\SiteSetting::getValueForTenant($tid, 'contact_address', '');
        $storeLogo  = \App\Models\SiteSetting::getValueForTenant($tid, 'logo', '');
        $taxRate    = (float) \App\Models\SiteSetting::getValueForTenant($tid, 'pos_tax_rate', '0');
        $currency   = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');

        // Today's stats
        $todayStats = [
            'orders'   => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', today())->count(),
            'sales'    => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', today())->sum('total'),
            'discount' => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', today())->sum('discount_amount'),
        ];

        return view('admin.pos.terminal', compact(
            'session', 'categories', 'products',
            'storeName', 'storePhone', 'storeEmail', 'storeAddress', 'storeLogo',
            'taxRate', 'currency', 'cashier', 'todayStats'
        ));
    }

    // ─── Open Session ─────────────────────────────────────────────────────────
    public function openSession(Request $request)
    {
        $request->validate(['opening_cash' => 'required|numeric|min:0']);
        $tid     = $this->tenantId();
        $cashier = auth()->user();

        // Check no open session already
        $existing = PosSession::where('tenant_id', $tid)
            ->where('cashier_id', $cashier->id)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'A session is already open.']);
        }

        $session = PosSession::create([
            'tenant_id'    => $tid,
            'cashier_id'   => $cashier->id,
            'session_number' => PosSession::generateNumber(),
            'status'       => 'open',
            'opening_cash' => $request->opening_cash,
            'opened_at'    => now(),
        ]);

        return response()->json([
            'success'    => true,
            'session_id' => $session->id,
            'session_number' => $session->session_number,
            'message'    => 'Session opened successfully.',
        ]);
    }

    // ─── Close Session ────────────────────────────────────────────────────────
    public function closeSession(Request $request, PosSession $session)
    {
        abort_if($session->tenant_id !== $this->tenantId(), 403);
        abort_if($session->cashier_id !== auth()->id(), 403);

        $request->validate(['closing_cash' => 'required|numeric|min:0', 'notes' => 'nullable|string']);

        // Calculate expected cash
        $cashSales = PosOrder::where('session_id', $session->id)
            ->where('status', 'completed')
            ->sum('cash_paid');

        $expectedCash  = $session->opening_cash + $cashSales;
        $closingCash   = (float) $request->closing_cash;
        $cashDifference = $closingCash - $expectedCash;

        $totalOrders = PosOrder::where('session_id', $session->id)->where('status', 'completed')->count();
        $totalSales  = PosOrder::where('session_id', $session->id)->where('status', 'completed')->sum('total');
        $totalDiscount = PosOrder::where('session_id', $session->id)->where('status', 'completed')->sum('discount_amount');
        $totalTax    = PosOrder::where('session_id', $session->id)->where('status', 'completed')->sum('tax_amount');

        $session->update([
            'status'          => 'closed',
            'closing_cash'    => $closingCash,
            'expected_cash'   => $expectedCash,
            'cash_difference' => $cashDifference,
            'total_orders'    => $totalOrders,
            'total_sales'     => $totalSales,
            'total_discount'  => $totalDiscount,
            'total_tax'       => $totalTax,
            'notes'           => $request->notes,
            'closed_at'       => now(),
        ]);

        return response()->json([
            'success'         => true,
            'message'         => 'Session closed.',
            'summary'         => [
                'total_orders'    => $totalOrders,
                'total_sales'     => number_format($totalSales, 2),
                'total_discount'  => number_format($totalDiscount, 2),
                'total_tax'       => number_format($totalTax, 2),
                'expected_cash'   => number_format($expectedCash, 2),
                'closing_cash'    => number_format($closingCash, 2),
                'cash_difference' => number_format($cashDifference, 2),
            ],
        ]);
    }

    // ─── Search Products (AJAX) ───────────────────────────────────────────────
    public function searchProducts(Request $request)
    {
        $tid   = $this->tenantId();
        $query = $request->get('q', '');
        $catId = $request->get('category_id');

        $products = Product::where('user_id', $tid)
            ->where('is_active', true)
            ->whereIn('stock_status', ['in_stock', 'on_backorder'])
            ->when($query, fn($q) => $q->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            }))
            ->when($catId, fn($q) => $q->where('category_id', $catId))
            ->with('category')
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'name'          => $p->name,
                'sku'           => $p->sku,
                'price'         => (float) $p->price,
                'sale_price'    => $p->sale_price ? (float) $p->sale_price : null,
                'effective_price' => (float) $p->effective_price,
                'stock_quantity'=> $p->stock_quantity,
                'stock_status'  => $p->stock_status,
                'manage_stock'  => $p->manage_stock,
                'featured_image'=> $p->featured_image ? asset('storage/' . $p->featured_image) : null,
                'category'      => $p->category?->name,
            ]);

        return response()->json(['products' => $products]);
    }

    // ─── Place Order ──────────────────────────────────────────────────────────
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method'     => 'required|in:cash,card,upi,split',
            'amount_paid'        => 'required|numeric|min:0',
            'session_id'         => 'nullable|integer',
        ]);

        $tid     = $this->tenantId();
        $cashier = auth()->user();

        DB::beginTransaction();
        try {
            $taxRate       = (float) \App\Models\SiteSetting::getValueForTenant($tid, 'pos_tax_rate', '0');
            $discountType  = $request->get('discount_type', 'fixed');
            $discountValue = (float) $request->get('discount_value', 0);

            // Calculate subtotal
            $subtotal = 0;
            $itemsData = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $unitPrice = (float) $item['unit_price'];
                $qty       = (int) $item['quantity'];
                $lineTotal = round($unitPrice * $qty, 2);
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id'   => $item['product_id'],
                    'product_name' => $product ? $product->name : ($item['product_name'] ?? 'Unknown'),
                    'product_sku'  => $product ? $product->sku : null,
                    'unit_price'   => $unitPrice,
                    'sale_price'   => $product?->sale_price,
                    'quantity'     => $qty,
                    'line_total'   => $lineTotal,
                ];
            }

            // Discount
            $discountAmount = 0;
            if ($discountType === 'percent') {
                $discountAmount = round($subtotal * ($discountValue / 100), 2);
            } else {
                $discountAmount = min($discountValue, $subtotal);
            }

            $afterDiscount = $subtotal - $discountAmount;
            $taxAmount     = round($afterDiscount * ($taxRate / 100), 2);
            $total         = round($afterDiscount + $taxAmount, 2);
            $amountPaid    = (float) $request->amount_paid;
            $changeDue     = max(0, round($amountPaid - $total, 2));

            // Payment split
            $cashPaid = $cardPaid = $upiPaid = 0;
            if ($request->payment_method === 'cash') {
                $cashPaid = $amountPaid;
            } elseif ($request->payment_method === 'card') {
                $cardPaid = $total;
            } elseif ($request->payment_method === 'upi') {
                $upiPaid = $total;
            } elseif ($request->payment_method === 'split') {
                $cashPaid = (float) $request->get('cash_paid', 0);
                $cardPaid = (float) $request->get('card_paid', 0);
                $upiPaid  = (float) $request->get('upi_paid', 0);
            }

            $order = PosOrder::create([
                'tenant_id'       => $tid,
                'session_id'      => $request->session_id,
                'cashier_id'      => $cashier->id,
                'order_number'    => PosOrder::generateNumber(),
                'status'          => 'completed',
                'customer_name'   => $request->get('customer_name'),
                'customer_phone'  => $request->get('customer_phone'),
                'customer_email'  => $request->get('customer_email'),
                'subtotal'        => $subtotal,
                'discount_amount' => $discountAmount,
                'discount_type'   => $discountType,
                'discount_value'  => $discountValue,
                'tax_rate'        => $taxRate,
                'tax_amount'      => $taxAmount,
                'total'           => $total,
                'amount_paid'     => $amountPaid,
                'change_due'      => $changeDue,
                'payment_method'  => $request->payment_method,
                'cash_paid'       => $cashPaid,
                'card_paid'       => $cardPaid,
                'upi_paid'        => $upiPaid,
                'upi_reference'   => $request->get('upi_reference'),
                'card_reference'  => $request->get('card_reference'),
                'notes'           => $request->get('notes'),
            ]);

            // Create order items and deduct stock
            foreach ($itemsData as $item) {
                PosOrderItem::create(array_merge($item, [
                    'pos_order_id' => $order->id,
                    'tax_rate'     => $taxRate,
                    'tax_amount'   => round($item['line_total'] * ($taxRate / 100), 2),
                    'discount_amount' => 0,
                ]));

                // Deduct stock if managed
                if ($item['product_id']) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->manage_stock) {
                        $newQty = max(0, $product->stock_quantity - $item['quantity']);
                        $product->update([
                            'stock_quantity' => $newQty,
                            'stock_status'   => $newQty <= 0 ? 'out_of_stock' : 'in_stock',
                        ]);
                    }
                }
            }

            // Update session totals
            if ($request->session_id) {
                $session = PosSession::find($request->session_id);
                if ($session) {
                    $session->increment('total_orders');
                    $session->increment('total_sales', $total);
                    $session->increment('total_discount', $discountAmount);
                    $session->increment('total_tax', $taxAmount);
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'total'        => number_format($total, 2),
                'change_due'   => number_format($changeDue, 2),
                'message'      => 'Order placed successfully.',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('POS order failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()], 500);
        }
    }

    // ─── Get Order for Receipt (AJAX) ─────────────────────────────────────────
    public function getOrder(PosOrder $order)
    {
        abort_if($order->tenant_id !== $this->tenantId(), 403);
        $order->load('items', 'cashier');

        $tid = $this->tenantId();
        $tenantUser = User::find($tid);
        $storeName    = \App\Models\SiteSetting::getValueForTenant($tid, 'site_name', $tenantUser->name ?? 'Store');
        $storePhone   = \App\Models\SiteSetting::getValueForTenant($tid, 'contact_phone', '');
        $storeAddress = \App\Models\SiteSetting::getValueForTenant($tid, 'contact_address', '');
        $currency     = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');

        return response()->json([
            'order' => [
                'id'             => $order->id,
                'order_number'   => $order->order_number,
                'status'         => $order->status,
                'customer_name'  => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'cashier'        => $order->cashier?->name,
                'subtotal'       => number_format($order->subtotal, 2),
                'discount_amount'=> number_format($order->discount_amount, 2),
                'tax_amount'     => number_format($order->tax_amount, 2),
                'total'          => number_format($order->total, 2),
                'amount_paid'    => number_format($order->amount_paid, 2),
                'change_due'     => number_format($order->change_due, 2),
                'payment_method' => $order->payment_method,
                'upi_reference'  => $order->upi_reference,
                'card_reference' => $order->card_reference,
                'created_at'     => $order->created_at->format('d M Y, h:i A'),
                'items'          => $order->items->map(fn($i) => [
                    'product_name' => $i->product_name,
                    'product_sku'  => $i->product_sku,
                    'quantity'     => $i->quantity,
                    'unit_price'   => number_format($i->unit_price, 2),
                    'line_total'   => number_format($i->line_total, 2),
                ]),
            ],
            'store' => [
                'name'     => $storeName,
                'phone'    => $storePhone,
                'address'  => $storeAddress,
                'currency' => $currency,
            ],
        ]);
    }

    // ─── Void / Refund Order ──────────────────────────────────────────────────
    public function voidOrder(Request $request, PosOrder $order)
    {
        abort_if($order->tenant_id !== $this->tenantId(), 403);
        $request->validate(['reason' => 'required|string|max:255']);

        $order->update([
            'status'        => 'void',
            'refund_reason' => $request->reason,
        ]);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product_id) {
                $product = Product::find($item->product_id);
                if ($product && $product->manage_stock) {
                    $product->increment('stock_quantity', $item->quantity);
                    $product->update(['stock_status' => 'in_stock']);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Order voided and stock restored.']);
    }

    // ─── Order History ────────────────────────────────────────────────────────
    public function orders(Request $request)
    {
        $tid    = $this->tenantId();
        $date   = $request->date ?? date('Y-m-d');
        $query  = PosOrder::where('tenant_id', $tid)->with(['items', 'cashier'])->latest();

        if ($date) {
            $query->whereDate('created_at', $date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment') || $request->filled('payment_method')) {
            $query->where('payment_method', $request->payment ?? $request->payment_method);
        }
        if ($request->filled('q') || $request->filled('search')) {
            $s = $request->q ?? $request->search;
            $query->where(fn($q) => $q->where('order_number', 'like', "%$s%")
                ->orWhere('customer_name', 'like', "%$s%")
                ->orWhere('customer_phone', 'like', "%$s%"));
        }

        $currency = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');

        // JSON response for POS terminal AJAX
        if ($request->boolean('json') || $request->wantsJson()) {
            $orders = $query->get();
            return response()->json([
                'orders'   => $orders->map(fn($o) => [
                    'id'             => $o->id,
                    'order_number'   => $o->order_number,
                    'customer_name'  => $o->customer_name,
                    'customer_phone' => $o->customer_phone,
                    'items_count'    => $o->items->count(),
                    'total'          => number_format($o->total, 2),
                    'payment_method' => $o->payment_method,
                    'status'         => $o->status,
                    'created_at'     => $o->created_at->format('h:i A'),
                ]),
                'currency' => $currency,
            ]);
        }

        $orders = $query->paginate(25)->appends($request->all());

        $summary = [
            'count'    => PosOrder::where('tenant_id', $tid)->whereDate('created_at', $date)->where('status', 'completed')->count(),
            'total'    => PosOrder::where('tenant_id', $tid)->whereDate('created_at', $date)->where('status', 'completed')->sum('total'),
            'discount' => PosOrder::where('tenant_id', $tid)->whereDate('created_at', $date)->where('status', 'completed')->sum('discount_amount'),
        ];

        return view('admin.pos.orders', compact('orders', 'currency', 'summary'));
    }

    // ─── Sessions History ─────────────────────────────────────────────────────
    public function sessions(Request $request)
    {
        $tid      = $this->tenantId();
        $sessions = PosSession::where('tenant_id', $tid)
            ->withCount(['orders as orders_count'])
            ->with('cashier')
            ->latest()
            ->paginate(20);

        $activeSession = PosSession::where('tenant_id', $tid)
            ->where('status', 'open')
            ->first();

        $stats = [
            'total_sessions' => PosSession::where('tenant_id', $tid)->count(),
            'total_revenue'  => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->sum('total'),
            'total_orders'   => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->count(),
        ];

        $currency = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');

        return view('admin.pos.sessions', compact('sessions', 'currency', 'activeSession', 'stats'));
    }

    // ─── Session Detail ───────────────────────────────────────────────────────
    public function sessionDetail(Request $request, PosSession $session)
    {
        $tid = $this->tenantId();
        abort_if($session->tenant_id !== $tid, 403);

        $orders = PosOrder::where('session_id', $session->id)
            ->with('items')
            ->latest()
            ->paginate(25);

        $totalSales = PosOrder::where('session_id', $session->id)
            ->where('status', 'completed')
            ->sum('total');

        $currency = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');

        return view('admin.pos.session-detail', compact('session', 'orders', 'totalSales', 'currency'));
    }

    // ─── POS Dashboard / Reports ──────────────────────────────────────────────
    public function dashboard()
    {
        $tid      = $this->tenantId();
        $currency = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');

        $stats = [
            'today_orders'   => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', today())->count(),
            'today_sales'    => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', today())->sum('total'),
            'week_orders'    => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'week_sales'     => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total'),
            'month_orders'   => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereMonth('created_at', now()->month)->count(),
            'month_sales'    => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereMonth('created_at', now()->month)->sum('total'),
            'total_orders'   => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->count(),
            'total_sales'    => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->sum('total'),
        ];

        // Last 7 days chart data
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date'   => $date->format('d M'),
                'orders' => PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', $date)->count(),
                'sales'  => (float) PosOrder::where('tenant_id', $tid)->where('status', 'completed')->whereDate('created_at', $date)->sum('total'),
            ];
        }

        // Top products
        $topProducts = PosOrderItem::whereHas('order', fn($q) => $q->where('tenant_id', $tid)->where('status', 'completed'))
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Payment method breakdown
        $paymentBreakdown = PosOrder::where('tenant_id', $tid)
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Recent orders
        $recentOrders = PosOrder::where('tenant_id', $tid)
            ->with('cashier')
            ->latest()
            ->limit(10)
            ->get();

        // Open session
        $openSession = PosSession::where('tenant_id', $tid)
            ->where('cashier_id', auth()->id())
            ->where('status', 'open')
            ->latest()
            ->first();

        return view('admin.pos.dashboard', compact(
            'stats', 'chartData', 'topProducts', 'paymentBreakdown',
            'recentOrders', 'openSession', 'currency'
        ));
    }

    // ─── Settings ─────────────────────────────────────────────────────────────
    public function settings()
    {
        $tid      = $this->tenantId();
        $taxRate  = \App\Models\SiteSetting::getValueForTenant($tid, 'pos_tax_rate', '0');
        $currency = \App\Models\SiteSetting::getValueForTenant($tid, 'currency_symbol', '₹');
        $receiptFooter = \App\Models\SiteSetting::getValueForTenant($tid, 'pos_receipt_footer', 'Thank you for shopping with us!');

        return view('admin.pos.settings', compact('taxRate', 'currency', 'receiptFooter'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'pos_tax_rate'       => 'required|numeric|min:0|max:100',
            'currency_symbol'    => 'required|string|max:5',
            'pos_receipt_footer' => 'nullable|string|max:255',
        ]);

        $tid = $this->tenantId();
        \App\Models\SiteSetting::setValueForTenant($tid, 'pos_tax_rate', $request->pos_tax_rate);
        \App\Models\SiteSetting::setValueForTenant($tid, 'currency_symbol', $request->currency_symbol);
        \App\Models\SiteSetting::setValueForTenant($tid, 'pos_receipt_footer', $request->pos_receipt_footer ?? '');

        return back()->with('success', 'POS settings saved.');
    }
}
