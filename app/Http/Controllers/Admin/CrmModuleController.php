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
                $leadData = array_merge(
                    $request->only([
                        'lead_image','owner_id','lead_status','rating',
                        'salutation','first_name','last_name','title','company','industry',
                        'email','secondary_email','phone','mobile','fax','website',
                        'twitter','linkedin','facebook','instagram',
                        'country','flat_no','street','city','state','zip',
                        'annual_revenue','no_of_employees',
                        'budget','requirement','expected_purchase_date','deal_value',
                        'decision_maker','competitor','interest_level','follow_up_date',
                        'source','campaign_source','campaign_name','referral_source',
                        'priority','description','internal_notes','notes',
                    ]),
                    [
                        'user_id'       => $tid,
                        'name'          => trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? '')),
                        'email_opt_out' => $request->boolean('email_opt_out'),
                        'lead_status'   => $request->input('lead_status', 'Not Contacted'),
                        'status'        => 'new',
                    ]
                );
                // Handle image upload
                if ($request->hasFile('lead_image')) {
                    $leadData['lead_image'] = $request->file('lead_image')->store('crm/leads', 'public');
                }
                $lead = CrmLead::create($leadData);
                if ($request->input('_action') === 'save_new') {
                    return redirect()->route('admin.crm2.sales.leads.create')->with('success', 'Lead created. Add another.');
                }
                return redirect()->route('admin.crm2.sales.leads.show', $lead->id)->with('success', 'Lead created successfully.');
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
                $updateData = $request->only([
                        'lead_image','owner_id','lead_status','rating',
                        'salutation','first_name','last_name','title','company','industry',
                        'email','secondary_email','phone','mobile','fax','website',
                        'twitter','linkedin','facebook','instagram',
                        'country','flat_no','street','city','state','zip',
                        'annual_revenue','no_of_employees',
                        'budget','requirement','expected_purchase_date','deal_value',
                        'decision_maker','competitor','interest_level','follow_up_date',
                        'source','campaign_source','campaign_name','referral_source',
                        'priority','description','internal_notes','notes',
                    ]);
                $updateData['name'] = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
                $updateData['email_opt_out'] = $request->boolean('email_opt_out');
                if ($request->hasFile('lead_image')) {
                    $updateData['lead_image'] = $request->file('lead_image')->store('crm/leads', 'public');
                }
                CrmLead::where('id', $id)->where('user_id', $tid)->update($updateData);
                break;
            case 'contact':
                CrmContact::where('id', $id)->where('user_id', $tid)->update($request->only(['first_name','last_name','email','phone','job_title','department','account_id','status','city','country','notes']));
                break;
            case 'account':
                CrmAccount::where('id', $id)->where('user_id', $tid)->update($request->only(['name','type','industry','email','phone','website','annual_revenue','employees','status','city','country','notes']));
                break;
            case 'deal':
                CrmDeal::where('id', $id)->where('user_id', $tid)->update($request->only(['title','value','stage','probability','expected_close','account_id','contact_id','notes']));
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
                $request->only(['service_id','contact_id','account_id','status','price','notes']),
                ['booking_time' => $request->input('booking_date'), 'user_id' => $tid]
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
                ->update($request->only(['service_id','status','notes','price','booking_time']));
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
                array_merge($request->only(['project_id','priority','status','due_date','estimated_hours','description']), ['name' => $request->input('title')])
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
        $tid = $this->tenantId();
        $staff = \App\Models\User::orderBy('name')->get();
        // Fallback: get all users for this tenant
        if ($staff->isEmpty()) {
            $staff = \App\Models\User::orderBy('name')->limit(20)->get();
        }
        return view('admin.crm2.sales.create-lead', compact('staff'));
    }

    public function salesLeadsShow($id)
    {
        $tid = $this->tenantId();
        $lead = \App\Models\CrmLead::where('id', $id)->where('user_id', $tid)->with('owner')->firstOrFail();
        $activities = \App\Models\CrmActivity::where('related_type', 'lead')
            ->where('related_id', $id)
            ->orderByDesc('created_at')
            ->get();
        $staff = \App\Models\User::where('id', $tid)->get();
        return view('admin.crm2.sales.view-lead', compact('lead', 'activities', 'staff'));
    }

    public function salesLeadsConvert(Request $request, $id)
    {
        $tid = $this->tenantId();
        $lead = \App\Models\CrmLead::where('id', $id)->where('user_id', $tid)->firstOrFail();

        if ($lead->is_converted) {
            return back()->with('error', 'This lead has already been converted.');
        }

        // Create Account
        $account = \App\Models\CrmAccount::create([
            'user_id'  => $tid,
            'name'     => $lead->company ?: ($lead->first_name . ' ' . $lead->last_name),
            'type'     => 'prospect',
            'industry' => $lead->industry,
            'website'  => $lead->website,
            'phone'    => $lead->phone ?? $lead->mobile,
            'email'    => $lead->email,
            'city'     => $lead->city,
            'country'  => $lead->country,
            'annual_revenue' => $lead->annual_revenue,
            'employees'      => $lead->no_of_employees,
            'notes'    => $lead->description,
            'status'   => 'active',
        ]);

        // Create Contact
        $contact = \App\Models\CrmContact::create([
            'user_id'    => $tid,
            'account_id' => $account->id,
            'first_name' => $lead->first_name ?? $lead->name,
            'last_name'  => $lead->last_name ?? '',
            'email'      => $lead->email,
            'phone'      => $lead->phone ?? $lead->mobile,
            'job_title'  => $lead->title,
            'source'     => $lead->source ?? 'manual',
            'status'     => 'active',
        ]);

        // Optionally create Deal
        if ($request->input('create_deal', '0') === '1') {
            \App\Models\CrmDeal::create([
                'user_id'        => $tid,
                'title'          => 'Deal — ' . ($lead->company ?: $lead->first_name . ' ' . $lead->last_name),
                'value'          => $lead->deal_value ?? $lead->budget ?? 0,
                'stage'          => 'prospecting',
                'account_id'     => $account->id,
                'contact_id'     => $contact->id,
                'expected_close' => $lead->expected_purchase_date ?? now()->addDays(30)->toDateString(),
                'probability'    => 20,
                'notes'          => $lead->requirement,
            ]);
        }

        // Mark lead as converted
        $lead->update([
            'is_converted'   => true,
            'converted_date' => now(),
            'account_id'     => $account->id,
            'contact_id'     => $contact->id,
        ]);

        return redirect()->route('admin.crm2.sales.leads')
            ->with('success', 'Lead converted successfully! Account, Contact' . ($request->input('create_deal','0')==='1' ? ', and Deal' : '') . ' created.');
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

    public function salesContactsCreate(Request $request)
    {
        $tid = $this->tenantId();
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $prefill_account_id = $request->query('account_id');
        return view('admin.crm2.sales.create-contact', compact('staff', 'accounts_list', 'prefill_account_id'));
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
        $tid = $this->tenantId();
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.sales.create-account', compact('staff', 'accounts_list'));
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

    public function salesDealsCreate(Request $request)
    {
        $tid = $this->tenantId();
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $prefill_account_id = $request->query('account_id');
        $prefill_contact_id = $request->query('contact_id');
        return view('admin.crm2.sales.create-deal', compact('staff', 'accounts_list', 'contacts_list', 'prefill_account_id', 'prefill_contact_id'));
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


    // ─── EDIT METHODS (full-page edit forms) ─────────────────────────────────

    public function salesLeadsEdit($id) {
        $tid = $this->tenantId();
        $lead = CrmLead::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        return view('admin.crm2.sales.edit-lead', compact('lead', 'staff'));
    }
    public function salesContactsEdit($id) {
        $item = CrmContact::where('user_id', auth()->id())->findOrFail($id);
        $accounts = CrmAccount::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.sales.edit-contact', compact('item', 'accounts'));
    }
    public function salesAccountsEdit($id) {
        $item = CrmAccount::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.sales.edit-account', compact('item'));
    }
    public function salesDealsEdit($id) {
        $item = CrmDeal::where('user_id', auth()->id())->findOrFail($id);
        $accounts = CrmAccount::where('user_id', auth()->id())->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', auth()->id())->orderBy('first_name')->get();
        return view('admin.crm2.sales.edit-deal', compact('item', 'accounts', 'contacts'));
    }
    public function salesForecastsEdit($id) {
        $item = CrmForecast::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.sales.edit-forecast', compact('item'));
    }

    public function activitiesTasksEdit($id) {
        $item = CrmActivity::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.activities.edit-activity', ['item' => $item, 'backRoute' => 'admin.crm2.activities.tasks']);
    }
    public function activitiesMeetingsEdit($id) {
        $item = CrmActivity::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.activities.edit-activity', ['item' => $item, 'backRoute' => 'admin.crm2.activities.meetings']);
    }
    public function activitiesCallsEdit($id) {
        $item = CrmActivity::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.activities.edit-activity', ['item' => $item, 'backRoute' => 'admin.crm2.activities.calls']);
    }

    public function inventoryPriceBooksEdit($id) {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.inventory.edit-price-book', compact('item'));
    }
    public function inventoryQuotesEdit($id) {
        $item = CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $accounts_list = CrmAccount::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.inventory.edit-quote', compact('item', 'accounts_list'));
    }
    public function inventorySalesOrdersEdit($id) {
        $item = CrmSalesOrder::where('user_id', auth()->id())->findOrFail($id);
        $accounts_list = CrmAccount::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.inventory.edit-sales-order', compact('item', 'accounts_list'));
    }
    public function inventoryPurchaseOrdersEdit($id) {
        $item = CrmPurchaseOrder::where('user_id', auth()->id())->findOrFail($id);
        $vendors_list = CrmVendor::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.inventory.edit-purchase-order', compact('item', 'vendors_list'));
    }
    public function inventoryInvoicesEdit($id) {
        $item = CrmInvoice::where('user_id', auth()->id())->findOrFail($id);
        $accounts_list = CrmAccount::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.inventory.edit-invoice', compact('item', 'accounts_list'));
    }
    public function inventoryVendorsEdit($id) {
        $item = CrmVendor::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.inventory.edit-vendor', compact('item'));
    }
    public function inventoryUpdate(Request $request, $type, $id) {
        $uid = auth()->id();
        $routeMap = [
            'price_books'=>'admin.crm2.inventory.price-books','quotes'=>'admin.crm2.inventory.quotes',
            'sales_orders'=>'admin.crm2.inventory.sales-orders','purchase_orders'=>'admin.crm2.inventory.purchase-orders',
            'invoices'=>'admin.crm2.inventory.invoices','vendors'=>'admin.crm2.inventory.vendors',
        ];
        switch ($type) {
            case 'price_books':
                CrmPriceBook::where('user_id',$uid)->findOrFail($id)->update($request->only(['name','description','pricing_percentage','is_active']));
                break;
            case 'quotes':
                CrmQuote::where('user_id',$uid)->findOrFail($id)->update($request->only(['subject','account_id','stage','valid_until','subtotal','discount_amount','tax_amount','total','notes']));
                break;
            case 'sales_orders':
                CrmSalesOrder::where('user_id',$uid)->findOrFail($id)->update($request->only(['subject','account_id','status','delivery_date','subtotal','discount_amount','tax_amount','total','notes']));
                break;
            case 'purchase_orders':
                CrmPurchaseOrder::where('user_id',$uid)->findOrFail($id)->update($request->only(['subject','vendor_id','status','expected_delivery','subtotal','discount_amount','tax_amount','total','notes']));
                break;
            case 'invoices':
                CrmInvoice::where('user_id',$uid)->findOrFail($id)->update($request->only(['subject','account_id','status','due_date','subtotal','discount_amount','tax_amount','total','amount_paid','notes']));
                break;
            case 'vendors':
                CrmVendor::where('user_id',$uid)->findOrFail($id)->update($request->only(['name','email','phone','website','category','address','description','status']));
                break;
        }
        return redirect()->route($routeMap[$type] ?? 'admin.crm2.inventory.price-books')->with('success', ucwords(str_replace('_',' ',$type)).' updated successfully.');
    }

    public function supportCasesEdit($id) {
        $item = CrmCase::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.support.edit-case', compact('item'));
    }
    public function supportSolutionsEdit($id) {
        $item = CrmSolution::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.support.edit-solution', compact('item'));
    }

    public function servicesCatalogEdit($id) {
        $item = CrmService::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.services.edit-service', compact('item'));
    }
    public function servicesBookingsEdit($id) {
        $item = CrmServiceBooking::where('user_id', auth()->id())->findOrFail($id);
        $services_list = CrmService::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.services.edit-booking', compact('item', 'services_list'));
    }

    public function projectsListEdit($id) {
        $item = CrmProject::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.crm2.projects.edit-project', compact('item'));
    }
    public function projectsTasksEdit($id) {
        $item = CrmProjectTask::where('user_id', auth()->id())->findOrFail($id);
        $projects_list = CrmProject::where('user_id', auth()->id())->orderBy('name')->get();
        return view('admin.crm2.projects.edit-task', compact('item', 'projects_list'));
    }

    // ─── CONTACTS STORE / SHOW / UPDATE ─────────────────────────────────────────
    public function salesContactsStore(Request $request)
    {
        $data = $request->except(['_token']);
        $data['user_id'] = auth()->id();
        if ($request->hasFile('contact_image')) {
            $data['contact_image'] = $request->file('contact_image')->store('crm/contacts', 'public');
        }
        $data['email_opt_out'] = $request->has('email_opt_out') ? 1 : 0;
        CrmContact::create($data);
        return redirect()->route('admin.crm2.sales.contacts')->with('success', 'Contact created successfully.');
    }

    public function salesContactsShow($id)
    {
        $contact = CrmContact::with(['account','owner','reportingTo'])->where('user_id', auth()->id())->findOrFail($id);
        $deals = CrmDeal::where('user_id', auth()->id())->where('contact_id', $id)->get();
        $leads = CrmLead::where('user_id', auth()->id())->where('contact_id', $id)->get();
        $activities = \App\Models\CrmActivity::where('user_id', auth()->id())
            ->where('related_type', 'contact')->where('related_id', $id)->orderByDesc('created_at')->get();
        return view('admin.crm2.sales.view-contact', compact('contact', 'deals', 'leads', 'activities'));
    }

    public function salesContactsUpdate(Request $request, $id)
    {
        $contact = CrmContact::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('contact_image')) {
            $data['contact_image'] = $request->file('contact_image')->store('crm/contacts', 'public');
        }
        $data['email_opt_out'] = $request->has('email_opt_out') ? 1 : 0;
        $contact->update($data);
        return redirect()->route('admin.crm2.sales.contacts')->with('success', 'Contact updated successfully.');
    }

    // ─── ACCOUNTS STORE / SHOW / UPDATE ─────────────────────────────────────────
    public function salesAccountsStore(Request $request)
    {
        $data = $request->except(['_token']);
        $data['user_id'] = auth()->id();
        if ($request->hasFile('account_image')) {
            $data['account_image'] = $request->file('account_image')->store('crm/accounts', 'public');
        }
        CrmAccount::create($data);
        return redirect()->route('admin.crm2.sales.accounts')->with('success', 'Account created successfully.');
    }

    public function salesAccountsShow($id)
    {
        $account = CrmAccount::with(['owner','contacts','deals','leads'])->where('user_id', auth()->id())->findOrFail($id);
        $contacts = $account->contacts;
        $deals = $account->deals;
        $leads = $account->leads;
        return view('admin.crm2.sales.view-account', compact('account', 'contacts', 'deals', 'leads'));
    }

    public function salesAccountsUpdate(Request $request, $id)
    {
        $account = CrmAccount::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('account_image')) {
            $data['account_image'] = $request->file('account_image')->store('crm/accounts', 'public');
        }
        $account->update($data);
        return redirect()->route('admin.crm2.sales.accounts')->with('success', 'Account updated successfully.');
    }

    // ─── DEALS STORE / SHOW / UPDATE ─────────────────────────────────────────────
    public function salesDealsStore(Request $request)
    {
        $data = $request->except(['_token']);
        $data['user_id'] = auth()->id();
        // Map name/amount/closing_date to DB columns
        if (!isset($data['title']) && isset($data['name'])) $data['title'] = $data['name'];
        if (!isset($data['value']) && isset($data['amount'])) $data['value'] = $data['amount'];
        if (!isset($data['expected_close']) && isset($data['closing_date'])) $data['expected_close'] = $data['closing_date'];
        CrmDeal::create($data);
        return redirect()->route('admin.crm2.sales.deals')->with('success', 'Deal created successfully.');
    }

    public function salesDealsShow($id)
    {
        $deal = CrmDeal::with(['account','contact','owner'])->where('user_id', auth()->id())->findOrFail($id);
        $activities = \App\Models\CrmActivity::where('user_id', auth()->id())
            ->where('related_type', 'deal')->where('related_id', $id)->orderByDesc('created_at')->get();
        return view('admin.crm2.sales.view-deal', compact('deal', 'activities'));
    }

    public function salesDealsUpdate(Request $request, $id)
    {
        $deal = CrmDeal::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->except(['_token', '_method']);
        if (!isset($data['title']) && isset($data['name'])) $data['title'] = $data['name'];
        if (!isset($data['value']) && isset($data['amount'])) $data['value'] = $data['amount'];
        if (!isset($data['expected_close']) && isset($data['closing_date'])) $data['expected_close'] = $data['closing_date'];
        $deal->update($data);
        return redirect()->route('admin.crm2.sales.deals')->with('success', 'Deal updated successfully.');
    }

}
