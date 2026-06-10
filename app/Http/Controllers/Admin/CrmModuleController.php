<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// Existing CRM models
use App\Models\CrmLead;
use App\Models\CrmContact;
use App\Models\CrmAccount;
use App\Models\CrmDeal;
use App\Models\CrmActivity;

// New CRM models
use App\Models\CrmForecast;
use App\Models\CrmVendor;
use App\Models\CrmPriceBook;
use App\Models\CrmQuote;
use App\Models\CrmSalesOrder;
use App\Models\CrmPurchaseOrder;
use App\Models\CrmInvoice;
use App\Models\CrmCase;
use App\Models\CrmSolution;
use App\Models\CrmService;
use App\Models\CrmServiceBooking;
use App\Models\CrmProject;
use App\Models\CrmProjectTask;

class CrmModuleController extends Controller
{
    /**
     * Get the current tenant ID.
     */
    protected function tenantId(): int
    {
        return Auth::user()->id;
    }

    // ══════════════════════════════════════════════════════════════
    // ANALYSIS
    // ══════════════════════════════════════════════════════════════

    public function analysis()
    {
        $tid = $this->tenantId();

        $totalLeads    = CrmLead::where('user_id', $tid)->count();
        $totalContacts = CrmContact::where('user_id', $tid)->count();
        $totalAccounts = CrmAccount::where('user_id', $tid)->count();
        $openDeals     = CrmDeal::where('user_id', $tid)->whereNotIn('stage', ['closed_won','closed_lost'])->count();
        $wonDeals      = CrmDeal::where('user_id', $tid)->where('stage', 'closed_won');
        $wonValue      = $wonDeals->sum('value');
        $pipelineValue = CrmDeal::where('user_id', $tid)->whereNotIn('stage', ['closed_lost'])->sum('value');
        $totalDeals    = CrmDeal::where('user_id', $tid)->whereIn('stage', ['closed_won','closed_lost'])->count();
        $winRate       = $totalDeals > 0 ? round(($wonDeals->count() / $totalDeals) * 100, 1) : 0;
        $openCases     = CrmCase::where('user_id', $tid)->whereNotIn('status', ['closed','resolved'])->count();

        // Monthly revenue (last 12 months)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $total = CrmDeal::where('user_id', $tid)
                ->where('stage', 'closed_won')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->sum('value');
            $monthlyRevenue[] = ['month' => $date->format('M Y'), 'revenue' => (float) $total];
        }

        // Deals by stage
        $dealsByStage = CrmDeal::where('user_id', $tid)
            ->selectRaw('stage, COUNT(*) as count, SUM(value) as total')
            ->groupBy('stage')
            ->get()
            ->map(fn($r) => ['stage' => $r->stage, 'count' => $r->count, 'total' => (float) $r->total]);

        // Activity types
        $activityTypes = CrmActivity::where('user_id', $tid)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->map(fn($r) => ['type' => $r->type, 'count' => $r->count]);

        // Top accounts by closed won deal value
        $topAccounts = CrmAccount::where('crm_accounts.user_id', $tid)
            ->join('crm_deals', 'crm_accounts.id', '=', 'crm_deals.account_id')
            ->where('crm_deals.stage', 'closed_won')
            ->selectRaw('crm_accounts.name, SUM(crm_deals.value) as total')
            ->groupBy('crm_accounts.id', 'crm_accounts.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.crm2.analysis', [
            'kpis' => [
                'total_leads'    => $totalLeads,
                'total_contacts' => $totalContacts,
                'total_accounts' => $totalAccounts,
                'open_deals'     => $openDeals,
                'won_value'      => $wonValue,
                'pipeline_value' => $pipelineValue,
                'win_rate'       => $winRate,
                'open_cases'     => $openCases,
            ],
            'monthlyRevenue' => $monthlyRevenue,
            'dealsByStage'   => $dealsByStage,
            'activityTypes'  => $activityTypes,
            'topAccounts'    => $topAccounts,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // REPORTS
    // ══════════════════════════════════════════════════════════════

    public function reports(Request $request)
    {
        $tid  = $this->tenantId();
        $type = $request->input('type', 'sales_summary');
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $data = [];

        if ($type === 'sales_summary') {
            $data['deals'] = CrmDeal::where('user_id', $tid)
                ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->selectRaw('stage, COUNT(*) as count, SUM(value) as total')
                ->groupBy('stage')->get();

            $data['invoices'] = CrmInvoice::where('user_id', $tid)
                ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->selectRaw('status, COUNT(*) as count, SUM(total) as total')
                ->groupBy('status')->get();

        } elseif ($type === 'lead_report') {
            $data['leads'] = CrmLead::where('user_id', $tid)
                ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')->get();

            $data['sources'] = CrmLead::where('user_id', $tid)
                ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')->get();

        } elseif ($type === 'activity_report') {
            $data['activities'] = CrmActivity::where('user_id', $tid)
                ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->selectRaw('type, status, COUNT(*) as count')
                ->groupBy('type', 'status')->get();

        } elseif ($type === 'case_report') {
            $data['cases'] = CrmCase::where('user_id', $tid)
                ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->selectRaw('status, priority, COUNT(*) as count')
                ->groupBy('status', 'priority')->get();
        }

        return view('admin.crm2.reports', compact('type', 'from', 'to', 'data'));
    }

    // ══════════════════════════════════════════════════════════════
    // SALES (Leads, Contacts, Accounts, Deals, Forecasts)
    // ══════════════════════════════════════════════════════════════

    public function sales(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->input('tab', 'leads');
        $search = $request->input('search');

        // Shared lists for dropdowns
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();

        $leads = $contacts = $accounts = $deals = $forecasts = collect();

        if ($tab === 'leads') {
            $q = CrmLead::where('user_id', $tid)->withCount('conversations');
            if ($search) $q->where(fn($q) => $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%")->orWhere('company','like',"%$search%"));
            if ($request->status) $q->where('status', $request->status);
            $leads = $q->orderByDesc('created_at')->paginate(20)->withQueryString();

        } elseif ($tab === 'contacts') {
            $q = CrmContact::where('user_id', $tid)->with('account');
            if ($search) $q->where(fn($q) => $q->where('first_name','like',"%$search%")->orWhere('last_name','like',"%$search%")->orWhere('email','like',"%$search%"));
            $contacts = $q->orderByDesc('created_at')->paginate(20)->withQueryString();

        } elseif ($tab === 'accounts') {
            $q = CrmAccount::where('user_id', $tid)->withCount(['contacts','deals']);
            if ($search) $q->where(fn($q) => $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%"));
            if ($request->type) $q->where('type', $request->type);
            $accounts = $q->orderByDesc('created_at')->paginate(20)->withQueryString();

        } elseif ($tab === 'deals') {
            $q = CrmDeal::where('user_id', $tid)->with(['account','contact']);
            if ($search) $q->where('title','like',"%$search%");
            if ($request->stage) $q->where('stage', $request->stage);
            $deals = $q->orderByDesc('created_at')->paginate(20)->withQueryString();

        } elseif ($tab === 'forecasts') {
            $q = CrmForecast::where('user_id', $tid);
            if ($search) $q->where('notes','like',"%$search%");
            $forecasts = $q->orderByDesc('year')->orderByDesc('quarter')->paginate(20)->withQueryString();
        }

        return view('admin.crm2.sales', compact(
            'tab','leads','contacts','accounts','deals','forecasts',
            'accounts_list','contacts_list'
        ));
    }

    public function salesStore(Request $request)
    {
        $tid  = $this->tenantId();
        $type = $request->input('_type');

        switch ($type) {
            case 'lead':
                CrmLead::create(array_merge($request->only(['name','email','phone','company','source','status','deal_value','notes']), ['user_id' => $tid]));
                break;
            case 'contact':
                CrmContact::create(array_merge($request->only(['first_name','last_name','email','phone','job_title','account_id','source','notes']), ['user_id' => $tid]));
                break;
            case 'account':
                CrmAccount::create(array_merge($request->only(['name','type','industry','website','email','phone','city','country','notes']), ['user_id' => $tid]));
                break;
            case 'deal':
                CrmDeal::create(array_merge($request->only(['title','value','stage','account_id','contact_id','expected_close','probability','notes']), ['user_id' => $tid]));
                break;
            case 'forecast':
                CrmForecast::create(array_merge($request->only(['year','quarter','target_amount','achieved_amount','notes']), ['user_id' => $tid]));
                break;
        }

        return back()->with('success', ucfirst($type) . ' created successfully.');
    }

    public function salesUpdate(Request $request, string $type, int $id)
    {
        $tid = $this->tenantId();

        switch ($type) {
            case 'lead':
                CrmLead::where('id', $id)->where('user_id', $tid)->update($request->only(['name','email','phone','company','status','deal_value','notes']));
                break;
            case 'contact':
                CrmContact::where('id', $id)->where('user_id', $tid)->update($request->only(['first_name','last_name','email','phone','job_title','status']));
                break;
            case 'account':
                CrmAccount::where('id', $id)->where('user_id', $tid)->update($request->only(['name','type','industry','email','phone','status']));
                break;
            case 'deal':
                CrmDeal::where('id', $id)->where('user_id', $tid)->update($request->only(['title','value','stage','probability','expected_close','notes']));
                break;
            case 'forecast':
                CrmForecast::where('id', $id)->where('user_id', $tid)->update($request->only(['year','quarter','target_amount','achieved_amount','notes']));
                break;
        }

        return back()->with('success', ucfirst($type) . ' updated successfully.');
    }

    public function salesDestroy(string $type, int $id)
    {
        $tid = $this->tenantId();

        match ($type) {
            'lead'     => CrmLead::where('id', $id)->where('user_id', $tid)->delete(),
            'contact'  => CrmContact::where('id', $id)->where('user_id', $tid)->delete(),
            'account'  => CrmAccount::where('id', $id)->where('user_id', $tid)->delete(),
            'deal'     => CrmDeal::where('id', $id)->where('user_id', $tid)->delete(),
            'forecast' => CrmForecast::where('id', $id)->where('user_id', $tid)->delete(),
            default    => null,
        };

        return back()->with('success', ucfirst($type) . ' deleted.');
    }

    // ══════════════════════════════════════════════════════════════
    // ACTIVITIES (Tasks, Meetings, Calls)
    // ══════════════════════════════════════════════════════════════

    public function activities(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->input('tab', 'tasks');
        $search = $request->input('search');
        $status = $request->input('status');

        $typeMap = ['tasks' => 'task', 'meetings' => 'meeting', 'calls' => 'call'];
        $type    = $typeMap[$tab] ?? 'task';

        $q = CrmActivity::where('user_id', $tid)->where('type', $type);
        if ($search) $q->where('subject', 'like', "%$search%");
        if ($status) $q->where('status', $status);

        $activities = $q->orderByDesc('due_at')->paginate(20)->withQueryString();

        $leads_list    = CrmLead::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();

        return view('admin.crm2.activities', compact('tab','activities','leads_list','contacts_list','accounts_list'));
    }

    public function activityStore(Request $request)
    {
        $tid = $this->tenantId();

        CrmActivity::create(array_merge(
            $request->only(['type','subject','description','due_at','status','related_type','related_id']),
            ['user_id' => $tid]
        ));

        return back()->with('success', ucfirst($request->type) . ' created successfully.');
    }

    public function activityUpdate(Request $request, int $id)
    {
        $tid = $this->tenantId();
        CrmActivity::where('id', $id)->where('user_id', $tid)
            ->update($request->only(['subject','description','due_at','status']));

        return back()->with('success', 'Activity updated.');
    }

    public function activityComplete(int $id)
    {
        $tid = $this->tenantId();
        CrmActivity::where('id', $id)->where('user_id', $tid)->update(['status' => 'completed']);
        return response()->json(['success' => true]);
    }

    public function activityDestroy(int $id)
    {
        $tid = $this->tenantId();
        CrmActivity::where('id', $id)->where('user_id', $tid)->delete();
        return back()->with('success', 'Activity deleted.');
    }

    // ══════════════════════════════════════════════════════════════
    // INVENTORY (Price Books, Quotes, Sales Orders, Purchase Orders, Invoices, Vendors)
    // ══════════════════════════════════════════════════════════════

    public function inventory(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->input('tab', 'price_books');
        $search = $request->input('search');

        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $vendors_list  = CrmVendor::where('user_id', $tid)->orderBy('name')->get();

        $priceBooks = $quotes = $salesOrders = $purchaseOrders = $invoices = $vendors = collect();

        switch ($tab) {
            case 'price_books':
                $q = CrmPriceBook::where('user_id', $tid);
                if ($search) $q->where('name','like',"%$search%");
                $priceBooks = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
                break;

            case 'quotes':
                $q = CrmQuote::where('user_id', $tid)->with('account');
                if ($search) $q->where(fn($q) => $q->where('subject','like',"%$search%")->orWhere('quote_number','like',"%$search%"));
                if ($request->stage) $q->where('stage', $request->stage);
                $quotes = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
                break;

            case 'sales_orders':
                $q = CrmSalesOrder::where('user_id', $tid)->with('account');
                if ($search) $q->where(fn($q) => $q->where('subject','like',"%$search%")->orWhere('so_number','like',"%$search%"));
                $salesOrders = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
                break;

            case 'purchase_orders':
                $q = CrmPurchaseOrder::where('user_id', $tid)->with('vendor');
                if ($search) $q->where(fn($q) => $q->where('subject','like',"%$search%")->orWhere('po_number','like',"%$search%"));
                $purchaseOrders = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
                break;

            case 'invoices':
                $q = CrmInvoice::where('user_id', $tid)->with('account');
                if ($search) $q->where(fn($q) => $q->where('subject','like',"%$search%")->orWhere('invoice_number','like',"%$search%"));
                if ($request->status) $q->where('status', $request->status);
                $invoices = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
                break;

            case 'vendors':
                $q = CrmVendor::where('user_id', $tid);
                if ($search) $q->where(fn($q) => $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%"));
                $vendors = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
                break;
        }

        return view('admin.crm2.inventory', compact(
            'tab','priceBooks','quotes','salesOrders','purchaseOrders','invoices','vendors',
            'accounts_list','contacts_list','vendors_list'
        ));
    }

    public function inventoryStore(Request $request)
    {
        $tid  = $this->tenantId();
        $type = $request->input('_type');

        // Build line items JSON
        $lineItems = [];
        if ($request->has('item_name')) {
            foreach ($request->item_name as $i => $name) {
                if (!$name) continue;
                $lineItems[] = [
                    'name'      => $name,
                    'qty'       => (float) ($request->item_qty[$i] ?? 1),
                    'price'     => (float) ($request->item_price[$i] ?? 0),
                    'discount'  => (float) ($request->item_discount[$i] ?? 0),
                    'tax_rate'  => (float) ($request->item_tax_rate[$i] ?? 0),
                    'total'     => (float) ($request->item_total[$i] ?? 0),
                ];
            }
        }

        $common = [
            'user_id'  => $tid,
            'subtotal'   => (float) $request->input('subtotal', 0),
            'discount_amount' => (float) $request->input('discount_amount', 0),
            'tax_amount' => (float) $request->input('tax_amount', 0),
            'total'      => (float) $request->input('total', 0),
            'line_items' => $lineItems ?: null,
        ];

        switch ($type) {
            case 'price_book':
                CrmPriceBook::create(array_merge($request->only(['name','description','pricing_percentage','is_active']), ['user_id' => $tid]));
                break;
            case 'quote':
                CrmQuote::create(array_merge(
                    $request->only(['subject','account_id','contact_id','stage','valid_until','terms','notes']),
                    $common,
                    ['quote_number' => 'QT-' . strtoupper(Str::random(8))]
                ));
                break;
            case 'sales_order':
                CrmSalesOrder::create(array_merge(
                    $request->only(['subject','account_id','status','delivery_date','notes']),
                    $common,
                    ['so_number' => 'SO-' . strtoupper(Str::random(8))]
                ));
                break;
            case 'purchase_order':
                CrmPurchaseOrder::create(array_merge(
                    $request->only(['subject','vendor_id','status','expected_delivery','notes']),
                    $common,
                    ['po_number' => 'PO-' . strtoupper(Str::random(8))]
                ));
                break;
            case 'invoice':
                $inv = array_merge(
                    $request->only(['subject','account_id','status','due_date','amount_paid','notes']),
                    $common,
                    ['invoice_number' => 'INV-' . strtoupper(Str::random(8))]
                );
                $inv['balance_due'] = $inv['total'] - (float) ($inv['amount_paid'] ?? 0);
                CrmInvoice::create($inv);
                break;
            case 'vendor':
                CrmVendor::create(array_merge($request->only(['name','email','phone','website','category','status','address','description']), ['user_id' => $tid]));
                break;
        }

        return back()->with('success', ucwords(str_replace('_', ' ', $type)) . ' created successfully.');
    }

    public function inventoryDestroy(string $type, int $id)
    {
        $tid = $this->tenantId();

        match ($type) {
            'price_book'     => CrmPriceBook::where('id', $id)->where('user_id', $tid)->delete(),
            'quote'          => CrmQuote::where('id', $id)->where('user_id', $tid)->delete(),
            'sales_order'    => CrmSalesOrder::where('id', $id)->where('user_id', $tid)->delete(),
            'purchase_order' => CrmPurchaseOrder::where('id', $id)->where('user_id', $tid)->delete(),
            'invoice'        => CrmInvoice::where('id', $id)->where('user_id', $tid)->delete(),
            'vendor'         => CrmVendor::where('id', $id)->where('user_id', $tid)->delete(),
            default          => null,
        };

        return back()->with('success', ucwords(str_replace('_', ' ', $type)) . ' deleted.');
    }

    // ══════════════════════════════════════════════════════════════
    // SUPPORT (Cases, Solutions)
    // ══════════════════════════════════════════════════════════════

    public function support(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->input('tab', 'cases');
        $search = $request->input('search');

        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $cases = $solutions = collect();

        if ($tab === 'cases') {
            $q = CrmCase::where('user_id', $tid)->with(['account','contact']);
            if ($search) $q->where(fn($q) => $q->where('subject','like',"%$search%")->orWhere('case_number','like',"%$search%"));
            if ($request->status) $q->where('status', $request->status);
            if ($request->priority) $q->where('priority', $request->priority);
            $cases = $q->orderByDesc('created_at')->paginate(20)->withQueryString();

        } elseif ($tab === 'solutions') {
            $q = CrmSolution::where('user_id', $tid);
            if ($search) $q->where(fn($q) => $q->where('title','like',"%$search%")->orWhere('question','like',"%$search%"));
            $solutions = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        }

        return view('admin.crm2.support', compact('tab','cases','solutions','accounts_list','contacts_list'));
    }

    public function supportStore(Request $request)
    {
        $tid  = $this->tenantId();
        $type = $request->input('_type');

        if ($type === 'case') {
            CrmCase::create(array_merge(
                $request->only(['subject','priority','status','type','origin','account_id','contact_id','description','resolution']),
                ['user_id' => $tid, 'case_number' => 'CASE-' . strtoupper(Str::random(6))]
            ));
        } elseif ($type === 'solution') {
            CrmSolution::create(array_merge(
                $request->only(['title','category','is_public','question','answer']),
                ['user_id' => $tid]
            ));
        }

        return back()->with('success', ucfirst($type) . ' created successfully.');
    }

    public function supportUpdate(Request $request, string $type, int $id)
    {
        $tid = $this->tenantId();

        if ($type === 'case') {
            CrmCase::where('id', $id)->where('user_id', $tid)
                ->update($request->only(['subject','priority','status','resolution']));
        } elseif ($type === 'solution') {
            CrmSolution::where('id', $id)->where('user_id', $tid)
                ->update($request->only(['title','category','is_public','question','answer']));
        }

        return back()->with('success', ucfirst($type) . ' updated.');
    }

    public function supportDestroy(string $type, int $id)
    {
        $tid = $this->tenantId();

        match ($type) {
            'case'     => CrmCase::where('id', $id)->where('user_id', $tid)->delete(),
            'solution' => CrmSolution::where('id', $id)->where('user_id', $tid)->delete(),
            default    => null,
        };

        return back()->with('success', ucfirst($type) . ' deleted.');
    }

    // ══════════════════════════════════════════════════════════════
    // SERVICES
    // ══════════════════════════════════════════════════════════════

    public function services(Request $request)
    {
        $tid  = $this->tenantId();
        $tab  = $request->input('tab', 'catalog');

        $services_list = CrmService::where('user_id', $tid)->where('is_active', true)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $serviceList   = collect();
        $bookings      = collect();

        if ($tab === 'catalog') {
            $serviceList = CrmService::where('user_id', $tid)->orderByDesc('created_at')->paginate(12)->withQueryString();
        } elseif ($tab === 'bookings') {
            $q = CrmServiceBooking::where('user_id', $tid)->with(['service','contact']);
            if ($request->status)     $q->where('status', $request->status);
            if ($request->service_id) $q->where('service_id', $request->service_id);
            $bookings = $q->orderByDesc('booking_time')->paginate(20)->withQueryString();
        }

        return view('admin.crm2.services', compact('tab','serviceList','bookings','services_list','contacts_list','accounts_list'));
    }

    public function servicesStore(Request $request)
    {
        $tid  = $this->tenantId();
        $type = $request->input('_type');

        if ($type === 'service') {
            CrmService::create(array_merge(
                $request->only(['name','description','price','duration_minutes','is_active']),
                ['user_id' => $tid]
            ));
        } elseif ($type === 'booking') {
            CrmServiceBooking::create(array_merge(
                $request->only(['service_id','contact_id','account_id','booking_time','status','price','notes']),
                ['user_id' => $tid]
            ));
        }

        return back()->with('success', ucfirst($type) . ' created successfully.');
    }

    public function servicesUpdate(Request $request, string $type, int $id)
    {
        $tid = $this->tenantId();

        if ($type === 'service') {
            CrmService::where('id', $id)->where('user_id', $tid)
                ->update($request->only(['name','description','price','duration_minutes','is_active']));
        } elseif ($type === 'booking') {
            CrmServiceBooking::where('id', $id)->where('user_id', $tid)
                ->update($request->only(['status','notes','price']));
        }

        return back()->with('success', ucfirst($type) . ' updated.');
    }

    public function servicesDestroy(string $type, int $id)
    {
        $tid = $this->tenantId();

        match ($type) {
            'service' => CrmService::where('id', $id)->where('user_id', $tid)->delete(),
            'booking' => CrmServiceBooking::where('id', $id)->where('user_id', $tid)->delete(),
            default   => null,
        };

        return back()->with('success', ucfirst($type) . ' deleted.');
    }

    // ══════════════════════════════════════════════════════════════
    // PROJECTS
    // ══════════════════════════════════════════════════════════════

    public function projects(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->input('tab', 'projects');
        $search = $request->input('search');
        $status = $request->input('status');

        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $deals_list    = CrmDeal::where('user_id', $tid)->orderBy('title')->get();
        $projects_list = CrmProject::where('user_id', $tid)->orderBy('name')->get();
        $projects      = collect();
        $projectTasks  = collect();

        if ($tab === 'projects') {
            $q = CrmProject::where('user_id', $tid)->with('account')->withCount('tasks');
            if ($search) $q->where('name','like',"%$search%");
            if ($status) $q->where('status', $status);
            $projects = $q->orderByDesc('created_at')->paginate(12)->withQueryString();

        } elseif ($tab === 'tasks') {
            $q = CrmProjectTask::whereHas('project', fn($q) => $q->where('user_id', $tid))->with('project');
            if ($request->project_id) $q->where('project_id', $request->project_id);
            if ($search) $q->where('name','like',"%$search%");
            if ($status) $q->where('status', $status);
            $projectTasks = $q->orderBy('due_date')->paginate(20)->withQueryString();
        }

        return view('admin.crm2.projects', compact('tab','projects','projectTasks','accounts_list','deals_list','projects_list'));
    }

    public function projectsStore(Request $request)
    {
        $tid  = $this->tenantId();
        $type = $request->input('_type');

        if ($type === 'project') {
            CrmProject::create(array_merge(
                $request->only(['name','status','priority','account_id','deal_id','start_date','end_date','budget','description']),
                ['user_id' => $tid]
            ));
        } elseif ($type === 'task') {
            CrmProjectTask::create(
                $request->only(['project_id','name','priority','status','due_date','estimated_hours','description'])
            );
        }

        return back()->with('success', ucfirst($type) . ' created successfully.');
    }

    public function projectsUpdate(Request $request, string $type, int $id)
    {
        $tid = $this->tenantId();

        if ($type === 'project') {
            CrmProject::where('id', $id)->where('user_id', $tid)
                ->update($request->only(['name','status','priority','end_date','budget','description']));
        } elseif ($type === 'task') {
            CrmProjectTask::where('id', $id)
                ->whereHas('project', fn($q) => $q->where('user_id', $tid))
                ->update($request->only(['name','status','priority','due_date','estimated_hours','description']));
        }

        return back()->with('success', ucfirst($type) . ' updated.');
    }

    public function projectTaskStatus(Request $request, int $id)
    {
        $tid = $this->tenantId();
        CrmProjectTask::where('id', $id)
            ->whereHas('project', fn($q) => $q->where('user_id', $tid))
            ->update(['status' => $request->input('status')]);

        return response()->json(['success' => true]);
    }

    public function projectsDestroy(string $type, int $id)
    {
        $tid = $this->tenantId();

        if ($type === 'project') {
            CrmProject::where('id', $id)->where('user_id', $tid)->delete();
        } elseif ($type === 'task') {
            CrmProjectTask::where('id', $id)
                ->whereHas('project', fn($q) => $q->where('user_id', $tid))
                ->delete();
        }

        return back()->with('success', ucfirst($type) . ' deleted.');
    }

    // ══════════════════════════════════════════════════════════════
    // SALES SUB-MODULE PAGES
    // ══════════════════════════════════════════════════════════════

    public function salesLeads(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmLead::where('user_id', $tid)->withCount('conversations');
        if ($search) $q->where(fn($q) => $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%")->orWhere('company','like',"%$search%"));
        if ($request->status) $q->where('status', $request->status);
        $leads = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.sales.leads', compact('leads'));
    }

    public function salesLeadsCreate()
    {
        return view('admin.crm2.sales.create-lead');
    }

    public function salesContacts(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $q = CrmContact::where('user_id', $tid)->with('account');
        if ($search) $q->where(fn($q) => $q->where('first_name','like',"%$search%")->orWhere('last_name','like',"%$search%")->orWhere('email','like',"%$search%"));
        $contacts = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.sales.contacts', compact('contacts', 'accounts_list'));
    }

    public function salesContactsCreate()
    {
        $tid = $this->tenantId();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.sales.create-contact', compact('accounts_list'));
    }

    public function salesAccounts(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmAccount::where('user_id', $tid)->withCount(['contacts','deals']);
        if ($search) $q->where(fn($q) => $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%"));
        if ($request->type) $q->where('type', $request->type);
        $accounts = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.sales.accounts', compact('accounts'));
    }

    public function salesAccountsCreate()
    {
        return view('admin.crm2.sales.create-account');
    }

    public function salesDeals(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $q = CrmDeal::where('user_id', $tid)->with(['account','contact']);
        if ($search) $q->where('title','like',"%$search%");
        if ($request->stage) $q->where('stage', $request->stage);
        $deals = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.sales.deals', compact('deals', 'accounts_list', 'contacts_list'));
    }

    public function salesDealsCreate()
    {
        $tid = $this->tenantId();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.sales.create-deal', compact('accounts_list', 'contacts_list'));
    }

    public function salesForecasts(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmForecast::where('user_id', $tid);
        if ($search) $q->where('notes','like',"%$search%");
        $forecasts = $q->orderByDesc('year')->orderByDesc('quarter')->paginate(25)->withQueryString();
        return view('admin.crm2.sales.forecasts', compact('forecasts'));
    }

    public function salesForecastsCreate()
    {
        return view('admin.crm2.sales.create-forecast');
    }

    // ══════════════════════════════════════════════════════════════
    // ACTIVITIES SUB-MODULE PAGES
    // ══════════════════════════════════════════════════════════════

    public function activitiesTasks(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmActivity::where('user_id', $tid)->where('type', 'task');
        if ($search) $q->where('title','like',"%$search%");
        $activities = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.activities.tasks', compact('activities'));
    }

    public function activitiesTasksCreate()
    {
        return view('admin.crm2.activities.create-activity', ['type' => 'task']);
    }

    public function activitiesMeetings(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmActivity::where('user_id', $tid)->where('type', 'meeting');
        if ($search) $q->where('title','like',"%$search%");
        $activities = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.activities.meetings', compact('activities'));
    }

    public function activitiesMeetingsCreate()
    {
        return view('admin.crm2.activities.create-activity', ['type' => 'meeting']);
    }

    public function activitiesCalls(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmActivity::where('user_id', $tid)->where('type', 'call');
        if ($search) $q->where('title','like',"%$search%");
        $activities = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.activities.calls', compact('activities'));
    }

    public function activitiesCallsCreate()
    {
        return view('admin.crm2.activities.create-activity', ['type' => 'call']);
    }

    // ══════════════════════════════════════════════════════════════
    // INVENTORY SUB-MODULE PAGES
    // ══════════════════════════════════════════════════════════════

    public function inventoryPriceBooks(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmPriceBook::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.price-books', compact('items'));
    }

    public function inventoryPriceBooksCreate()
    {
        return view('admin.crm2.inventory.create-price-book');
    }

    public function inventoryQuotes(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmQuote::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.quotes', compact('items'));
    }

    public function inventoryQuotesCreate()
    {
        $tid = $this->tenantId();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.create-quote', compact('accounts_list'));
    }

    public function inventorySalesOrders(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmSalesOrder::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.sales-orders', compact('items'));
    }

    public function inventorySalesOrdersCreate()
    {
        $tid = $this->tenantId();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.create-sales-order', compact('accounts_list'));
    }

    public function inventoryPurchaseOrders(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmPurchaseOrder::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.purchase-orders', compact('items'));
    }

    public function inventoryPurchaseOrdersCreate()
    {
        $tid = $this->tenantId();
        $vendors_list = CrmVendor::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.create-purchase-order', compact('vendors_list'));
    }

    public function inventoryInvoices(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmInvoice::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.invoices', compact('items'));
    }

    public function inventoryInvoicesCreate()
    {
        $tid = $this->tenantId();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.create-invoice', compact('accounts_list'));
    }

    public function inventoryVendors(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmVendor::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.vendors', compact('items'));
    }

    public function inventoryVendorsCreate()
    {
        return view('admin.crm2.inventory.create-vendor');
    }

    // ══════════════════════════════════════════════════════════════
    // SUPPORT SUB-MODULE PAGES
    // ══════════════════════════════════════════════════════════════

    public function supportCases(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmCase::where('user_id', $tid);
        if ($search) $q->where(fn($q) => $q->where('subject','like',"%$search%")->orWhere('description','like',"%$search%"));
        $cases = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.support.cases', compact('cases'));
    }

    public function supportCasesCreate()
    {
        return view('admin.crm2.support.create-case');
    }

    public function supportSolutions(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmSolution::where('user_id', $tid);
        if ($search) $q->where(fn($q) => $q->where('title','like',"%$search%")->orWhere('content','like',"%$search%"));
        $solutions = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.support.solutions', compact('solutions'));
    }

    public function supportSolutionsCreate()
    {
        return view('admin.crm2.support.create-solution');
    }

    // ══════════════════════════════════════════════════════════════
    // SERVICES SUB-MODULE PAGES
    // ══════════════════════════════════════════════════════════════

    public function servicesCatalog(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmService::where('user_id', $tid);
        if ($search) $q->where('name','like',"%$search%");
        $services = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.services.catalog', compact('services'));
    }

    public function servicesCatalogCreate()
    {
        return view('admin.crm2.services.create-service');
    }

    public function servicesBookings(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $services_list = CrmService::where('user_id', $tid)->orderBy('name')->get();
        $q = CrmServiceBooking::where('user_id', $tid)->with('service');
        if ($search) $q->where(fn($q) => $q->where('client_name','like',"%$search%")->orWhere('client_email','like',"%$search%"));
        $bookings = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.services.bookings', compact('bookings', 'services_list'));
    }

    public function servicesBookingsCreate()
    {
        $tid = $this->tenantId();
        $services_list = CrmService::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.services.create-booking', compact('services_list'));
    }

    // ══════════════════════════════════════════════════════════════
    // PROJECTS SUB-MODULE PAGES
    // ══════════════════════════════════════════════════════════════

    public function projectsList(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $q = CrmProject::where('user_id', $tid)->withCount('tasks');
        if ($search) $q->where('name','like',"%$search%");
        $projects = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.projects.list', compact('projects'));
    }

    public function projectsListCreate()
    {
        return view('admin.crm2.projects.create-project');
    }

    public function projectsTasks(Request $request)
    {
        $tid = $this->tenantId();
        $search = $request->input('search');
        $projects_list = CrmProject::where('user_id', $tid)->orderBy('name')->get();
        $q = CrmProjectTask::whereHas('project', fn($q) => $q->where('user_id', $tid));
        if ($search) $q->where('title','like',"%$search%");
        $tasks = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.projects.tasks', compact('tasks', 'projects_list'));
    }

    public function projectsTasksCreate()
    {
        $tid = $this->tenantId();
        $projects_list = CrmProject::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.projects.create-task', compact('projects_list'));
    }

}