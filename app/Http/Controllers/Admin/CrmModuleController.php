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
use App\Models\CrmQuoteAttachment;
use App\Models\CrmPriceBookAttachment;
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
use App\Models\CrmProjectMilestone;
use App\Models\CrmProjectIssue;
use App\Models\CrmProjectTimeLog;
use App\Models\CrmProjectNote;
use App\Models\CrmNote;
use App\Models\CrmMailConfig;
use App\Models\CrmMailTemplate;
use App\Models\CrmAccountEmail;
use App\Models\CrmProduct;
use App\Models\User;

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
        $type = $request->input('type');
                // Build line items JSON - forms send as JSON string
        $lineItemsRaw = $request->input('line_items');
        $lineItems = null;
        if ($lineItemsRaw) {
            $decoded = json_decode($lineItemsRaw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $lineItems = $decoded;
            }
        }
        $common = [
            'user_id'         => $tid,
            'subtotal'        => (float) $request->input('subtotal', 0),
            'discount_amount' => (float) $request->input('discount_amount', 0),
            'tax_amount'      => (float) $request->input('tax_amount', 0),
            'adjustment'      => (float) $request->input('adjustment', 0),
            'grand_total'     => (float) $request->input('grand_total', 0),
            'total'           => (float) $request->input('grand_total', $request->input('total', 0)),
            'line_items'      => $lineItems,
        ];

        switch ($type) {
            case 'price_book':
                CrmPriceBook::create(array_merge($request->only(['name','description','pricing_percentage','is_active']), ['user_id' => $tid]));
                break;
            case 'quote':
                CrmQuote::create(array_merge(
                    $request->only(['subject','owner_id','account_id','contact_id','deal_id','stage','valid_until','terms','notes','team','carrier',
                        'bill_country','bill_building','bill_street','bill_city','bill_state','bill_zip',
                        'ship_country','ship_building','ship_street','ship_city','ship_state','ship_zip']),
                    $common,
                    ['quote_number' => 'QT-' . strtoupper(Str::random(8))]
                ));
                break;
            case 'sales_order':
                CrmSalesOrder::create(array_merge(
                    $request->only(['subject','owner_id','account_id','contact_id','deal_id','quote_id','status','delivery_date','terms','notes',
                        'bill_country','bill_building','bill_street','bill_city','bill_state','bill_zip',
                        'ship_country','ship_building','ship_street','ship_city','ship_state','ship_zip']),
                    $common,
                    ['so_number' => 'SO-' . strtoupper(Str::random(8))]
                ));
                break;
            case 'purchase_order':
                CrmPurchaseOrder::create(array_merge(
                    $request->only(['subject','owner_id','vendor_id','contact_id','status','expected_delivery','terms','notes','requisition_no',
                        'bill_country','bill_building','bill_street','bill_city','bill_state','bill_zip',
                        'ship_country','ship_building','ship_street','ship_city','ship_state','ship_zip']),
                    $common,
                    ['po_number' => 'PO-' . strtoupper(Str::random(8))]
                ));
                break;
            case 'invoice':
                $inv = array_merge(
                    $request->only(['subject','owner_id','account_id','contact_id','deal_id','sales_order_id','status','due_date','amount_paid','terms','notes',
                        'bill_country','bill_building','bill_street','bill_city','bill_state','bill_zip',
                        'ship_country','ship_building','ship_street','ship_city','ship_state','ship_zip']),
                    $common,
                    ['invoice_number' => 'INV-' . strtoupper(Str::random(8))]
                );
                $inv['balance_due'] = $inv['total'] - (float) ($inv['amount_paid'] ?? 0);
                CrmInvoice::create($inv);
                break;
            case 'vendor':
                CrmVendor::create(array_merge($request->only(['name','owner_id','email','phone','fax','gl_account','email_opt_out','website','category','status','address','description',
                    'bill_country','bill_building','bill_street','bill_city','bill_state','bill_zip']), ['user_id' => $tid]));
                break;
        }

        $createRouteMap = [
            'price_book'     => 'admin.crm2.inventory.price-books',
            'quote'          => 'admin.crm2.inventory.quotes',
            'sales_order'    => 'admin.crm2.inventory.sales-orders',
            'purchase_order' => 'admin.crm2.inventory.purchase-orders',
            'invoice'        => 'admin.crm2.inventory.invoices',
            'vendor'         => 'admin.crm2.inventory.vendors',
        ];
        return redirect()->route($createRouteMap[$type] ?? 'admin.crm2.inventory.price-books')
            ->with('success', ucwords(str_replace('_', ' ', $type)) . ' created successfully.');
    }


    // ══════════════════════════════════════════════════════════════
    // INVENTORY SHOW METHODS
    // ══════════════════════════════════════════════════════════════
    public function inventoryPriceBooksShow($id) {
        $tid = auth()->id();
        $item = CrmPriceBook::where('user_id', $tid)->findOrFail($id);
        $notes = CrmNote::where('notable_type', 'price_book')->where('notable_id', $id)->with('user')->latest()->get();
        $priceBookProducts = $item->products()->get();
        $allProducts = CrmProduct::where('user_id', $tid)->orderBy('name')->get();
        $attachments = CrmPriceBookAttachment::where('price_book_id', $id)->latest()->get();
        return view('admin.crm2.inventory.view-price-book', compact('item', 'notes', 'priceBookProducts', 'allProducts', 'attachments'));
    }

    // ─── PRICE BOOK NOTES ─────────────────────────────────────────────────────
    public function priceBookNotesStore(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:5000']);
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        CrmNote::create([
            'user_id'      => auth()->id(),
            'notable_type' => 'price_book',
            'notable_id'   => $id,
            'content'      => $request->content,
        ]);
        return redirect()->route('admin.crm2.inventory.price-books.show', $id)->with('success', 'Note added.');
    }

    // ─── PRICE BOOK PRODUCTS ──────────────────────────────────────────────────
    public function priceBookProductsAdd(Request $request, $id)
    {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $productId = $request->input('product_id');
        if (!$item->products()->where('product_id', $productId)->exists()) {
            $item->products()->attach($productId);
        }
        $count = $item->products()->count();
        $product = CrmProduct::find($productId);
        return response()->json(['success' => true, 'count' => $count, 'product' => $product]);
    }

    public function priceBookProductsRemove(Request $request, $id)
    {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $productId = $request->input('product_id');
        $item->products()->detach($productId);
        $count = $item->products()->count();
        return response()->json(['success' => true, 'count' => $count]);
    }

    public function priceBookProductsUpdateAll(Request $request, $id)
    {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $products = $request->input('products', []);
        foreach ($products as $productId => $data) {
            $item->products()->updateExistingPivot($productId, [
                'unit_price'          => $data['unit_price'] ?? null,
                'list_price'          => $data['list_price'] ?? null,
                'discount_percentage' => $data['discount_percentage'] ?? 0,
            ]);
        }
        return redirect()->route('admin.crm2.inventory.price-books.show', $id)->with('success', 'Product prices updated.');
    }

    public function priceBookImportTemplate($id)
    {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $products = $item->products()->get();
        $csv = "product_code,product_name,unit_price,list_price,discount_percentage\n";
        foreach ($products as $p) {
            $csv .= implode(',', [
                $p->product_code ?? '',
                '"' . str_replace('"', '""', $p->name) . '"',
                $p->pivot->unit_price ?? $p->unit_price ?? '',
                $p->pivot->list_price ?? '',
                $p->pivot->discount_percentage ?? '0',
            ]) . "\n";
        }
        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="price-book-' . $item->id . '-template.csv"',
        ]);
    }

    public function priceBookImport(Request $request, $id)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt,xlsx']);
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('trim', array_shift($rows));
        $codeIdx = array_search('product_code', $header);
        $listIdx = array_search('list_price', $header);
        $discIdx = array_search('discount_percentage', $header);
        $unitIdx = array_search('unit_price', $header);
        $updated = 0;
        foreach ($rows as $row) {
            if (empty($row[$codeIdx ?? 0])) continue;
            $product = CrmProduct::where('user_id', auth()->id())->where('product_code', trim($row[$codeIdx]))->first();
            if ($product && $item->products()->where('product_id', $product->id)->exists()) {
                $pivotData = [];
                if ($listIdx !== false && isset($row[$listIdx])) $pivotData['list_price'] = (float) $row[$listIdx];
                if ($discIdx !== false && isset($row[$discIdx])) $pivotData['discount_percentage'] = (float) $row[$discIdx];
                if ($unitIdx !== false && isset($row[$unitIdx])) $pivotData['unit_price'] = (float) $row[$unitIdx];
                if ($pivotData) { $item->products()->updateExistingPivot($product->id, $pivotData); $updated++; }
            }
        }
        return redirect()->route('admin.crm2.inventory.price-books.show', $id)->with('success', "Imported: {$updated} product prices updated.");
    }

    // ─── PRICE BOOK ATTACHMENTS ───────────────────────────────────────────────
    public function priceBookAttachmentsStore(Request $request, $id)
    {
        $request->validate(['attachment' => 'required|file|max:10240']);
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $file = $request->file('attachment');
        $stored = $file->store('crm/price-book-attachments', 'public');
        CrmPriceBookAttachment::create([
            'price_book_id' => $id,
            'user_id'       => auth()->id(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $stored,
            'mime_type'     => $file->getMimeType(),
            'file_size'     => $file->getSize(),
        ]);
        return redirect()->route('admin.crm2.inventory.price-books.show', $id)->with('success', 'File uploaded successfully.');
    }

    public function priceBookAttachmentsDownload($id, $attId)
    {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $att = CrmPriceBookAttachment::where('price_book_id', $id)->findOrFail($attId);
        $path = storage_path('app/public/' . $att->stored_name);
        if (!file_exists($path)) abort(404);
        return response()->download($path, $att->original_name);
    }

    public function priceBookAttachmentsDestroy(Request $request, $id, $attId)
    {
        $item = CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $att = CrmPriceBookAttachment::where('price_book_id', $id)->findOrFail($attId);
        \Storage::disk('public')->delete($att->stored_name);
        $att->delete();
        return response()->json(['success' => true]);
    }
    public function inventoryQuotesShow($id) {
        $tid = auth()->id();
        $item            = CrmQuote::where('user_id', $tid)->findOrFail($id);
        $notes           = CrmNote::where('notable_type', 'quote')->where('notable_id', $id)->with('user')->latest()->get();
        $salesOrders     = CrmSalesOrder::where('user_id', $tid)->where('quote_id', $id)->get();
        $allSalesOrders  = CrmSalesOrder::where('user_id', $tid)->get();
        $attachments     = CrmQuoteAttachment::where('quote_id', $id)->latest()->get();
        $openActivities  = CrmActivity::where('user_id', $tid)->where('related_type', 'quote')->where('related_id', $id)->whereNotIn('status', ['Completed', 'completed'])->get();
        $closedActivities= CrmActivity::where('user_id', $tid)->where('related_type', 'quote')->where('related_id', $id)->whereIn('status', ['Completed', 'completed'])->get();
        $mailTemplates   = CrmMailTemplate::where('user_id', $tid)->where('is_active', true)->get();
        $mailConfig      = CrmMailConfig::where('user_id', $tid)->where('is_active', true)->first();
        $accounts_list   = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list   = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.inventory.view-quote', compact(
            'item', 'notes', 'salesOrders', 'allSalesOrders', 'attachments',
            'openActivities', 'closedActivities', 'mailTemplates', 'mailConfig',
            'accounts_list', 'contacts_list'
        ));
    }

    // ─── QUOTE NOTES ──────────────────────────────────────────────────────────
    public function quoteNotesStore(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:5000']);
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        CrmNote::create([
            'user_id'      => auth()->id(),
            'notable_type' => 'quote',
            'notable_id'   => $id,
            'content'      => $request->content,
        ]);
        return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('success', 'Note added.');
    }

    // ─── QUOTE ACTIVITIES ─────────────────────────────────────────────────────
    public function quoteActivitiesStore(Request $request, $id)
    {
        $request->validate([
            'type'        => 'required|string',
            'subject'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_at'      => 'nullable|date',
        ]);
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        CrmActivity::create([
            'user_id'      => auth()->id(),
            'type'         => $request->type,
            'subject'      => $request->subject,
            'description'  => $request->description,
            'related_type' => 'quote',
            'related_id'   => $id,
            'due_at'       => $request->due_at,
            'status'       => 'pending',
        ]);
        return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('success', 'Activity added.');
    }

    public function quoteActivitiesComplete(Request $request, $id, $actId)
    {
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $act = CrmActivity::where('user_id', auth()->id())->where('related_type', 'quote')->where('related_id', $id)->findOrFail($actId);
        $act->update(['status' => 'completed', 'completed_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function quoteActivitiesDestroy(Request $request, $id, $actId)
    {
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $act = CrmActivity::where('user_id', auth()->id())->where('related_type', 'quote')->where('related_id', $id)->findOrFail($actId);
        $act->delete();
        return response()->json(['success' => true]);
    }

    // ─── QUOTE ATTACHMENTS ────────────────────────────────────────────────────
    public function quoteAttachmentsStore(Request $request, $id)
    {
        $request->validate(['attachment' => 'required|file|max:10240']);
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $file   = $request->file('attachment');
        $stored = $file->store('crm/quote-attachments', 'public');
        CrmQuoteAttachment::create([
            'quote_id'      => $id,
            'user_id'       => auth()->id(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $stored,
            'mime_type'     => $file->getMimeType(),
            'file_size'     => $file->getSize(),
        ]);
        return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('success', 'File uploaded.');
    }

    public function quoteAttachmentsDownload($id, $attId)
    {
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $att  = CrmQuoteAttachment::where('quote_id', $id)->findOrFail($attId);
        $path = storage_path('app/public/' . $att->stored_name);
        if (!file_exists($path)) abort(404);
        return response()->download($path, $att->original_name);
    }

    public function quoteAttachmentsDestroy(Request $request, $id, $attId)
    {
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $att = CrmQuoteAttachment::where('quote_id', $id)->findOrFail($attId);
        \Storage::disk('public')->delete($att->stored_name);
        $att->delete();
        return response()->json(['success' => true]);
    }

    // ─── QUOTE SALES ORDERS ───────────────────────────────────────────────────
    public function quoteSalesOrdersAssign(Request $request, $id)
    {
        $request->validate(['sales_order_id' => 'required|exists:crm_sales_orders,id']);
        $quote = CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $so    = CrmSalesOrder::where('user_id', auth()->id())->findOrFail($request->sales_order_id);
        $so->update(['quote_id' => $id]);
        return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('success', 'Sales Order assigned.');
    }

    public function quoteSalesOrdersUnassign(Request $request, $id, $soId)
    {
        CrmQuote::where('user_id', auth()->id())->findOrFail($id);
        $so = CrmSalesOrder::where('user_id', auth()->id())->where('quote_id', $id)->findOrFail($soId);
        $so->update(['quote_id' => null]);
        return response()->json(['success' => true]);
    }

    // ─── QUOTE SEND EMAIL ─────────────────────────────────────────────────────
    public function quoteSendMail(Request $request, $id)
    {
        $request->validate([
            'to_email'    => 'required|email',
            'subject'     => 'required|string|max:255',
            'body_html'   => 'required|string',
        ]);
        $uid   = auth()->id();
        $quote = CrmQuote::where('user_id', $uid)->findOrFail($id);
        $cfg   = CrmMailConfig::where('user_id', $uid)->where('is_active', true)->first();
        if (!$cfg) {
            return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('error', 'No active mail configuration found.');
        }
        try {
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                $cfg->mail_host, $cfg->mail_port, $cfg->mail_encryption === 'ssl'
            );
            $transport->setUsername($cfg->mail_username);
            $transport->setPassword($cfg->mail_password);
            $mailer  = new \Symfony\Component\Mailer\Mailer($transport);
            $email   = (new \Symfony\Component\Mime\Email())
                ->from(new \Symfony\Component\Mime\Address($cfg->from_address, $cfg->from_name ?? $cfg->from_address))
                ->to($request->to_email)
                ->subject($request->subject)
                ->html($request->body_html);
            if ($request->cc_email)  $email->cc($request->cc_email);
            if ($request->bcc_email) $email->bcc($request->bcc_email);
            $mailer->send($email);
            // Log the email using crm_account_emails if account exists
            if ($quote->account_id) {
                \App\Models\CrmAccountEmail::create([
                    'user_id'    => $uid,
                    'account_id' => $quote->account_id,
                    'to_email'   => $request->to_email,
                    'cc_email'   => $request->cc_email,
                    'bcc_email'  => $request->bcc_email,
                    'subject'    => $request->subject,
                    'body_html'  => $request->body_html,
                    'from_name'  => $cfg->from_name,
                    'from_email' => $cfg->from_address,
                    'status'     => 'sent',
                    'sent_at'    => now(),
                ]);
            }
            return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('success', 'Email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.crm2.inventory.quotes.show', $id)->with('error', 'Email failed: ' . $e->getMessage());
        }
    }
    public function inventorySalesOrdersShow($id) {
        $tid = auth()->id();
        $item = CrmSalesOrder::where('user_id', $tid)->findOrFail($id);

        // Notes (polymorphic)
        $notes = \App\Models\CrmNote::where('notable_type','sales_order')
            ->where('notable_id', $id)
            ->where('user_id', $tid)
            ->with('user')
            ->latest()->get();

        // Invoices linked to this SO
        $invoices = \App\Models\CrmInvoice::where('user_id', $tid)
            ->where('sales_order_id', $id)
            ->orderByDesc('created_at')->get();

        // All invoices (for assign slider)
        $allInvoices = \App\Models\CrmInvoice::where('user_id', $tid)
            ->whereNull('sales_order_id')
            ->orderByDesc('created_at')->get();

        // Attachments
        $attachments = \App\Models\CrmSoAttachment::where('user_id', $tid)
            ->where('sales_order_id', $id)
            ->latest()->get();

        // Activities
        $openActivities = \App\Models\CrmActivity::where('related_type','sales_order')
            ->where('related_id', $id)
            ->where('user_id', $tid)
            ->where('status','open')
            ->orderBy('due_at')->get();

        $closedActivities = \App\Models\CrmActivity::where('related_type','sales_order')
            ->where('related_id', $id)
            ->where('user_id', $tid)
            ->where('status','closed')
            ->latest('completed_at')->get();

        // Mail config & templates
        $mailConfig    = \App\Models\CrmMailConfig::where('user_id', $tid)->where('is_active', 1)->first();
        $mailTemplates = \App\Models\CrmMailTemplate::where('user_id', $tid)->get();

        return view('admin.crm2.inventory.view-sales-order', compact(
            'item','notes','invoices','allInvoices','attachments',
            'openActivities','closedActivities','mailConfig','mailTemplates'
        ));
    }

    // ── SO Notes ──────────────────────────────────────────────────
    public function soNotesStore(Request $request, $id) {
        $tid = auth()->id();
        CrmSalesOrder::where('user_id',$tid)->findOrFail($id);
        \App\Models\CrmNote::create([
            'user_id'      => $tid,
            'notable_type' => 'sales_order',
            'notable_id'   => $id,
            'content'      => $request->input('content'),
        ]);
        return back()->with('success','Note added.');
    }

    // ── SO Activities ─────────────────────────────────────────────
    public function soActivitiesStore(Request $request, $id) {
        $tid = auth()->id();
        CrmSalesOrder::where('user_id',$tid)->findOrFail($id);
        \App\Models\CrmActivity::create([
            'user_id'      => $tid,
            'related_type' => 'sales_order',
            'related_id'   => $id,
            'type'         => $request->input('type'),
            'subject'      => $request->input('subject'),
            'description'  => $request->input('description'),
            'due_at'       => $request->input('due_at') ?: null,
            'status'       => 'open',
        ]);
        return back()->with('success','Activity added.');
    }

    public function soActivitiesComplete(Request $request, $id, $actId) {
        $tid = auth()->id();
        $act = \App\Models\CrmActivity::where('user_id',$tid)->where('related_type','sales_order')->where('related_id',$id)->findOrFail($actId);
        $act->update(['status'=>'closed','completed_at'=>now()]);
        return response()->json(['success'=>true]);
    }

    public function soActivitiesDestroy(Request $request, $id, $actId) {
        $tid = auth()->id();
        $act = \App\Models\CrmActivity::where('user_id',$tid)->where('related_type','sales_order')->where('related_id',$id)->findOrFail($actId);
        $act->delete();
        return response()->json(['success'=>true]);
    }

    // ── SO Attachments ────────────────────────────────────────────
    public function soAttachmentsStore(Request $request, $id) {
        $tid = auth()->id();
        CrmSalesOrder::where('user_id',$tid)->findOrFail($id);
        $request->validate(['attachment'=>'required|file|max:10240']);
        $file = $request->file('attachment');
        $stored = $file->store('crm/so-attachments','public');
        \App\Models\CrmSoAttachment::create([
            'user_id'        => $tid,
            'sales_order_id' => $id,
            'original_name'  => $file->getClientOriginalName(),
            'stored_name'    => $stored,
            'mime_type'      => $file->getMimeType(),
            'file_size'      => $file->getSize(),
        ]);
        return back()->with('success','Attachment uploaded.');
    }

    public function soAttachmentsDownload(Request $request, $id, $attId) {
        $tid = auth()->id();
        $att = \App\Models\CrmSoAttachment::where('user_id',$tid)->where('sales_order_id',$id)->findOrFail($attId);
        $path = storage_path('app/public/'.$att->stored_name);
        if (!file_exists($path)) abort(404);
        return response()->download($path, $att->original_name);
    }

    public function soAttachmentsDestroy(Request $request, $id, $attId) {
        $tid = auth()->id();
        $att = \App\Models\CrmSoAttachment::where('user_id',$tid)->where('sales_order_id',$id)->findOrFail($attId);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($att->stored_name);
        $att->delete();
        return response()->json(['success'=>true]);
    }

    // ── SO Invoices ───────────────────────────────────────────────
    public function soInvoicesAssign(Request $request, $id) {
        $tid = auth()->id();
        CrmSalesOrder::where('user_id',$tid)->findOrFail($id);
        $inv = \App\Models\CrmInvoice::where('user_id',$tid)->findOrFail($request->input('invoice_id'));
        $inv->update(['sales_order_id'=>$id]);
        return back()->with('success','Invoice assigned.');
    }

    public function soInvoicesUnassign(Request $request, $id, $invId) {
        $tid = auth()->id();
        $inv = \App\Models\CrmInvoice::where('user_id',$tid)->where('sales_order_id',$id)->findOrFail($invId);
        $inv->update(['sales_order_id'=>null]);
        return response()->json(['success'=>true]);
    }

    // ── SO Send Mail ──────────────────────────────────────────────
    public function soSendMail(Request $request, $id) {
        $tid = auth()->id();
        $item = CrmSalesOrder::where('user_id',$tid)->findOrFail($id);
        $config = \App\Models\CrmMailConfig::where('user_id',$tid)->where('is_active',1)->first();
        if (!$config) return back()->with('error','No active mail configuration.');
        try {
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host',     $config->mail_host);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port',     $config->mail_port);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', $config->mail_username);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', $config->mail_password);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption',$config->mail_encryption ?? 'tls');
            \Illuminate\Support\Facades\Config::set('mail.from.address', $config->from_address);
            \Illuminate\Support\Facades\Config::set('mail.from.name',    $config->from_name ?? 'CRM');
            \Illuminate\Support\Facades\Mail::html($request->input('body_html'), function($msg) use ($request, $config) {
                $msg->to($request->input('to_email'))
                    ->subject($request->input('subject'));
                if ($request->filled('cc_email'))  $msg->cc($request->input('cc_email'));
                if ($request->filled('bcc_email')) $msg->bcc($request->input('bcc_email'));
            });
            return back()->with('success','Email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error','Failed to send email: '.$e->getMessage());
        }
    }
    public function inventoryPurchaseOrdersShow($id) {
        $tid = auth()->id();
        $item = CrmPurchaseOrder::where('user_id', $tid)->findOrFail($id);

        // Notes (polymorphic)
        $notes = \App\Models\CrmNote::where('notable_type','purchase_order')
            ->where('notable_id', $id)
            ->where('user_id', $tid)
            ->with('user')
            ->latest()->get();

        // Attachments
        $attachments = \App\Models\CrmPoAttachment::where('user_id', $tid)
            ->where('purchase_order_id', $id)
            ->latest()->get();

        // Activities
        $openActivities = \App\Models\CrmActivity::where('related_type','purchase_order')
            ->where('related_id', $id)
            ->where('user_id', $tid)
            ->where('status','open')
            ->orderBy('due_at')->get();

        $closedActivities = \App\Models\CrmActivity::where('related_type','purchase_order')
            ->where('related_id', $id)
            ->where('user_id', $tid)
            ->where('status','closed')
            ->latest('completed_at')->get();

        // Mail config & templates
        $mailConfig    = \App\Models\CrmMailConfig::where('user_id', $tid)->where('is_active', 1)->first();
        $mailTemplates = \App\Models\CrmMailTemplate::where('user_id', $tid)->get();

        return view('admin.crm2.inventory.view-purchase-order', compact(
            'item','notes','attachments',
            'openActivities','closedActivities','mailConfig','mailTemplates'
        ));
    }

    // ── PO Notes ──────────────────────────────────────────────────
    public function poNotesStore(Request $request, $id) {
        $tid = auth()->id();
        CrmPurchaseOrder::where('user_id',$tid)->findOrFail($id);
        \App\Models\CrmNote::create([
            'user_id'      => $tid,
            'notable_type' => 'purchase_order',
            'notable_id'   => $id,
            'content'      => $request->input('content'),
        ]);
        return back()->with('success','Note added.');
    }

    // ── PO Activities ─────────────────────────────────────────────
    public function poActivitiesStore(Request $request, $id) {
        $tid = auth()->id();
        CrmPurchaseOrder::where('user_id',$tid)->findOrFail($id);
        \App\Models\CrmActivity::create([
            'user_id'      => $tid,
            'related_type' => 'purchase_order',
            'related_id'   => $id,
            'type'         => $request->input('type'),
            'subject'      => $request->input('subject'),
            'description'  => $request->input('description'),
            'due_at'       => $request->input('due_at') ?: null,
            'status'       => 'open',
        ]);
        return back()->with('success','Activity added.');
    }

    public function poActivitiesComplete(Request $request, $id, $actId) {
        $tid = auth()->id();
        $act = \App\Models\CrmActivity::where('user_id',$tid)->where('related_type','purchase_order')->where('related_id',$id)->findOrFail($actId);
        $act->update(['status'=>'closed','completed_at'=>now()]);
        return response()->json(['success'=>true]);
    }

    public function poActivitiesDestroy(Request $request, $id, $actId) {
        $tid = auth()->id();
        $act = \App\Models\CrmActivity::where('user_id',$tid)->where('related_type','purchase_order')->where('related_id',$id)->findOrFail($actId);
        $act->delete();
        return response()->json(['success'=>true]);
    }

    // ── PO Attachments ────────────────────────────────────────────
    public function poAttachmentsStore(Request $request, $id) {
        $tid = auth()->id();
        CrmPurchaseOrder::where('user_id',$tid)->findOrFail($id);
        $request->validate(['attachment'=>'required|file|max:10240']);
        $file = $request->file('attachment');
        $stored = $file->store('crm/po-attachments','public');
        \App\Models\CrmPoAttachment::create([
            'user_id'           => $tid,
            'purchase_order_id' => $id,
            'original_name'     => $file->getClientOriginalName(),
            'stored_name'       => $stored,
            'mime_type'         => $file->getMimeType(),
            'file_size'         => $file->getSize(),
        ]);
        return back()->with('success','Attachment uploaded.');
    }

    public function poAttachmentsDownload(Request $request, $id, $attId) {
        $tid = auth()->id();
        $att = \App\Models\CrmPoAttachment::where('user_id',$tid)->where('purchase_order_id',$id)->findOrFail($attId);
        $path = storage_path('app/public/'.$att->stored_name);
        if (!file_exists($path)) abort(404);
        return response()->download($path, $att->original_name);
    }

    public function poAttachmentsDestroy(Request $request, $id, $attId) {
        $tid = auth()->id();
        $att = \App\Models\CrmPoAttachment::where('user_id',$tid)->where('purchase_order_id',$id)->findOrFail($attId);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($att->stored_name);
        $att->delete();
        return response()->json(['success'=>true]);
    }

    // ── PO Send Mail ──────────────────────────────────────────────
    public function poSendMail(Request $request, $id) {
        $tid = auth()->id();
        $item = CrmPurchaseOrder::where('user_id',$tid)->findOrFail($id);
        $config = \App\Models\CrmMailConfig::where('user_id',$tid)->where('is_active',1)->first();
        if (!$config) return back()->with('error','No active mail configuration.');
        try {
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host',     $config->mail_host);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port',     $config->mail_port);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', $config->mail_username);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', $config->mail_password);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption',$config->mail_encryption ?? 'tls');
            \Illuminate\Support\Facades\Config::set('mail.from.address', $config->from_address);
            \Illuminate\Support\Facades\Config::set('mail.from.name',    $config->from_name ?? 'CRM');
            \Illuminate\Support\Facades\Mail::html($request->input('body_html'), function($msg) use ($request) {
                $msg->to($request->input('to_email'))
                    ->subject($request->input('subject'));
                if ($request->filled('cc_email'))  $msg->cc($request->input('cc_email'));
                if ($request->filled('bcc_email')) $msg->bcc($request->input('bcc_email'));
            });
            return back()->with('success','Email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error','Failed to send email: '.$e->getMessage());
        }
    }
    public function inventoryInvoicesShow($id) {
        $tid  = auth()->id();
        $item = CrmInvoice::where('user_id', $tid)->findOrFail($id);
        $notes           = CrmNote::where('notable_type','invoice')->where('notable_id',$id)->with('user')->latest()->get();
        $openActivities  = CrmActivity::where('related_type','invoice')->where('related_id',$id)->where('status','open')->with('user')->latest()->get();
        $closedActivities= CrmActivity::where('related_type','invoice')->where('related_id',$id)->where('status','closed')->with('user')->latest()->get();
        $attachments     = \App\Models\CrmInvoiceAttachment::where('invoice_id',$id)->latest()->get();
        $mailConfig      = \App\Models\CrmMailConfig::where('user_id',$tid)->where('is_active',1)->first();
        $mailTemplates   = \App\Models\CrmMailTemplate::where('user_id',$tid)->get();
        return view('admin.crm2.inventory.view-invoice', compact(
            'item','notes','openActivities','closedActivities','attachments','mailConfig','mailTemplates'
        ));
    }

    // ── Invoice Notes ─────────────────────────────────────────────────────
    public function invoiceNotesStore(Request $request, $id) {
        $tid  = auth()->id();
        $item = CrmInvoice::where('user_id',$tid)->findOrFail($id);
        CrmNote::create(['notable_type'=>'invoice','notable_id'=>$id,'user_id'=>$tid,'content'=>$request->input('content')]);
        return back()->with('success','Note added.');
    }

    // ── Invoice Activities ────────────────────────────────────────────────
    public function invoiceActivitiesStore(Request $request, $id) {
        $tid  = auth()->id();
        $item = CrmInvoice::where('user_id',$tid)->findOrFail($id);
        CrmActivity::create([
            'related_type'=>'invoice','related_id'=>$id,'user_id'=>$tid,
            'type'=>$request->input('type'),'subject'=>$request->input('subject'),
            'description'=>$request->input('description'),'due_at'=>$request->input('due_at'),
            'status'=>'open',
        ]);
        return back()->with('success','Activity added.');
    }
    public function invoiceActivitiesComplete(Request $request, $id, $actId) {
        $tid = auth()->id();
        $act = CrmActivity::where('related_type','invoice')->where('related_id',$id)->findOrFail($actId);
        $act->update(['status'=>'closed','completed_at'=>now()]);
        return response()->json(['success'=>true]);
    }
    public function invoiceActivitiesDestroy(Request $request, $id, $actId) {
        $tid = auth()->id();
        $act = CrmActivity::where('related_type','invoice')->where('related_id',$id)->findOrFail($actId);
        $act->delete();
        return response()->json(['success'=>true]);
    }

    // ── Invoice Attachments ───────────────────────────────────────────────
    public function invoiceAttachmentsStore(Request $request, $id) {
        $tid  = auth()->id();
        $item = CrmInvoice::where('user_id',$tid)->findOrFail($id);
        $request->validate(['attachment'=>'required|file|max:10240']);
        $file = $request->file('attachment');
        $stored = $file->store('crm/invoice-attachments','public');
        \App\Models\CrmInvoiceAttachment::create([
            'invoice_id'=>$id,'user_id'=>$tid,
            'original_name'=>$file->getClientOriginalName(),
            'stored_name'=>$stored,'mime_type'=>$file->getMimeType(),
            'file_size'=>$file->getSize(),
        ]);
        return back()->with('success','Attachment uploaded.');
    }
    public function invoiceAttachmentsDownload(Request $request, $id, $attId) {
        $tid = auth()->id();
        $att = \App\Models\CrmInvoiceAttachment::where('invoice_id',$id)->findOrFail($attId);
        return \Storage::disk('public')->download($att->stored_name, $att->original_name);
    }
    public function invoiceAttachmentsDestroy(Request $request, $id, $attId) {
        $tid = auth()->id();
        $att = \App\Models\CrmInvoiceAttachment::where('invoice_id',$id)->findOrFail($attId);
        \Storage::disk('public')->delete($att->stored_name);
        $att->delete();
        return response()->json(['success'=>true]);
    }

    // ── Invoice Send Mail ─────────────────────────────────────────────────
    public function invoiceSendMail(Request $request, $id) {
        $tid  = auth()->id();
        $item = CrmInvoice::where('user_id',$tid)->findOrFail($id);
        $cfg  = \App\Models\CrmMailConfig::where('user_id',$tid)->where('is_active',1)->first();
        if (!$cfg) return back()->with('error','No active mail configuration.');
        try {
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host',     $cfg->mail_host);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port',     $cfg->mail_port);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', $cfg->mail_username);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', $cfg->mail_password);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption',$cfg->mail_encryption ?? 'tls');
            \Illuminate\Support\Facades\Config::set('mail.from.address', $cfg->from_address);
            \Illuminate\Support\Facades\Config::set('mail.from.name',    $cfg->from_name ?? 'CRM');
            \Mail::send([], [], function($msg) use ($request, $cfg, $item) {
                $msg->from($cfg->from_address, $cfg->from_name ?? 'Xenoraa CRM')
                    ->to($request->input('to_email'))
                    ->subject($request->input('subject'))
                    ->html($request->input('body_html'));
                if ($request->input('cc_email'))  $msg->cc($request->input('cc_email'));
                if ($request->input('bcc_email')) $msg->bcc($request->input('bcc_email'));
            });
            return back()->with('success','Email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error','Failed to send email: '.$e->getMessage());
        }
    }
    public function inventoryVendorsShow($id) {
        $tid  = auth()->id();
        $item = CrmVendor::where('user_id', $tid)->findOrFail($id);
        $notes            = CrmNote::where('notable_type','vendor')->where('notable_id',$id)->with('user')->latest()->get();
        $openActivities   = CrmActivity::where('related_type','vendor')->where('related_id',$id)->where('status','open')->with('user')->latest()->get();
        $closedActivities = CrmActivity::where('related_type','vendor')->where('related_id',$id)->where('status','closed')->with('user')->latest()->get();
        $attachments      = \App\Models\CrmVendorAttachment::where('vendor_id',$id)->latest()->get();
        $products         = CrmProduct::where('vendor_id',$id)->where('user_id',$tid)->orderBy('name')->get();
        $purchaseOrders   = CrmPurchaseOrder::where('vendor_id',$id)->where('user_id',$tid)->latest()->get();
        $contacts         = CrmContact::where('vendor_id',$id)->where('user_id',$tid)->orderBy('first_name')->get();
        $allProducts      = CrmProduct::where('user_id',$tid)->orderBy('name')->get();
        $allPurchaseOrders= CrmPurchaseOrder::where('user_id',$tid)->latest()->get();
        $allContacts      = CrmContact::where('user_id',$tid)->orderBy('first_name')->get();
        $mailConfig       = \App\Models\CrmMailConfig::where('user_id',$tid)->where('is_active',1)->first();
        $mailTemplates    = \App\Models\CrmMailTemplate::where('user_id',$tid)->get();
        return view('admin.crm2.inventory.view-vendor', compact(
            'item','notes','openActivities','closedActivities','attachments',
            'products','purchaseOrders','contacts',
            'allProducts','allPurchaseOrders','allContacts',
            'mailConfig','mailTemplates'
        ));
    }

    // ── Vendor Notes ──────────────────────────────────────────────────────
    public function vendorNotesStore(Request $request, $id) {
        $tid  = auth()->id();
        $item = CrmVendor::where('user_id',$tid)->findOrFail($id);
        CrmNote::create(['notable_type'=>'vendor','notable_id'=>$id,'user_id'=>$tid,'content'=>$request->input('content')]);
        return back()->with('success','Note added.');
    }

    // ── Vendor Activities ─────────────────────────────────────────────────
    public function vendorActivitiesStore(Request $request, $id) {
        $tid  = auth()->id();
        CrmVendor::where('user_id',$tid)->findOrFail($id);
        CrmActivity::create([
            'related_type'=>'vendor','related_id'=>$id,'user_id'=>$tid,
            'type'=>$request->input('type'),'subject'=>$request->input('subject'),
            'description'=>$request->input('description'),'due_at'=>$request->input('due_at'),
            'status'=>'open',
        ]);
        return back()->with('success','Activity added.');
    }
    public function vendorActivitiesComplete(Request $request, $id, $actId) {
        $act = CrmActivity::where('related_type','vendor')->where('related_id',$id)->findOrFail($actId);
        $act->update(['status'=>'closed','completed_at'=>now()]);
        return response()->json(['success'=>true]);
    }
    public function vendorActivitiesDestroy(Request $request, $id, $actId) {
        $act = CrmActivity::where('related_type','vendor')->where('related_id',$id)->findOrFail($actId);
        $act->delete();
        return response()->json(['success'=>true]);
    }

    // ── Vendor Attachments ────────────────────────────────────────────────
    public function vendorAttachmentsStore(Request $request, $id) {
        $tid  = auth()->id();
        CrmVendor::where('user_id',$tid)->findOrFail($id);
        $request->validate(['attachment'=>'required|file|max:10240']);
        $file = $request->file('attachment');
        $stored = $file->store('crm/vendor-attachments','public');
        \App\Models\CrmVendorAttachment::create([
            'vendor_id'=>$id,'user_id'=>$tid,
            'original_name'=>$file->getClientOriginalName(),
            'stored_name'=>$stored,'mime_type'=>$file->getMimeType(),
            'file_size'=>$file->getSize(),
        ]);
        return back()->with('success','Attachment uploaded.');
    }
    public function vendorAttachmentsDownload(Request $request, $id, $attId) {
        $att = \App\Models\CrmVendorAttachment::where('vendor_id',$id)->findOrFail($attId);
        return \Storage::disk('public')->download($att->stored_name, $att->original_name);
    }
    public function vendorAttachmentsDestroy(Request $request, $id, $attId) {
        $att = \App\Models\CrmVendorAttachment::where('vendor_id',$id)->findOrFail($attId);
        \Storage::disk('public')->delete($att->stored_name);
        $att->delete();
        return response()->json(['success'=>true]);
    }

    // ── Vendor Products ───────────────────────────────────────────────────
    public function vendorProductsAssign(Request $request, $id) {
        $tid = auth()->id();
        CrmVendor::where('user_id',$tid)->findOrFail($id);
        CrmProduct::where('user_id',$tid)->where('id',$request->input('product_id'))->update(['vendor_id'=>$id]);
        return back()->with('success','Product assigned to vendor.');
    }
    public function vendorProductsUnassign(Request $request, $id, $productId) {
        $tid = auth()->id();
        CrmProduct::where('user_id',$tid)->where('id',$productId)->where('vendor_id',$id)->update(['vendor_id'=>null]);
        return response()->json(['success'=>true]);
    }

    // ── Vendor Purchase Orders ────────────────────────────────────────────
    public function vendorPurchaseOrdersAssign(Request $request, $id) {
        $tid = auth()->id();
        CrmVendor::where('user_id',$tid)->findOrFail($id);
        CrmPurchaseOrder::where('user_id',$tid)->where('id',$request->input('po_id'))->update(['vendor_id'=>$id]);
        return back()->with('success','Purchase Order assigned to vendor.');
    }
    public function vendorPurchaseOrdersUnassign(Request $request, $id, $poId) {
        $tid = auth()->id();
        CrmPurchaseOrder::where('user_id',$tid)->where('id',$poId)->where('vendor_id',$id)->update(['vendor_id'=>null]);
        return response()->json(['success'=>true]);
    }

    // ── Vendor Contacts ───────────────────────────────────────────────────
    public function vendorContactsAssign(Request $request, $id) {
        $tid = auth()->id();
        CrmVendor::where('user_id',$tid)->findOrFail($id);
        CrmContact::where('user_id',$tid)->where('id',$request->input('contact_id'))->update(['vendor_id'=>$id]);
        return back()->with('success','Contact assigned to vendor.');
    }
    public function vendorContactsUnassign(Request $request, $id, $contactId) {
        $tid = auth()->id();
        CrmContact::where('user_id',$tid)->where('id',$contactId)->where('vendor_id',$id)->update(['vendor_id'=>null]);
        return response()->json(['success'=>true]);
    }

    // ── Vendor Send Mail ──────────────────────────────────────────────────
    public function vendorSendMail(Request $request, $id) {
        $tid  = auth()->id();
        $item = CrmVendor::where('user_id',$tid)->findOrFail($id);
        $cfg  = \App\Models\CrmMailConfig::where('user_id',$tid)->where('is_active',1)->first();
        if (!$cfg) return back()->with('error','No active mail configuration.');
        try {
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host',     $cfg->mail_host);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port',     $cfg->mail_port);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', $cfg->mail_username);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', $cfg->mail_password);
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption',$cfg->mail_encryption ?? 'tls');
            \Illuminate\Support\Facades\Config::set('mail.from.address', $cfg->from_address);
            \Illuminate\Support\Facades\Config::set('mail.from.name',    $cfg->from_name ?? 'CRM');
            \Mail::send([], [], function($msg) use ($request, $cfg) {
                $msg->from($cfg->from_address, $cfg->from_name ?? 'Xenoraa CRM')
                    ->to($request->input('to_email'))
                    ->subject($request->input('subject'))
                    ->html($request->input('body_html'));
                if ($request->input('cc_email'))  $msg->cc($request->input('cc_email'));
                if ($request->input('bcc_email')) $msg->bcc($request->input('bcc_email'));
            });
            return back()->with('success','Email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error','Failed to send email: '.$e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════
    // PRODUCTS SUB-MODULE
    // ══════════════════════════════════════════════════════════════
    public function inventoryProducts(Request $request) {
        $tid = $this->tenantId();
        $items = \App\Models\CrmProduct::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.products', compact('items'));
    }
    public function inventoryProductsCreate() {
        $tid = $this->tenantId();
        $vendors_list = CrmVendor::where('user_id', $tid)->orderBy('name')->get();
        $staff = \App\Models\User::where('id', $tid)->get();
        $price_books = CrmPriceBook::where("user_id", $tid)->orderBy("name")->get();
        return view("admin.crm2.inventory.create-product", compact("vendors_list", "staff", "price_books"));
    }
    public function inventoryProductsStore(Request $request) {
        $data = $request->except(['_token']);
        $data['user_id'] = $this->tenantId();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('crm/products', 'public');
        } elseif ($request->hasFile('product_image')) {
            $data['image'] = $request->file('product_image')->store('crm/products', 'public');
        }
        \App\Models\CrmProduct::create($data);
        return redirect()->route('admin.crm2.inventory.products')->with('success', 'Product created successfully.');
    }
    public function inventoryProductsShow($id) {
        $tid = $this->tenantId();
        $item = \App\Models\CrmProduct::where('user_id', $tid)->findOrFail($id);
        $vendors_list = CrmVendor::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.view-product', compact('item', 'vendors_list'));
    }
    public function inventoryProductsEdit($id) {
        $tid = auth()->id();
        $item = CrmProduct::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $vendors_list = CrmVendor::where("user_id", $tid)->orderBy("name")->get();
        $price_books = CrmPriceBook::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.edit-product', compact('item', 'staff', 'vendors_list', 'price_books'));
    }
    public function inventoryProductsUpdate(Request $request, $id) {
        $tid = $this->tenantId();
        $item = \App\Models\CrmProduct::where('user_id', $tid)->findOrFail($id);
        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('crm/products', 'public');
        } elseif ($request->hasFile('product_image')) {
            $data['image'] = $request->file('product_image')->store('crm/products', 'public');
        }
        $item->update($data);
        return redirect()->route('admin.crm2.inventory.products')->with('success', 'Product updated successfully.');
    }
    public function inventoryProductsDestroy($id) {
        $tid = $this->tenantId();
        \App\Models\CrmProduct::where('user_id', $tid)->findOrFail($id)->delete();
        return back()->with('success', 'Product deleted.');
    }

    public function inventoryProductsSearch(Request $request)
    {
        $tid = $this->tenantId();
        $q = $request->input('q', '');
        $products = \App\Models\CrmProduct::where('user_id', $tid)
            ->where('is_active', 1)
            ->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('product_code', 'like', "%{$q}%");
            })
            ->select('id','name','product_code','unit_price','tax','usage_unit','qty_in_stock')
            ->orderBy('name')
            ->limit(20)
            ->get();
        return response()->json($products);
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

    public function inventorySendMail(Request $request, string $type, int $id)
    {
        $tid = $this->tenantId();
        $item = match ($type) {
            'quote'          => CrmQuote::where('id', $id)->where('user_id', $tid)->firstOrFail(),
            'sales_order'    => CrmSalesOrder::where('id', $id)->where('user_id', $tid)->firstOrFail(),
            'purchase_order' => CrmPurchaseOrder::where('id', $id)->where('user_id', $tid)->firstOrFail(),
            'invoice'        => CrmInvoice::where('id', $id)->where('user_id', $tid)->firstOrFail(),
            default          => abort(404),
        };
        $to      = $request->input('to');
        $subject = $request->input('subject', ucwords(str_replace('_', ' ', $type)) . ' #' . $id);
        $body    = $request->input('body', '');
        if (!$to) return back()->with('error', 'Recipient email is required.');

        // Generate PDF using FPDF
        $pdfPath = null;
        try {
            $pdf = new \FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, ucwords(str_replace('_', ' ', $type)) . ' #' . $id, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Ln(4);
            $fields = $item->toArray();
            foreach ($fields as $key => $val) {
                if (in_array($key, ['id','user_id','created_at','updated_at','line_items'])) continue;
                if (is_array($val)) continue;
                $pdf->Cell(60, 7, ucwords(str_replace('_', ' ', $key)) . ':', 0, 0);
                $pdf->MultiCell(0, 7, (string)($val ?? ''));
            }
            if (!empty($item->line_items)) {
                $lines = is_string($item->line_items) ? json_decode($item->line_items, true) : $item->line_items;
                if (is_array($lines) && count($lines)) {
                    $pdf->Ln(4);
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->Cell(0, 7, 'Line Items:', 0, 1);
                    $pdf->SetFont('Arial', '', 10);
                    foreach ($lines as $i => $li) {
                        $pdf->Cell(0, 6, ($i+1) . '. ' . ($li['product'] ?? '') . '  Qty: ' . ($li['qty'] ?? '') . '  Price: ' . ($li['price'] ?? '') . '  Total: ' . ($li['total'] ?? ''), 0, 1);
                    }
                }
            }
            $pdfPath = storage_path('app/temp_inv_' . $type . '_' . $id . '_' . time() . '.pdf');
            $pdf->Output('F', $pdfPath);
        } catch (\Throwable $e) {
            $pdfPath = null;
        }

        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($to, $subject, $body, $pdfPath, $type, $id) {
                $message->to($to)
                    ->subject($subject)
                    ->html(nl2br(htmlspecialchars($body)) ?: '<p>' . $subject . '</p>');
                if ($pdfPath && file_exists($pdfPath)) {
                    $message->attach($pdfPath, ['as' => ucwords(str_replace('_', ' ', $type)) . '_' . $id . '.pdf', 'mime' => 'application/pdf']);
                }
            });
            if ($pdfPath && file_exists($pdfPath)) @unlink($pdfPath);
            return back()->with('success', 'Email sent successfully to ' . $to);
        } catch (\Throwable $e) {
            if ($pdfPath && file_exists($pdfPath)) @unlink($pdfPath);
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
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
        // All deals for Kanban view (unpaginated, with owner relation)
        $allDeals = CrmDeal::where('user_id', $tid)->with(['account','contact','owner'])->orderByDesc('created_at')->get();
        return view('admin.crm2.sales.deals', compact('deals', 'allDeals', 'accounts_list', 'contacts_list'));
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
        $tid   = $this->tenantId();
        $staff = User::where('id', $tid)->get();
        return view('admin.crm2.inventory.create-price-book', compact('staff'));
    }

    public function inventoryQuotes(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmQuote::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.quotes', compact('items'));
    }

    public function inventoryQuotesCreate()
    {
        $tid      = $this->tenantId();
        $staff    = User::where('id', $tid)->get();
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $deals    = CrmDeal::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.create-quote', compact('staff', 'accounts', 'contacts', 'deals'));
    }

    public function inventorySalesOrders(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmSalesOrder::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.sales-orders', compact('items'));
    }

    public function inventorySalesOrdersCreate()
    {
        $tid      = $this->tenantId();
        $staff    = User::where('id', $tid)->get();
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $deals    = CrmDeal::where('user_id', $tid)->orderBy('name')->get();
        $quotes   = CrmQuote::where('user_id', $tid)->orderBy('subject')->get();
        return view('admin.crm2.inventory.create-sales-order', compact('staff', 'accounts', 'contacts', 'deals', 'quotes'));
    }

    public function inventoryPurchaseOrders(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmPurchaseOrder::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.purchase-orders', compact('items'));
    }

    public function inventoryPurchaseOrdersCreate()
    {
        $tid      = $this->tenantId();
        $staff    = User::where('id', $tid)->get();
        $vendors  = CrmVendor::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.inventory.create-purchase-order', compact('staff', 'vendors', 'contacts'));
    }

    public function inventoryInvoices(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmInvoice::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.invoices', compact('items'));
    }

    public function inventoryInvoicesCreate()
    {
        $tid      = $this->tenantId();
        $staff    = User::where('id', $tid)->get();
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.inventory.create-invoice', compact('staff', 'accounts', 'contacts'));
    }

    public function inventoryVendors(Request $request)
    {
        $tid = $this->tenantId();
        $items = CrmVendor::where('user_id', $tid)->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.inventory.vendors', compact('items'));
    }

    public function inventoryVendorsCreate()
    {
        $tid   = $this->tenantId();
        $staff = User::where('id', $tid)->get();
        return view('admin.crm2.inventory.create-vendor', compact('staff'));
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

    public function salesLeadsEdit($id)
    {
        $tid = auth()->id();
        $item = CrmLead::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        return view('admin.crm2.sales.edit-lead', compact('item', 'staff'));
    }
    public function salesContactsEdit($id)
    {
        $tid = auth()->id();
        $item = CrmContact::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.sales.edit-contact', compact('item', 'staff', 'accounts_list'));
    }
    public function salesAccountsEdit($id)
    {
        $tid = auth()->id();
        $item = CrmAccount::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.sales.edit-account', compact('item', 'staff', 'accounts_list'));
    }
    public function salesDealsEdit($id)
    {
        $tid = auth()->id();
        $item = CrmDeal::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts_list = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.sales.edit-deal', compact('item', 'staff', 'accounts_list', 'contacts_list'));
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
        $tid = auth()->id();
        $item = CrmPriceBook::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        return view('admin.crm2.inventory.edit-price-book', compact('item', 'staff'));
    }
    public function inventoryQuotesEdit($id) {
        $tid = auth()->id();
        $item = CrmQuote::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $deals = CrmDeal::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.inventory.edit-quote', compact('item', 'staff', 'accounts', 'contacts', 'deals'));
    }
    public function inventorySalesOrdersEdit($id) {
        $tid = auth()->id();
        $item = CrmSalesOrder::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        $deals = CrmDeal::where('user_id', $tid)->orderBy('name')->get();
        $quotes = CrmQuote::where('user_id', $tid)->orderBy('subject')->get();
        return view('admin.crm2.inventory.edit-sales-order', compact('item', 'staff', 'accounts', 'contacts', 'deals', 'quotes'));
    }
    public function inventoryPurchaseOrdersEdit($id) {
        $tid = auth()->id();
        $item = CrmPurchaseOrder::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $vendors_list = CrmVendor::where("user_id", $tid)->orderBy("name")->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.inventory.edit-purchase-order', compact('item', 'staff', 'vendors', 'contacts'));
    }
    public function inventoryInvoicesEdit($id) {
        $tid = auth()->id();
        $item = CrmInvoice::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $tid)->orderBy('first_name')->get();
        return view('admin.crm2.inventory.edit-invoice', compact('item', 'staff', 'accounts', 'contacts'));
    }
    public function inventoryVendorsEdit($id) {
        $tid = auth()->id();
        $item = CrmVendor::where('user_id', $tid)->findOrFail($id);
        $staff = \App\Models\User::where('id', $tid)->get();
        return view('admin.crm2.inventory.edit-vendor', compact('item', 'staff'));
    }
    public function inventoryUpdate(Request $request, $type, $id) {
        $uid = auth()->id();
        $routeMap = [
            // plural keys (legacy)
            'price_books'    => 'admin.crm2.inventory.price-books',
            'quotes'         => 'admin.crm2.inventory.quotes',
            'sales_orders'   => 'admin.crm2.inventory.sales-orders',
            'purchase_orders'=> 'admin.crm2.inventory.purchase-orders',
            'invoices'       => 'admin.crm2.inventory.invoices',
            'vendors'        => 'admin.crm2.inventory.vendors',
            // singular keys (what edit forms actually send)
            'price_book'     => 'admin.crm2.inventory.price-books',
            'quote'          => 'admin.crm2.inventory.quotes',
            'sales_order'    => 'admin.crm2.inventory.sales-orders',
            'purchase_order' => 'admin.crm2.inventory.purchase-orders',
            'invoice'        => 'admin.crm2.inventory.invoices',
            'vendor'         => 'admin.crm2.inventory.vendors',
        ];
        // Parse line items JSON
        $lineItemsRaw = $request->input('line_items');
        $lineItems = null;
        if ($lineItemsRaw) {
            $decoded = json_decode($lineItemsRaw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $lineItems = $decoded;
            }
        }
        $commonFields = [
            'subtotal'        => (float) $request->input('subtotal', 0),
            'discount_amount' => (float) $request->input('discount_amount', 0),
            'tax_amount'      => (float) $request->input('tax_amount', 0),
            'adjustment'      => (float) $request->input('adjustment', 0),
            'grand_total'     => (float) $request->input('grand_total', 0),
            'total'           => (float) $request->input('grand_total', $request->input('total', 0)),
            'line_items'      => $lineItems,
        ];
        $addrFields = ['bill_country','bill_building','bill_street','bill_city','bill_state','bill_zip',
                       'ship_country','ship_building','ship_street','ship_city','ship_state','ship_zip'];
        switch ($type) {
            case 'price_books':
            case 'price_book':
                CrmPriceBook::where('user_id',$uid)->findOrFail($id)->update($request->only(['name','description','pricing_percentage','is_active']));
                break;
            case 'quotes':
            case 'quote':
                CrmQuote::where('user_id',$uid)->findOrFail($id)->update(array_merge(
                    $request->only(array_merge(['subject','account_id','contact_id','deal_id','stage','valid_until','terms','notes','team','carrier','owner_id'], $addrFields)),
                    $commonFields
                ));
                break;
            case 'sales_orders':
            case 'sales_order':
                CrmSalesOrder::where('user_id',$uid)->findOrFail($id)->update(array_merge(
                    $request->only(array_merge(['subject','account_id','contact_id','deal_id','quote_id','status','delivery_date','terms','notes','owner_id'], $addrFields)),
                    $commonFields
                ));
                break;
            case 'purchase_orders':
            case 'purchase_order':
                CrmPurchaseOrder::where('user_id',$uid)->findOrFail($id)->update(array_merge(
                    $request->only(array_merge(['subject','vendor_id','contact_id','status','expected_delivery','terms','notes','requisition_no','owner_id'], $addrFields)),
                    $commonFields
                ));
                break;
            case 'invoices':
            case 'invoice':
                $invData = array_merge(
                    $request->only(array_merge(['subject','account_id','contact_id','deal_id','sales_order_id','status','due_date','amount_paid','terms','notes','owner_id'], $addrFields)),
                    $commonFields
                );
                $invData['balance_due'] = $invData['total'] - (float) ($invData['amount_paid'] ?? 0);
                CrmInvoice::where('user_id',$uid)->findOrFail($id)->update($invData);
                break;
            case 'vendors':
            case 'vendor':
                CrmVendor::where('user_id',$uid)->findOrFail($id)->update($request->only(['name','email','phone','mobile','website','category','address','description','status','owner_id']));
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
        $uid = auth()->id();
        $account = CrmAccount::with(['owner','contacts','deals','leads'])->where('user_id', $uid)->findOrFail($id);
        $notes            = CrmNote::where('notable_type','account')->where('notable_id',$id)->with('user')->latest()->get();
        $deals            = CrmDeal::where('user_id',$uid)->where('account_id',$id)->get();
        $contacts         = CrmContact::where('user_id',$uid)->where('account_id',$id)->get();
        $openActivities   = CrmActivity::where('user_id',$uid)->where('related_type','account')->where('related_id',$id)->whereNotIn('status',['Completed','completed'])->get();
        $closedActivities = CrmActivity::where('user_id',$uid)->where('related_type','account')->where('related_id',$id)->whereIn('status',['Completed','completed'])->get();
        $accountProducts  = $account->products;
        $allProducts      = CrmProduct::where('user_id',$uid)->get();
        $quotes           = CrmQuote::where('user_id',$uid)->where('account_id',$id)->get();
        $salesOrders      = CrmSalesOrder::where('user_id',$uid)->where('account_id',$id)->get();
        $purchaseOrders   = collect(); // POs are linked to vendors, not accounts
        $invoices         = CrmInvoice::where('user_id',$uid)->where('account_id',$id)->get();
        $allDeals         = CrmDeal::where('user_id',$uid)->get();
        $allContacts      = CrmContact::where('user_id',$uid)->get();
        $allQuotes        = CrmQuote::where('user_id',$uid)->get();
        $allSalesOrders   = CrmSalesOrder::where('user_id',$uid)->get();
        $allInvoices      = CrmInvoice::where('user_id',$uid)->get();
        $leads            = $account->leads;
        $mailTemplates    = CrmMailTemplate::where('user_id', $uid)->where('is_active', true)->get();
        $mailConfig       = CrmMailConfig::where('user_id', $uid)->where('is_active', true)->first();
        $sentEmails       = CrmAccountEmail::where('user_id',$uid)->where('account_id',$id)->where('status','sent')->latest()->get();
        $draftEmails      = CrmAccountEmail::where('user_id',$uid)->where('account_id',$id)->where('status','draft')->latest()->get();
        $scheduledEmails  = CrmAccountEmail::where('user_id',$uid)->where('account_id',$id)->where('status','scheduled')->latest()->get();
        return view('admin.crm2.sales.view-account', compact(
            'account','notes','deals','contacts','openActivities','closedActivities',
            'accountProducts','allProducts','quotes','salesOrders','purchaseOrders','invoices',
            'allDeals','allContacts','allQuotes','allSalesOrders','allInvoices','leads',
            'mailTemplates','mailConfig','sentEmails','draftEmails','scheduledEmails'
        ));
    }

    // ─── ACCOUNT NOTES STORE ─────────────────────────────────────────────────────
    public function accountNotesStore(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:5000']);
        CrmNote::create([
            'user_id'      => auth()->id(),
            'notable_type' => 'account',
            'notable_id'   => $id,
            'content'      => $request->content,
        ]);
        return redirect()->route('admin.crm2.sales.accounts.show', $id)->with('success', 'Note added.');
    }

    // ─── ACCOUNT ACTIVITIES STORE ─────────────────────────────────────────────────
    public function accountActivitiesStore(Request $request, $id)
    {
        $request->validate(['subject' => 'required|string|max:255']);
        CrmActivity::create([
            'user_id'      => auth()->id(),
            'type'         => $request->input('type', 'Task'),
            'subject'      => $request->subject,
            'description'  => $request->description,
            'due_at'       => $request->due_at ?: null,
            'status'       => $request->input('status', 'Open'),
            'related_type' => 'account',
            'related_id'   => $id,
        ]);
        return redirect()->route('admin.crm2.sales.accounts.show', $id)->with('success', 'Activity added.');
    }

    // ─── ACCOUNT ASSIGN (AJAX) ────────────────────────────────────────────────────
    public function accountAssign(Request $request, $id)
    {
        $account = CrmAccount::where('user_id', auth()->id())->findOrFail($id);
        $type      = $request->input('type');
        $recordId  = $request->input('record_id');
        $action    = $request->input('action', 'assign'); // assign or unassign

        switch ($type) {
            case 'deal':
                CrmDeal::where('user_id', auth()->id())->where('id', $recordId)
                    ->update(['account_id' => $action === 'assign' ? $id : null]);
                break;
            case 'contact':
                CrmContact::where('user_id', auth()->id())->where('id', $recordId)
                    ->update(['account_id' => $action === 'assign' ? $id : null]);
                break;
            case 'product':
                if ($action === 'assign') {
                    $account->products()->syncWithoutDetaching([$recordId]);
                } else {
                    $account->products()->detach($recordId);
                }
                break;
            case 'quote':
                CrmQuote::where('user_id', auth()->id())->where('id', $recordId)
                    ->update(['account_id' => $action === 'assign' ? $id : null]);
                break;
            case 'sales-order':
                CrmSalesOrder::where('user_id', auth()->id())->where('id', $recordId)
                    ->update(['account_id' => $action === 'assign' ? $id : null]);
                break;
            case 'invoice':
                CrmInvoice::where('user_id', auth()->id())->where('id', $recordId)
                    ->update(['account_id' => $action === 'assign' ? $id : null]);
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Unknown type']);
        }
        return response()->json(['success' => true]);
    }

    // ─── ACCOUNT DESTROY ─────────────────────────────────────────────────────────
    public function salesAccountsDestroy($id)
    {
        CrmAccount::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.sales.accounts')->with('success', 'Account deleted.');
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


    // ══════════════════════════════════════════════════════════════
    // INTEGRATIONS — MAIL CONFIG
    // ══════════════════════════════════════════════════════════════

    public function integrationMailConfig()
    {
        $tid    = $this->tenantId();
        $config = CrmMailConfig::where('user_id', $tid)->first();
        return view('admin.crm2.integrations.mail-config', compact('config'));
    }

    public function integrationMailConfigSave(Request $request)
    {
        $tid = $this->tenantId();
        $data = $request->only([
            'mail_driver', 'mail_host', 'mail_port', 'mail_username',
            'mail_encryption', 'from_address', 'from_name', 'reply_to', 'is_active',
        ]);
        $data['user_id']   = $tid;
        $data['is_active'] = $request->boolean('is_active');

        // Only update password if a new one is provided
        if ($request->filled('mail_password')) {
            $data['mail_password'] = $request->input('mail_password');
        }

        $config = CrmMailConfig::updateOrCreate(['user_id' => $tid], $data);

        return redirect()->route('admin.crm2.integrations.mail-config')
            ->with('success', 'Mail configuration saved successfully.');
    }

    public function integrationMailConfigTest(Request $request)
    {
        $tid    = $this->tenantId();
        $config = CrmMailConfig::where('user_id', $tid)->first();

        if (!$config || !$config->mail_host) {
            return response()->json(['success' => false, 'message' => 'No mail configuration found. Please save your settings first.']);
        }

        try {
            // Temporarily override mail config for this request
            config([
                'mail.mailers.smtp.host'       => $config->mail_host,
                'mail.mailers.smtp.port'       => $config->mail_port,
                'mail.mailers.smtp.username'   => $config->mail_username,
                'mail.mailers.smtp.password'   => $config->mail_password,
                'mail.mailers.smtp.encryption' => $config->mail_encryption === 'none' ? null : $config->mail_encryption,
                'mail.from.address'            => $config->from_address,
                'mail.from.name'               => $config->from_name ?? 'CRM Test',
            ]);

            $toEmail = $request->input('test_email', $config->from_address);

            \Illuminate\Support\Facades\Mail::raw(
                'This is a test email from your CRM Mail Configuration. If you received this, your SMTP settings are working correctly!',
                function ($message) use ($config, $toEmail) {
                    $message->to($toEmail)
                            ->subject('CRM Mail Config Test — ' . now()->format('d M Y H:i'));
                    if ($config->reply_to) {
                        $message->replyTo($config->reply_to);
                    }
                }
            );

            // Mark as verified
            $config->update(['verified_at' => now(), 'last_error' => null]);

            return response()->json(['success' => true, 'message' => 'Test email sent successfully to ' . $toEmail]);
        } catch (\Exception $e) {
            $config->update(['last_error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    // ══════════════════════════════════════════════════════════════
    // SETTINGS — MAIL TEMPLATES
    // ══════════════════════════════════════════════════════════════

    public function settingsMailTemplates(Request $request)
    {
        $tid       = $this->tenantId();
        $type      = $request->input('type');
        $q         = CrmMailTemplate::where('user_id', $tid);
        if ($type) $q->where('type', $type);
        $templates = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        $types     = CrmMailTemplate::types();
        return view('admin.crm2.settings.mail-templates', compact('templates', 'types', 'type'));
    }

    public function settingsMailTemplatesCreate()
    {
        $types = CrmMailTemplate::types();
        return view('admin.crm2.settings.create-mail-template', compact('types'));
    }

    public function settingsMailTemplatesStore(Request $request)
    {
        $tid  = $this->tenantId();
        $data = $request->except(['_token']);
        $data['user_id']    = $tid;
        $data['show_logo']  = $request->boolean('show_logo');
        $data['show_footer']= $request->boolean('show_footer');
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('crm/mail-logos', 'public');
        }

        // If set as default, unset others of same type
        if (!empty($data['is_default'])) {
            CrmMailTemplate::where('user_id', $tid)->where('type', $data['type'])->update(['is_default' => false]);
        }

        CrmMailTemplate::create($data);

        return redirect()->route('admin.crm2.settings.mail-templates')
            ->with('success', 'Mail template created successfully.');
    }

    public function settingsMailTemplatesShow($id)
    {
        $tid      = $this->tenantId();
        $template = CrmMailTemplate::where('user_id', $tid)->findOrFail($id);
        $types    = CrmMailTemplate::types();
        return view('admin.crm2.settings.view-mail-template', compact('template', 'types'));
    }

    public function settingsMailTemplatesEdit($id)
    {
        $tid      = $this->tenantId();
        $template = CrmMailTemplate::where('user_id', $tid)->findOrFail($id);
        $types    = CrmMailTemplate::types();
        return view('admin.crm2.settings.edit-mail-template', compact('template', 'types'));
    }

    public function settingsMailTemplatesUpdate(Request $request, $id)
    {
        $tid      = $this->tenantId();
        $template = CrmMailTemplate::where('user_id', $tid)->findOrFail($id);
        $data     = $request->except(['_token', '_method', 'logo']);
        $data['show_logo']  = $request->boolean('show_logo');
        $data['show_footer']= $request->boolean('show_footer');
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($template->logo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($template->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('crm/mail-logos', 'public');
        }

        // If set as default, unset others of same type
        if (!empty($data['is_default'])) {
            CrmMailTemplate::where('user_id', $tid)->where('type', $data['type'] ?? $template->type)
                ->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $template->update($data);

        return redirect()->route('admin.crm2.settings.mail-templates')
            ->with('success', 'Mail template updated successfully.');
    }

    public function settingsMailTemplatesDestroy($id)
    {
        $tid      = $this->tenantId();
        $template = CrmMailTemplate::where('user_id', $tid)->findOrFail($id);
        if ($template->logo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($template->logo_path);
        }
        $template->delete();
        return redirect()->route('admin.crm2.settings.mail-templates')
            ->with('success', 'Template deleted.');
    }

    public function settingsMailTemplatesPreview($id)
    {
        $tid      = $this->tenantId();
        $template = CrmMailTemplate::where('user_id', $tid)->findOrFail($id);
        return view('admin.crm2.settings.preview-mail-template', compact('template'));
    }

    public function settingsMailTemplatesSeedDefaults()
    {
        $tid = $this->tenantId();

        // Only seed if no templates exist yet
        if (CrmMailTemplate::where('user_id', $tid)->count() > 0) {
            return redirect()->route('admin.crm2.settings.mail-templates')
                ->with('info', 'Templates already exist. Seeding skipped.');
        }

        $defaults = $this->getDefaultMailTemplates($tid);
        foreach ($defaults as $tpl) {
            CrmMailTemplate::create($tpl);
        }

        return redirect()->route('admin.crm2.settings.mail-templates')
            ->with('success', count($defaults) . ' default templates created.');
    }

    private function getDefaultMailTemplates(int $tid): array
    {
        $base = [
            'user_id'         => $tid,
            'primary_color'   => '#6366f1',
            'secondary_color' => '#f8fafc',
            'font_family'     => 'Inter, sans-serif',
            'show_logo'       => true,
            'show_footer'     => true,
            'is_default'      => true,
            'is_active'       => true,
        ];

        return [
            array_merge($base, [
                'name'        => 'Invoice Template',
                'type'        => 'invoice',
                'subject'     => 'Invoice {{invoice_number}} from {{company_name}}',
                'header_text' => '{{company_name}}',
                'footer_text' => 'Thank you for your business. Payment is due by {{due_date}}.',
                'body_html'   => $this->invoiceTemplateHtml(),
            ]),
            array_merge($base, [
                'name'        => 'Quote Template',
                'type'        => 'quote',
                'subject'     => 'Quotation {{quote_number}} from {{company_name}}',
                'header_text' => '{{company_name}}',
                'footer_text' => 'This quote is valid until {{valid_until}}. Contact us for any queries.',
                'body_html'   => $this->quoteTemplateHtml(),
            ]),
            array_merge($base, [
                'name'        => 'Sales Order Template',
                'type'        => 'sales_order',
                'subject'     => 'Sales Order {{so_number}} Confirmation',
                'header_text' => '{{company_name}}',
                'footer_text' => 'Thank you for your order. Expected delivery: {{delivery_date}}.',
                'body_html'   => $this->salesOrderTemplateHtml(),
            ]),
            array_merge($base, [
                'name'        => 'Purchase Order Template',
                'type'        => 'purchase_order',
                'subject'     => 'Purchase Order {{po_number}} from {{company_name}}',
                'header_text' => '{{company_name}}',
                'footer_text' => 'Please confirm receipt of this purchase order.',
                'body_html'   => $this->purchaseOrderTemplateHtml(),
            ]),
            array_merge($base, [
                'name'        => 'General Template',
                'type'        => 'general',
                'subject'     => 'Message from {{company_name}}',
                'header_text' => '{{company_name}}',
                'footer_text' => 'This email was sent from {{company_name}}. Please do not reply to this email.',
                'body_html'   => $this->generalTemplateHtml(),
            ]),
            array_merge($base, [
                'name'        => 'All-in-One Template',
                'type'        => 'all_in_one',
                'subject'     => 'Documents from {{company_name}}',
                'header_text' => '{{company_name}}',
                'footer_text' => 'Please find the attached documents. Contact us for any queries.',
                'body_html'   => $this->allInOneTemplateHtml(),
            ]),
        ];
    }

    private function invoiceTemplateHtml(): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;font-family:{{font_family}};color:#1e293b;">
  <tr><td style="background:{{primary_color}};padding:32px 40px;border-radius:12px 12px 0 0;">
    <table width="100%"><tr>
      <td><h1 style="margin:0;color:#fff;font-size:1.6rem;font-weight:700;">INVOICE</h1>
          <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:0.9rem;">{{invoice_number}}</p></td>
      <td align="right"><p style="margin:0;color:rgba(255,255,255,0.9);font-size:0.85rem;">Date: {{invoice_date}}<br>Due: {{due_date}}</p></td>
    </tr></table>
  </td></tr>
  <tr><td style="background:#fff;padding:32px 40px;">
    <table width="100%"><tr>
      <td width="50%" style="vertical-align:top;">
        <p style="margin:0 0 4px;font-size:0.75rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Billed To</p>
        <p style="margin:0;font-weight:600;font-size:1rem;">{{client_name}}</p>
        <p style="margin:4px 0 0;font-size:0.875rem;color:#475569;">{{client_address}}</p>
      </td>
      <td width="50%" align="right" style="vertical-align:top;">
        <p style="margin:0 0 4px;font-size:0.75rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">From</p>
        <p style="margin:0;font-weight:600;font-size:1rem;">{{company_name}}</p>
        <p style="margin:4px 0 0;font-size:0.875rem;color:#475569;">{{company_address}}</p>
      </td>
    </tr></table>
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0;">
    <table width="100%" style="border-collapse:collapse;">
      <thead><tr style="background:{{secondary_color}};">
        <th style="padding:10px 12px;text-align:left;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Item</th>
        <th style="padding:10px 12px;text-align:center;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Qty</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Rate</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Amount</th>
      </tr></thead>
      <tbody>{{line_items}}</tbody>
    </table>
    <table width="100%" style="margin-top:16px;"><tr>
      <td></td>
      <td width="220" style="background:{{secondary_color}};padding:16px;border-radius:8px;">
        <table width="100%">
          <tr><td style="font-size:0.875rem;color:#475569;">Subtotal</td><td align="right" style="font-size:0.875rem;">{{subtotal}}</td></tr>
          <tr><td style="font-size:0.875rem;color:#475569;">Tax</td><td align="right" style="font-size:0.875rem;">{{tax_amount}}</td></tr>
          <tr><td style="font-size:0.875rem;color:#475569;">Discount</td><td align="right" style="font-size:0.875rem;">{{discount_amount}}</td></tr>
          <tr><td colspan="2"><hr style="border:none;border-top:1px solid #e2e8f0;margin:8px 0;"></td></tr>
          <tr><td style="font-weight:700;font-size:1rem;color:{{primary_color}};">Total</td><td align="right" style="font-weight:700;font-size:1rem;color:{{primary_color}};">{{total}}</td></tr>
        </table>
      </td>
    </tr></table>
    {{#notes}}<div style="margin-top:24px;padding:16px;background:#f8fafc;border-left:4px solid {{primary_color}};border-radius:0 8px 8px 0;">
      <p style="margin:0 0 4px;font-size:0.75rem;color:#64748b;text-transform:uppercase;font-weight:600;">Notes</p>
      <p style="margin:0;font-size:0.875rem;color:#475569;">{{notes}}</p>
    </div>{{/notes}}
  </td></tr>
</table>';
    }

    private function quoteTemplateHtml(): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;font-family:{{font_family}};color:#1e293b;">
  <tr><td style="background:{{primary_color}};padding:32px 40px;border-radius:12px 12px 0 0;">
    <table width="100%"><tr>
      <td><h1 style="margin:0;color:#fff;font-size:1.6rem;font-weight:700;">QUOTATION</h1>
          <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:0.9rem;">{{quote_number}}</p></td>
      <td align="right"><p style="margin:0;color:rgba(255,255,255,0.9);font-size:0.85rem;">Date: {{quote_date}}<br>Valid Until: {{valid_until}}</p></td>
    </tr></table>
  </td></tr>
  <tr><td style="background:#fff;padding:32px 40px;">
    <p style="margin:0 0 24px;font-size:0.95rem;color:#475569;">Dear <strong>{{client_name}}</strong>,<br><br>Thank you for your interest. Please find below our quotation for your reference.</p>
    <table width="100%" style="border-collapse:collapse;">
      <thead><tr style="background:{{secondary_color}};">
        <th style="padding:10px 12px;text-align:left;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Description</th>
        <th style="padding:10px 12px;text-align:center;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Qty</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Unit Price</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Total</th>
      </tr></thead>
      <tbody>{{line_items}}</tbody>
    </table>
    <table width="100%" style="margin-top:16px;"><tr><td></td>
      <td width="220" style="background:{{secondary_color}};padding:16px;border-radius:8px;">
        <table width="100%">
          <tr><td style="font-size:0.875rem;color:#475569;">Subtotal</td><td align="right" style="font-size:0.875rem;">{{subtotal}}</td></tr>
          <tr><td style="font-size:0.875rem;color:#475569;">Tax</td><td align="right" style="font-size:0.875rem;">{{tax_amount}}</td></tr>
          <tr><td colspan="2"><hr style="border:none;border-top:1px solid #e2e8f0;margin:8px 0;"></td></tr>
          <tr><td style="font-weight:700;font-size:1rem;color:{{primary_color}};">Grand Total</td><td align="right" style="font-weight:700;font-size:1rem;color:{{primary_color}};">{{total}}</td></tr>
        </table>
      </td>
    </tr></table>
    <p style="margin:24px 0 0;font-size:0.875rem;color:#475569;">We look forward to working with you. Please do not hesitate to contact us if you have any questions.</p>
  </td></tr>
</table>';
    }

    private function salesOrderTemplateHtml(): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;font-family:{{font_family}};color:#1e293b;">
  <tr><td style="background:{{primary_color}};padding:32px 40px;border-radius:12px 12px 0 0;">
    <table width="100%"><tr>
      <td><h1 style="margin:0;color:#fff;font-size:1.6rem;font-weight:700;">SALES ORDER</h1>
          <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:0.9rem;">{{so_number}}</p></td>
      <td align="right"><p style="margin:0;color:rgba(255,255,255,0.9);font-size:0.85rem;">Order Date: {{order_date}}<br>Delivery: {{delivery_date}}</p></td>
    </tr></table>
  </td></tr>
  <tr><td style="background:#fff;padding:32px 40px;">
    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:16px;margin-bottom:24px;">
      <p style="margin:0;font-size:0.875rem;color:#166534;"><strong>Order Confirmed</strong> — Status: <strong>{{status}}</strong></p>
    </div>
    <table width="100%"><tr>
      <td width="50%" style="vertical-align:top;">
        <p style="margin:0 0 4px;font-size:0.75rem;color:#64748b;text-transform:uppercase;">Ship To</p>
        <p style="margin:0;font-weight:600;">{{client_name}}</p>
        <p style="margin:4px 0 0;font-size:0.875rem;color:#475569;">{{shipping_address}}</p>
      </td>
      <td width="50%" align="right" style="vertical-align:top;">
        <p style="margin:0 0 4px;font-size:0.75rem;color:#64748b;text-transform:uppercase;">Bill To</p>
        <p style="margin:0;font-weight:600;">{{client_name}}</p>
        <p style="margin:4px 0 0;font-size:0.875rem;color:#475569;">{{billing_address}}</p>
      </td>
    </tr></table>
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0;">
    <table width="100%" style="border-collapse:collapse;">
      <thead><tr style="background:{{secondary_color}};">
        <th style="padding:10px 12px;text-align:left;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Item</th>
        <th style="padding:10px 12px;text-align:center;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Qty</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Price</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Total</th>
      </tr></thead>
      <tbody>{{line_items}}</tbody>
    </table>
    <table width="100%" style="margin-top:16px;"><tr><td></td>
      <td width="220" style="background:{{secondary_color}};padding:16px;border-radius:8px;">
        <table width="100%">
          <tr><td style="font-size:0.875rem;color:#475569;">Subtotal</td><td align="right">{{subtotal}}</td></tr>
          <tr><td style="font-size:0.875rem;color:#475569;">Tax</td><td align="right">{{tax_amount}}</td></tr>
          <tr><td colspan="2"><hr style="border:none;border-top:1px solid #e2e8f0;margin:8px 0;"></td></tr>
          <tr><td style="font-weight:700;color:{{primary_color}};">Order Total</td><td align="right" style="font-weight:700;color:{{primary_color}};">{{total}}</td></tr>
        </table>
      </td>
    </tr></table>
  </td></tr>
</table>';
    }

    private function purchaseOrderTemplateHtml(): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;font-family:{{font_family}};color:#1e293b;">
  <tr><td style="background:{{primary_color}};padding:32px 40px;border-radius:12px 12px 0 0;">
    <table width="100%"><tr>
      <td><h1 style="margin:0;color:#fff;font-size:1.6rem;font-weight:700;">PURCHASE ORDER</h1>
          <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:0.9rem;">{{po_number}}</p></td>
      <td align="right"><p style="margin:0;color:rgba(255,255,255,0.9);font-size:0.85rem;">Date: {{po_date}}<br>Expected: {{expected_delivery}}</p></td>
    </tr></table>
  </td></tr>
  <tr><td style="background:#fff;padding:32px 40px;">
    <p style="margin:0 0 24px;font-size:0.95rem;color:#475569;">To: <strong>{{vendor_name}}</strong><br><br>Please supply the following items as per the terms below.</p>
    <table width="100%" style="border-collapse:collapse;">
      <thead><tr style="background:{{secondary_color}};">
        <th style="padding:10px 12px;text-align:left;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Item</th>
        <th style="padding:10px 12px;text-align:center;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Qty</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Unit Price</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Total</th>
      </tr></thead>
      <tbody>{{line_items}}</tbody>
    </table>
    <table width="100%" style="margin-top:16px;"><tr><td></td>
      <td width="220" style="background:{{secondary_color}};padding:16px;border-radius:8px;">
        <table width="100%">
          <tr><td style="font-size:0.875rem;color:#475569;">Subtotal</td><td align="right">{{subtotal}}</td></tr>
          <tr><td style="font-size:0.875rem;color:#475569;">Tax</td><td align="right">{{tax_amount}}</td></tr>
          <tr><td colspan="2"><hr style="border:none;border-top:1px solid #e2e8f0;margin:8px 0;"></td></tr>
          <tr><td style="font-weight:700;color:{{primary_color}};">PO Total</td><td align="right" style="font-weight:700;color:{{primary_color}};">{{total}}</td></tr>
        </table>
      </td>
    </tr></table>
    <div style="margin-top:24px;padding:16px;background:#fefce8;border:1px solid #fde047;border-radius:8px;">
      <p style="margin:0;font-size:0.875rem;color:#713f12;"><strong>Authorised by:</strong> {{authorised_by}} &nbsp;|&nbsp; <strong>Status:</strong> {{status}}</p>
    </div>
  </td></tr>
</table>';
    }

    private function generalTemplateHtml(): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;font-family:{{font_family}};color:#1e293b;">
  <tr><td style="background:{{primary_color}};padding:32px 40px;border-radius:12px 12px 0 0;">
    <h1 style="margin:0;color:#fff;font-size:1.4rem;font-weight:700;">{{email_subject}}</h1>
    <p style="margin:8px 0 0;color:rgba(255,255,255,0.8);font-size:0.875rem;">{{company_name}}</p>
  </td></tr>
  <tr><td style="background:#fff;padding:32px 40px;">
    <p style="margin:0 0 16px;font-size:0.95rem;color:#475569;">Dear <strong>{{recipient_name}}</strong>,</p>
    <div style="font-size:0.95rem;color:#475569;line-height:1.7;">{{message_body}}</div>
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:32px 0 24px;">
    <p style="margin:0;font-size:0.875rem;color:#475569;">Warm regards,<br><strong>{{sender_name}}</strong><br>{{company_name}}</p>
  </td></tr>
</table>';
    }

    private function allInOneTemplateHtml(): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;font-family:{{font_family}};color:#1e293b;">
  <tr><td style="background:{{primary_color}};padding:32px 40px;border-radius:12px 12px 0 0;">
    <table width="100%"><tr>
      <td><h1 style="margin:0;color:#fff;font-size:1.4rem;font-weight:700;">{{document_title}}</h1>
          <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:0.875rem;">{{company_name}}</p></td>
      <td align="right"><p style="margin:0;color:rgba(255,255,255,0.9);font-size:0.85rem;">Date: {{document_date}}</p></td>
    </tr></table>
  </td></tr>
  <tr><td style="background:#fff;padding:32px 40px;">
    <p style="margin:0 0 24px;font-size:0.95rem;color:#475569;">Dear <strong>{{recipient_name}}</strong>,<br><br>{{intro_message}}</p>
    <div style="background:{{secondary_color}};border-radius:8px;padding:20px;margin-bottom:24px;">
      <table width="100%">
        <tr><td style="font-size:0.8rem;color:#64748b;text-transform:uppercase;font-weight:600;padding-bottom:8px;" colspan="2">Document Summary</td></tr>
        <tr><td style="font-size:0.875rem;color:#475569;padding:4px 0;">Reference No.</td><td align="right" style="font-size:0.875rem;font-weight:600;">{{reference_number}}</td></tr>
        <tr><td style="font-size:0.875rem;color:#475569;padding:4px 0;">Document Type</td><td align="right" style="font-size:0.875rem;font-weight:600;">{{document_type}}</td></tr>
        <tr><td style="font-size:0.875rem;color:#475569;padding:4px 0;">Amount</td><td align="right" style="font-size:0.875rem;font-weight:700;color:{{primary_color}};">{{total_amount}}</td></tr>
        <tr><td style="font-size:0.875rem;color:#475569;padding:4px 0;">Status</td><td align="right" style="font-size:0.875rem;font-weight:600;">{{status}}</td></tr>
      </table>
    </div>
    <table width="100%" style="border-collapse:collapse;">
      <thead><tr style="background:{{secondary_color}};">
        <th style="padding:10px 12px;text-align:left;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Item</th>
        <th style="padding:10px 12px;text-align:center;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Qty</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Price</th>
        <th style="padding:10px 12px;text-align:right;font-size:0.8rem;color:#64748b;font-weight:600;text-transform:uppercase;">Total</th>
      </tr></thead>
      <tbody>{{line_items}}</tbody>
    </table>
    <p style="margin:24px 0 0;font-size:0.875rem;color:#475569;">{{closing_message}}</p>
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0;">
    <p style="margin:0;font-size:0.875rem;color:#475569;">{{sender_name}}<br>{{company_name}}</p>
  </td></tr>
</table>';
    }


    // ─── ACCOUNT EMAILS: LIST (AJAX) ─────────────────────────────────────────────
    public function accountEmailsList(Request $request, $id)
    {
        $uid    = auth()->id();
        $status = $request->input('status', 'sent'); // sent | draft | scheduled
        $source = $request->input('source', 'crm');  // crm | contact

        $query = CrmAccountEmail::where('user_id', $uid)
            ->where('account_id', $id)
            ->where('status', $status)
            ->with('template')
            ->latest();

        if ($source === 'contact') {
            // Emails associated with contacts linked to this account
            $contactEmails = \App\Models\CrmContact::where('user_id', $uid)
                ->where('account_id', $id)
                ->pluck('email')
                ->filter()
                ->values();
            $query->whereIn('to_email', $contactEmails);
        }

        $emails = $query->get();

        return response()->json(['success' => true, 'emails' => $emails]);
    }

    // ─── ACCOUNT EMAILS: COMPOSE / SEND / DRAFT / SCHEDULE ───────────────────────
    public function accountEmailsStore(Request $request, $id)
    {
        $uid     = auth()->id();
        $account = CrmAccount::where('user_id', $uid)->findOrFail($id);
        $action  = $request->input('action', 'send'); // send | draft | schedule

        $request->validate([
            'to_email' => 'required|email',
            'subject'  => 'required|string|max:500',
            'body_html'=> 'required|string',
        ]);

        $status     = $action === 'draft' ? 'draft' : ($action === 'schedule' ? 'scheduled' : 'sent');
        $scheduledAt = $action === 'schedule' ? $request->input('scheduled_at') : null;
        $sentAt      = $action === 'send' ? now() : null;
        $errorMsg    = null;

        if ($action === 'send') {
            // Try to send via configured mail config
            $mailConfig = CrmMailConfig::where('user_id', $uid)->where('is_active', true)->first();
            if ($mailConfig) {
                try {
                    $decryptedPassword = $mailConfig->mail_password;
                    config([
                        'mail.default'                     => 'smtp',
                        'mail.mailers.smtp.host'           => $mailConfig->mail_host,
                        'mail.mailers.smtp.port'           => $mailConfig->mail_port,
                        'mail.mailers.smtp.username'       => $mailConfig->mail_username,
                        'mail.mailers.smtp.password'       => $decryptedPassword,
                        'mail.mailers.smtp.encryption'     => $mailConfig->mail_encryption ?: 'tls',
                        'mail.from.address'                => $mailConfig->from_address,
                        'mail.from.name'                   => $mailConfig->from_name,
                    ]);

                    \Illuminate\Support\Facades\Mail::html($request->body_html, function ($msg) use ($request, $mailConfig) {
                        $msg->to($request->to_email)
                            ->subject($request->subject)
                            ->from($mailConfig->from_address, $mailConfig->from_name);
                        if ($request->cc_email)  $msg->cc($request->cc_email);
                        if ($request->bcc_email) $msg->bcc($request->bcc_email);
                        if ($mailConfig->reply_to) $msg->replyTo($mailConfig->reply_to);
                    });
                } catch (\Exception $e) {
                    $status   = 'draft';
                    $sentAt   = null;
                    $errorMsg = $e->getMessage();
                }
            } else {
                // No mail config — save as draft with error note
                $status   = 'draft';
                $sentAt   = null;
                $errorMsg = 'No active mail configuration found. Email saved as draft.';
            }
        }

        $email = CrmAccountEmail::create([
            'user_id'          => $uid,
            'account_id'       => $id,
            'mail_template_id' => $request->input('mail_template_id'),
            'status'           => $status,
            'to_email'         => $request->to_email,
            'cc_email'         => $request->cc_email,
            'bcc_email'        => $request->bcc_email,
            'subject'          => $request->subject,
            'body_html'        => $request->body_html,
            'from_name'        => $request->input('from_name'),
            'from_email'       => $request->input('from_email'),
            'scheduled_at'     => $scheduledAt,
            'sent_at'          => $sentAt,
            'error_message'    => $errorMsg,
        ]);

        return response()->json([
            'success'  => true,
            'status'   => $status,
            'email_id' => $email->id,
            'message'  => $status === 'sent'
                ? 'Email sent successfully.'
                : ($status === 'scheduled'
                    ? 'Email scheduled successfully.'
                    : 'Email saved as draft.' . ($errorMsg ? ' Note: ' . $errorMsg : '')),
        ]);
    }

    // ─── ACCOUNT EMAILS: DELETE ───────────────────────────────────────────────────
    public function accountEmailsDestroy($id, $emailId)
    {
        $email = CrmAccountEmail::where('user_id', auth()->id())
            ->where('account_id', $id)
            ->findOrFail($emailId);
        $email->delete();
        return response()->json(['success' => true]);
    }

    // ─── ACCOUNT EMAILS: GET TEMPLATE BODY (AJAX) ────────────────────────────────
    public function accountEmailsGetTemplate(Request $request, $id)
    {
        $uid        = auth()->id();
        $templateId = $request->input('template_id');
        $template   = CrmMailTemplate::where('user_id', $uid)->findOrFail($templateId);

        // Get account data for variable substitution
        $account = CrmAccount::where('user_id', $uid)->findOrFail($id);

        $body = $template->body_html;
        // Replace common variables
        $vars = [
            '{{account_name}}' => $account->account_name ?? $account->name ?? '',
            '{{company_name}}' => $account->account_name ?? '',
            '{{date}}'         => now()->format('d M Y'),
            '{{sender_name}}' => auth()->user()->name ?? '',
        ];
        foreach ($vars as $k => $v) {
            $body = str_replace($k, $v, $body);
        }

        return response()->json([
            'success'  => true,
            'subject'  => $template->subject,
            'body_html'=> $body,
            'template' => [
                'id'      => $template->id,
                'name'    => $template->name,
                'subject' => $template->subject,
            ],
        ]);
    }

    // ── Price Book Note Destroy ────────────────────────────────────────────────
    public function priceBookNoteDestroy(Request $request, $id, $noteId)
    {
        $tid = auth()->id();
        CrmPriceBook::where('user_id', $tid)->findOrFail($id);
        $note = \App\Models\CrmNote::where('notable_type','price_book')->where('notable_id',$id)->where('user_id',$tid)->findOrFail($noteId);
        $note->delete();
        return response()->json(['success'=>true]);
    }

    // ── Quote Note Destroy ────────────────────────────────────────────────────
    public function quoteNoteDestroy(Request $request, $id, $noteId)
    {
        $tid = auth()->id();
        CrmQuote::where('user_id', $tid)->findOrFail($id);
        $note = \App\Models\CrmNote::where('notable_type','quote')->where('notable_id',$id)->where('user_id',$tid)->findOrFail($noteId);
        $note->delete();
        return response()->json(['success'=>true]);
    }

    // ── Sales Order Note Destroy ──────────────────────────────────────────────
    public function soNoteDestroy(Request $request, $id, $noteId)
    {
        $tid = auth()->id();
        CrmSalesOrder::where('user_id', $tid)->findOrFail($id);
        $note = \App\Models\CrmNote::where('notable_type','sales_order')->where('notable_id',$id)->where('user_id',$tid)->findOrFail($noteId);
        $note->delete();
        return response()->json(['success'=>true]);
    }

    // ── Purchase Order Note Destroy ───────────────────────────────────────────
    public function poNoteDestroy(Request $request, $id, $noteId)
    {
        $tid = auth()->id();
        CrmPurchaseOrder::where('user_id', $tid)->findOrFail($id);
        $note = \App\Models\CrmNote::where('notable_type','purchase_order')->where('notable_id',$id)->where('user_id',$tid)->findOrFail($noteId);
        $note->delete();
        return response()->json(['success'=>true]);
    }

    // ── Invoice Note Destroy ──────────────────────────────────────────────────
    public function invoiceNoteDestroy(Request $request, $id, $noteId)
    {
        $tid = auth()->id();
        CrmInvoice::where('user_id', $tid)->findOrFail($id);
        $note = \App\Models\CrmNote::where('notable_type','invoice')->where('notable_id',$id)->where('user_id',$tid)->findOrFail($noteId);
        $note->delete();
        return response()->json(['success'=>true]);
    }

    // ── Vendor Note Destroy ───────────────────────────────────────────────────
    public function vendorNoteDestroy(Request $request, $id, $noteId)
    {
        $tid = auth()->id();
        CrmVendor::where('user_id', $tid)->findOrFail($id);
        $note = \App\Models\CrmNote::where('notable_type','vendor')->where('notable_id',$id)->where('user_id',$tid)->findOrFail($noteId);
        $note->delete();
        return response()->json(['success'=>true]);
    }

    // ─── ACCOUNT EMAILS: UPDATE DRAFT ─────────────────────────────────────────────
    public function accountEmailsUpdate(Request $request, $id, $emailId)
    {
        $email = CrmAccountEmail::where('user_id', auth()->id())
            ->where('account_id', $id)
            ->findOrFail($emailId);

        $email->update($request->only([
            'to_email', 'cc_email', 'bcc_email', 'subject', 'body_html',
            'scheduled_at', 'mail_template_id',
        ]));

        return response()->json(['success' => true]);
    }


    // ══════════════════════════════════════════════════════════════
    // QUOTE CONVERSION METHODS
    // ══════════════════════════════════════════════════════════════

    /**
     * Convert a Quote into a Sales Order.
     * All matching fields are copied; quote_id is set to the source quote's ID.
     * Redirects to the new Sales Order's view page.
     */
    public function quoteConvertToSalesOrder($id)
    {
        $tid   = auth()->id();
        $quote = CrmQuote::where('user_id', $tid)->findOrFail($id);

        $so = CrmSalesOrder::create([
            'user_id'         => $tid,
            'quote_id'        => $quote->id,
            'account_id'      => $quote->account_id,
            'contact_id'      => $quote->contact_id,
            'deal_id'         => $quote->deal_id,
            'owner_id'        => $quote->owner_id,
            'subject'         => $quote->subject,
            'status'          => 'Created',
            'carrier'         => $quote->carrier,
            'terms'           => $quote->terms,
            'notes'           => $quote->notes,
            'subtotal'        => $quote->subtotal,
            'discount_amount' => $quote->discount_amount,
            'tax_amount'      => $quote->tax_amount,
            'adjustment'      => $quote->adjustment,
            'grand_total'     => $quote->grand_total,
            'total'           => $quote->total,
            'line_items'      => $quote->line_items,
            'bill_country'    => $quote->bill_country,
            'bill_building'   => $quote->bill_building,
            'bill_street'     => $quote->bill_street,
            'bill_city'       => $quote->bill_city,
            'bill_state'      => $quote->bill_state,
            'bill_zip'        => $quote->bill_zip,
            'ship_country'    => $quote->ship_country,
            'ship_building'   => $quote->ship_building,
            'ship_street'     => $quote->ship_street,
            'ship_city'       => $quote->ship_city,
            'ship_state'      => $quote->ship_state,
            'ship_zip'        => $quote->ship_zip,
            'so_number'       => 'SO-' . strtoupper(Str::random(8)),
        ]);

        return redirect()
            ->route('admin.crm2.inventory.sales-orders.show', $so->id)
            ->with('success', 'Quote converted to Sales Order successfully.');
    }

    /**
     * Convert a Quote directly into an Invoice.
     * All matching fields are copied; sales_order_id is left null.
     * Redirects to the new Invoice's view page.
     */
    public function quoteConvertToInvoice($id)
    {
        $tid   = auth()->id();
        $quote = CrmQuote::where('user_id', $tid)->findOrFail($id);

        $inv = CrmInvoice::create([
            'user_id'         => $tid,
            'sales_order_id'  => null,
            'account_id'      => $quote->account_id,
            'contact_id'      => $quote->contact_id,
            'deal_id'         => $quote->deal_id,
            'owner_id'        => $quote->owner_id,
            'subject'         => $quote->subject,
            'status'          => 'Draft',
            'invoice_date'    => now()->toDateString(),
            'terms'           => $quote->terms,
            'notes'           => $quote->notes,
            'subtotal'        => $quote->subtotal,
            'discount_amount' => $quote->discount_amount,
            'tax_amount'      => $quote->tax_amount,
            'adjustment'      => $quote->adjustment,
            'grand_total'     => $quote->grand_total,
            'total'           => $quote->total,
            'amount_paid'     => 0,
            'line_items'      => $quote->line_items,
            'bill_country'    => $quote->bill_country,
            'bill_building'   => $quote->bill_building,
            'bill_street'     => $quote->bill_street,
            'bill_city'       => $quote->bill_city,
            'bill_state'      => $quote->bill_state,
            'bill_zip'        => $quote->bill_zip,
            'ship_country'    => $quote->ship_country,
            'ship_building'   => $quote->ship_building,
            'ship_street'     => $quote->ship_street,
            'ship_city'       => $quote->ship_city,
            'ship_state'      => $quote->ship_state,
            'ship_zip'        => $quote->ship_zip,
            'invoice_number'  => 'INV-' . strtoupper(Str::random(8)),
        ]);

        return redirect()
            ->route('admin.crm2.inventory.invoices.show', $inv->id)
            ->with('success', 'Quote converted to Invoice successfully.');
    }

    // ══════════════════════════════════════════════════════════════
    // SALES ORDER CONVERSION METHOD
    // ══════════════════════════════════════════════════════════════

    /**
     * Convert a Sales Order into an Invoice.
     * All matching fields are copied; sales_order_id is set to the source SO's ID.
     * Redirects to the new Invoice's view page.
     */
    public function soConvertToInvoice($id)
    {
        $tid = auth()->id();
        $so  = CrmSalesOrder::where('user_id', $tid)->findOrFail($id);

        $inv = CrmInvoice::create([
            'user_id'         => $tid,
            'sales_order_id'  => $so->id,
            'account_id'      => $so->account_id,
            'contact_id'      => $so->contact_id,
            'deal_id'         => $so->deal_id,
            'owner_id'        => $so->owner_id,
            'subject'         => $so->subject,
            'status'          => 'Draft',
            'invoice_date'    => now()->toDateString(),
            'terms'           => $so->terms,
            'notes'           => $so->notes,
            'subtotal'        => $so->subtotal,
            'discount_amount' => $so->discount_amount,
            'tax_amount'      => $so->tax_amount,
            'adjustment'      => $so->adjustment,
            'grand_total'     => $so->grand_total,
            'total'           => $so->total,
            'amount_paid'     => 0,
            'line_items'      => $so->line_items,
            'bill_country'    => $so->bill_country,
            'bill_building'   => $so->bill_building,
            'bill_street'     => $so->bill_street,
            'bill_city'       => $so->bill_city,
            'bill_state'      => $so->bill_state,
            'bill_zip'        => $so->bill_zip,
            'ship_country'    => $so->ship_country,
            'ship_building'   => $so->ship_building,
            'ship_street'     => $so->ship_street,
            'ship_city'       => $so->ship_city,
            'ship_state'      => $so->ship_state,
            'ship_zip'        => $so->ship_zip,
            'invoice_number'  => 'INV-' . strtoupper(\Illuminate\Support\Str::random(8)),
        ]);

        return redirect()
            ->route('admin.crm2.inventory.invoices.show', $inv->id)
            ->with('success', 'Sales Order converted to Invoice successfully.');
    }

    // ══════════════════════════════════════════════════════════════
    // QUOTE — BULK DELETE
    // ══════════════════════════════════════════════════════════════
    public function quotesBulkDelete(\Illuminate\Http\Request $request)
    {
        $tid = auth()->id();
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('admin.crm2.inventory.quotes')
                ->with('error', 'No quotes selected.');
        }
        $deleted = \App\Models\CrmQuote::where('user_id', $tid)
            ->whereIn('id', $ids)
            ->delete();
        return redirect()->route('admin.crm2.inventory.quotes')
            ->with('success', $deleted . ' quote(s) deleted successfully.');
    }

    // ══════════════════════════════════════════════════════════════
    // QUOTE — CLONE
    // ══════════════════════════════════════════════════════════════
    public function quoteClone(int $id)
    {
        $tid   = auth()->id();
        $quote = \App\Models\CrmQuote::where('user_id', $tid)->findOrFail($id);

        $clone = $quote->replicate();
        $clone->quote_number = 'QT-' . strtoupper(\Illuminate\Support\Str::random(8));
        $clone->stage        = 'draft';
        $clone->created_at   = now();
        $clone->updated_at   = now();
        $clone->save();

        return redirect()->route('admin.crm2.inventory.quotes.show', $clone->id)
            ->with('success', 'Quote cloned successfully. You are now viewing the clone.');
    }

    // ══════════════════════════════════════════════════════════════
    // QUOTE — SINGLE DELETE (from view page)
    // ══════════════════════════════════════════════════════════════
    public function quoteDestroy(int $id)
    {
        $tid = auth()->id();
        \App\Models\CrmQuote::where('user_id', $tid)->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.inventory.quotes')
            ->with('success', 'Quote deleted successfully.');
    }

    // ══════════════════════════════════════════════════════════════
    // QUOTE — EXPORT TO PDF
    // ══════════════════════════════════════════════════════════════
    public function quoteExportPdf(int $id)
    {
        $tid   = auth()->id();
        $quote = \App\Models\CrmQuote::with(['account','contact','owner'])
            ->where('user_id', $tid)->findOrFail($id);

        $lineItems = is_array($quote->line_items) ? $quote->line_items : json_decode($quote->line_items ?? '[]', true);

        // Build HTML for PDF
        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
        $html .= '<style>
body{font-family:Arial,sans-serif;font-size:12px;color:#222;margin:0;padding:20px;}
h1{font-size:20px;margin-bottom:4px;}
.meta{color:#666;font-size:11px;margin-bottom:16px;}
.section-title{font-size:13px;font-weight:bold;border-bottom:1px solid #ddd;padding-bottom:4px;margin:16px 0 8px;}
table{width:100%;border-collapse:collapse;margin-bottom:12px;}
th{background:#f3f4f6;text-align:left;padding:6px 8px;font-size:11px;border:1px solid #e5e7eb;}
td{padding:6px 8px;border:1px solid #e5e7eb;font-size:11px;}
.totals{float:right;width:260px;}
.totals td:first-child{font-weight:bold;}
.grand{font-size:14px;font-weight:bold;background:#f3f4f6;}
</style></head><body>';
        $html .= '<h1>Quote: ' . htmlspecialchars($quote->quote_number) . '</h1>';
        $html .= '<div class="meta">Subject: ' . htmlspecialchars($quote->subject ?? '') . ' &nbsp;|&nbsp; Stage: ' . ucfirst($quote->stage ?? '') . ' &nbsp;|&nbsp; Valid Until: ' . ($quote->valid_until ? $quote->valid_until->format('d M Y') : '—') . '</div>';

        // Account / Contact
        $html .= '<div class="section-title">Details</div>';
        $html .= '<table><tr><th>Account</th><th>Contact</th><th>Owner</th><th>Carrier</th></tr>';
        $html .= '<tr><td>' . htmlspecialchars($quote->account?->name ?? '—') . '</td>';
        $html .= '<td>' . htmlspecialchars($quote->contact ? ($quote->contact->first_name . ' ' . $quote->contact->last_name) : '—') . '</td>';
        $html .= '<td>' . htmlspecialchars($quote->owner?->name ?? '—') . '</td>';
        $html .= '<td>' . htmlspecialchars($quote->carrier ?? '—') . '</td></tr></table>';

        // Line items
        $html .= '<div class="section-title">Line Items</div>';
        $html .= '<table><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Discount</th><th>Tax %</th><th>Total</th></tr>';
        foreach ($lineItems as $li) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($li['product'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($li['qty'] ?? '') . '</td>';
            $html .= '<td>₹' . number_format((float)($li['price'] ?? 0), 2) . '</td>';
            $html .= '<td>₹' . number_format((float)($li['discount'] ?? 0), 2) . '</td>';
            $html .= '<td>' . htmlspecialchars($li['tax'] ?? '0') . '%</td>';
            $html .= '<td>₹' . number_format((float)($li['total'] ?? 0), 2) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        // Totals
        $html .= '<table class="totals"><tr><td>Subtotal</td><td>₹' . number_format((float)$quote->subtotal, 2) . '</td></tr>';
        $html .= '<tr><td>Discount</td><td>₹' . number_format((float)$quote->discount_amount, 2) . '</td></tr>';
        $html .= '<tr><td>Tax</td><td>₹' . number_format((float)$quote->tax_amount, 2) . '</td></tr>';
        $html .= '<tr><td>Adjustment</td><td>₹' . number_format((float)$quote->adjustment, 2) . '</td></tr>';
        $html .= '<tr class="grand"><td>Grand Total</td><td>₹' . number_format((float)$quote->grand_total, 2) . '</td></tr></table>';

        if ($quote->terms) {
            $html .= '<div class="section-title">Terms & Conditions</div><p>' . nl2br(htmlspecialchars($quote->terms)) . '</p>';
        }
        if ($quote->notes) {
            $html .= '<div class="section-title">Notes</div><p>' . nl2br(htmlspecialchars($quote->notes)) . '</p>';
        }
        $html .= '</body></html>';

        // Generate PDF using wkhtmltopdf or dompdf fallback
        $filename = 'Quote_' . $quote->quote_number . '_' . date('Ymd') . '.pdf';
        $tmpHtml  = sys_get_temp_dir() . '/' . uniqid('qt_') . '.html';
        $tmpPdf   = sys_get_temp_dir() . '/' . uniqid('qt_') . '.pdf';
        file_put_contents($tmpHtml, $html);

        // Try wkhtmltopdf first
        $wk = shell_exec('which wkhtmltopdf 2>/dev/null');
        if ($wk) {
            shell_exec('wkhtmltopdf --quiet --page-size A4 ' . escapeshellarg($tmpHtml) . ' ' . escapeshellarg($tmpPdf) . ' 2>/dev/null');
        }

        // Fallback: use manus-md-to-pdf via system call if wkhtmltopdf not available
        if (!file_exists($tmpPdf) || filesize($tmpPdf) < 100) {
            // Use PHP's built-in HTML→PDF via Dompdf if available
            if (class_exists('\Dompdf\Dompdf')) {
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                file_put_contents($tmpPdf, $dompdf->output());
            } else {
                // Last resort: return HTML as download
                return response($html, 200, [
                    'Content-Type'        => 'text/html',
                    'Content-Disposition' => 'attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"',
                ]);
            }
        }

        $pdf = file_get_contents($tmpPdf);
        @unlink($tmpHtml);
        @unlink($tmpPdf);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // PRICE BOOKS — BULK DELETE / CLONE / DESTROY / EXPORT PDF
    // ══════════════════════════════════════════════════════════════
    public function priceBooksBulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->route('admin.crm2.inventory.price-books')->with('error', 'No price books selected.');
        $deleted = \App\Models\CrmPriceBook::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.inventory.price-books')->with('success', $deleted . ' price book(s) deleted successfully.');
    }

    public function priceBookClone(int $id)
    {
        $item = \App\Models\CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $clone = $item->replicate();
        $clone->name = $item->name . ' (Copy)';
        $clone->created_at = $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.inventory.price-books.show', $clone->id)->with('success', 'Price book cloned successfully.');
    }

    public function priceBookDestroy(int $id)
    {
        \App\Models\CrmPriceBook::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.inventory.price-books')->with('success', 'Price book deleted successfully.');
    }

    public function priceBookExportPdf(int $id)
    {
        $item = \App\Models\CrmPriceBook::where('user_id', auth()->id())->findOrFail($id);
        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-family:Arial,sans-serif;font-size:12px;color:#222;padding:20px;}h1{font-size:20px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #e5e7eb;padding:6px 8px;font-size:11px;}th{background:#f3f4f6;}</style></head><body>';
        $html .= '<h1>Price Book: ' . htmlspecialchars($item->name) . '</h1>';
        $html .= '<table><tr><th>Field</th><th>Value</th></tr>';
        $html .= '<tr><td>Pricing Model</td><td>' . htmlspecialchars($item->pricing_model ?? '—') . '</td></tr>';
        $html .= '<tr><td>Pricing %</td><td>' . ($item->pricing_percentage ? $item->pricing_percentage . '%' : '—') . '</td></tr>';
        $html .= '<tr><td>Currency</td><td>' . htmlspecialchars($item->currency ?? 'INR') . '</td></tr>';
        $html .= '<tr><td>Active</td><td>' . ($item->is_active ? 'Yes' : 'No') . '</td></tr>';
        $html .= '<tr><td>Description</td><td>' . nl2br(htmlspecialchars($item->description ?? '')) . '</td></tr>';
        $html .= '</table></body></html>';
        return $this->_renderPdf($html, 'PriceBook_' . $item->id . '_' . date('Ymd') . '.pdf');
    }

    // ══════════════════════════════════════════════════════════════
    // SALES ORDERS — BULK DELETE / CLONE / DESTROY / EXPORT PDF
    // ══════════════════════════════════════════════════════════════
    public function salesOrdersBulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->route('admin.crm2.inventory.sales-orders')->with('error', 'No sales orders selected.');
        $deleted = \App\Models\CrmSalesOrder::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.inventory.sales-orders')->with('success', $deleted . ' sales order(s) deleted successfully.');
    }

    public function salesOrderClone(int $id)
    {
        $item = \App\Models\CrmSalesOrder::where('user_id', auth()->id())->findOrFail($id);
        $clone = $item->replicate();
        $clone->so_number = 'SO-' . strtoupper(\Illuminate\Support\Str::random(8));
        $clone->status = 'draft';
        $clone->created_at = $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.inventory.sales-orders.show', $clone->id)->with('success', 'Sales order cloned successfully.');
    }

    public function salesOrderDestroy(int $id)
    {
        \App\Models\CrmSalesOrder::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.inventory.sales-orders')->with('success', 'Sales order deleted successfully.');
    }

    public function salesOrderExportPdf(int $id)
    {
        $item = \App\Models\CrmSalesOrder::with(['account','contact','owner'])->where('user_id', auth()->id())->findOrFail($id);
        $lineItems = is_array($item->line_items) ? $item->line_items : json_decode($item->line_items ?? '[]', true);
        $html = $this->_buildInventoryPdfHtml('Sales Order', $item->so_number, $item->subject, $item->status, $item->account?->name, $item->contact ? ($item->contact->first_name . ' ' . $item->contact->last_name) : null, $item->owner?->name, $lineItems, $item->subtotal, $item->discount_amount, $item->tax_amount, $item->adjustment, $item->grand_total, $item->terms, $item->notes);
        return $this->_renderPdf($html, 'SalesOrder_' . $item->so_number . '_' . date('Ymd') . '.pdf');
    }

    // ══════════════════════════════════════════════════════════════
    // PURCHASE ORDERS — BULK DELETE / CLONE / DESTROY / EXPORT PDF
    // ══════════════════════════════════════════════════════════════
    public function purchaseOrdersBulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->route('admin.crm2.inventory.purchase-orders')->with('error', 'No purchase orders selected.');
        $deleted = \App\Models\CrmPurchaseOrder::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.inventory.purchase-orders')->with('success', $deleted . ' purchase order(s) deleted successfully.');
    }

    public function purchaseOrderClone(int $id)
    {
        $item = \App\Models\CrmPurchaseOrder::where('user_id', auth()->id())->findOrFail($id);
        $clone = $item->replicate();
        $clone->po_number = 'PO-' . strtoupper(\Illuminate\Support\Str::random(8));
        $clone->status = 'draft';
        $clone->created_at = $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.inventory.purchase-orders.show', $clone->id)->with('success', 'Purchase order cloned successfully.');
    }

    public function purchaseOrderDestroy(int $id)
    {
        \App\Models\CrmPurchaseOrder::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.inventory.purchase-orders')->with('success', 'Purchase order deleted successfully.');
    }

    public function purchaseOrderExportPdf(int $id)
    {
        $item = \App\Models\CrmPurchaseOrder::with(['vendor','owner'])->where('user_id', auth()->id())->findOrFail($id);
        $lineItems = is_array($item->line_items) ? $item->line_items : json_decode($item->line_items ?? '[]', true);
        $html = $this->_buildInventoryPdfHtml('Purchase Order', $item->po_number, $item->subject, $item->status, $item->vendor?->name, null, $item->owner?->name, $lineItems, $item->subtotal, $item->discount_amount, $item->tax_amount, $item->adjustment, $item->grand_total, $item->terms, $item->notes);
        return $this->_renderPdf($html, 'PurchaseOrder_' . $item->po_number . '_' . date('Ymd') . '.pdf');
    }

    // ══════════════════════════════════════════════════════════════
    // INVOICES — BULK DELETE / CLONE / DESTROY / EXPORT PDF
    // ══════════════════════════════════════════════════════════════
    public function invoicesBulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->route('admin.crm2.inventory.invoices')->with('error', 'No invoices selected.');
        $deleted = \App\Models\CrmInvoice::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.inventory.invoices')->with('success', $deleted . ' invoice(s) deleted successfully.');
    }

    public function invoiceClone(int $id)
    {
        $item = \App\Models\CrmInvoice::where('user_id', auth()->id())->findOrFail($id);
        $clone = $item->replicate();
        $clone->invoice_number = 'INV-' . strtoupper(\Illuminate\Support\Str::random(8));
        $clone->status = 'draft';
        $clone->invoice_date = now()->toDateString();
        $clone->amount_paid = 0;
        $clone->created_at = $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.inventory.invoices.show', $clone->id)->with('success', 'Invoice cloned successfully.');
    }

    public function invoiceDestroy(int $id)
    {
        \App\Models\CrmInvoice::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.inventory.invoices')->with('success', 'Invoice deleted successfully.');
    }

    public function invoiceExportPdf(int $id)
    {
        $item = \App\Models\CrmInvoice::with(['account','contact','owner'])->where('user_id', auth()->id())->findOrFail($id);
        $lineItems = is_array($item->line_items) ? $item->line_items : json_decode($item->line_items ?? '[]', true);
        $html = $this->_buildInventoryPdfHtml('Invoice', $item->invoice_number ?? 'INV-' . $item->id, $item->subject, $item->status, $item->account?->name, $item->contact ? ($item->contact->first_name . ' ' . $item->contact->last_name) : null, $item->owner?->name, $lineItems, $item->subtotal, $item->discount_amount, $item->tax_amount, $item->adjustment, $item->grand_total, $item->terms, $item->notes);
        return $this->_renderPdf($html, 'Invoice_' . ($item->invoice_number ?? $item->id) . '_' . date('Ymd') . '.pdf');
    }

    // ══════════════════════════════════════════════════════════════
    // VENDORS — BULK DELETE / CLONE / DESTROY / EXPORT PDF
    // ══════════════════════════════════════════════════════════════
    public function vendorsBulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->route('admin.crm2.inventory.vendors')->with('error', 'No vendors selected.');
        $deleted = \App\Models\CrmVendor::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.inventory.vendors')->with('success', $deleted . ' vendor(s) deleted successfully.');
    }

    public function vendorClone(int $id)
    {
        $item = \App\Models\CrmVendor::where('user_id', auth()->id())->findOrFail($id);
        $clone = $item->replicate();
        $clone->name = $item->name . ' (Copy)';
        $clone->created_at = $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.inventory.vendors.show', $clone->id)->with('success', 'Vendor cloned successfully.');
    }

    public function vendorDestroy(int $id)
    {
        \App\Models\CrmVendor::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('admin.crm2.inventory.vendors')->with('success', 'Vendor deleted successfully.');
    }

    public function vendorExportPdf(int $id)
    {
        $item = \App\Models\CrmVendor::where('user_id', auth()->id())->findOrFail($id);
        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-family:Arial,sans-serif;font-size:12px;color:#222;padding:20px;}h1{font-size:20px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #e5e7eb;padding:6px 8px;font-size:11px;}th{background:#f3f4f6;}</style></head><body>';
        $html .= '<h1>Vendor: ' . htmlspecialchars($item->name) . '</h1>';
        $html .= '<table><tr><th>Field</th><th>Value</th></tr>';
        $html .= '<tr><td>Email</td><td>' . htmlspecialchars($item->email ?? '—') . '</td></tr>';
        $html .= '<tr><td>Phone</td><td>' . htmlspecialchars($item->phone ?? '—') . '</td></tr>';
        $html .= '<tr><td>City</td><td>' . htmlspecialchars($item->city ?? '—') . '</td></tr>';
        $html .= '<tr><td>Category</td><td>' . htmlspecialchars($item->category ?? '—') . '</td></tr>';
        $html .= '<tr><td>Status</td><td>' . ucfirst($item->status ?? 'Active') . '</td></tr>';
        $html .= '<tr><td>Website</td><td>' . htmlspecialchars($item->website ?? '—') . '</td></tr>';
        $html .= '</table></body></html>';
        return $this->_renderPdf($html, 'Vendor_' . $item->id . '_' . date('Ymd') . '.pdf');
    }

    // ══════════════════════════════════════════════════════════════
    // SHARED PDF HELPERS
    // ══════════════════════════════════════════════════════════════
    private function _buildInventoryPdfHtml(string $type, string $number, ?string $subject, ?string $status, ?string $account, ?string $contact, ?string $owner, array $lineItems, $subtotal, $discount, $tax, $adjustment, $grandTotal, ?string $terms, ?string $notes): string
    {
        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
        $html .= '<style>body{font-family:Arial,sans-serif;font-size:12px;color:#222;margin:0;padding:20px;}h1{font-size:20px;margin-bottom:4px;}.meta{color:#666;font-size:11px;margin-bottom:16px;}.section-title{font-size:13px;font-weight:bold;border-bottom:1px solid #ddd;padding-bottom:4px;margin:16px 0 8px;}table{width:100%;border-collapse:collapse;margin-bottom:12px;}th{background:#f3f4f6;text-align:left;padding:6px 8px;font-size:11px;border:1px solid #e5e7eb;}td{padding:6px 8px;border:1px solid #e5e7eb;font-size:11px;}.totals{float:right;width:260px;}.totals td:first-child{font-weight:bold;}.grand{font-size:14px;font-weight:bold;background:#f3f4f6;}</style></head><body>';
        $html .= '<h1>' . $type . ': ' . htmlspecialchars($number) . '</h1>';
        $html .= '<div class="meta">Subject: ' . htmlspecialchars($subject ?? '') . ' &nbsp;|&nbsp; Status: ' . ucfirst($status ?? '') . '</div>';
        $html .= '<div class="section-title">Details</div>';
        $html .= '<table><tr><th>Account</th><th>Contact</th><th>Owner</th></tr>';
        $html .= '<tr><td>' . htmlspecialchars($account ?? '—') . '</td><td>' . htmlspecialchars($contact ?? '—') . '</td><td>' . htmlspecialchars($owner ?? '—') . '</td></tr></table>';
        $html .= '<div class="section-title">Line Items</div>';
        $html .= '<table><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Discount</th><th>Tax %</th><th>Total</th></tr>';
        foreach ($lineItems as $li) {
            $html .= '<tr><td>' . htmlspecialchars($li['product'] ?? '') . '</td><td>' . htmlspecialchars($li['qty'] ?? '') . '</td><td>₹' . number_format((float)($li['price'] ?? 0), 2) . '</td><td>₹' . number_format((float)($li['discount'] ?? 0), 2) . '</td><td>' . htmlspecialchars($li['tax'] ?? '0') . '%</td><td>₹' . number_format((float)($li['total'] ?? 0), 2) . '</td></tr>';
        }
        $html .= '</table>';
        $html .= '<table class="totals"><tr><td>Subtotal</td><td>₹' . number_format((float)$subtotal, 2) . '</td></tr>';
        $html .= '<tr><td>Discount</td><td>₹' . number_format((float)$discount, 2) . '</td></tr>';
        $html .= '<tr><td>Tax</td><td>₹' . number_format((float)$tax, 2) . '</td></tr>';
        $html .= '<tr><td>Adjustment</td><td>₹' . number_format((float)$adjustment, 2) . '</td></tr>';
        $html .= '<tr class="grand"><td>Grand Total</td><td>₹' . number_format((float)$grandTotal, 2) . '</td></tr></table>';
        if ($terms) $html .= '<div class="section-title">Terms & Conditions</div><p>' . nl2br(htmlspecialchars($terms)) . '</p>';
        if ($notes) $html .= '<div class="section-title">Notes</div><p>' . nl2br(htmlspecialchars($notes)) . '</p>';
        $html .= '</body></html>';
        return $html;
    }

    private function _renderPdf(string $html, string $filename)
    {
        $tmpHtml = sys_get_temp_dir() . '/' . uniqid('inv_') . '.html';
        $tmpPdf  = sys_get_temp_dir() . '/' . uniqid('inv_') . '.pdf';
        file_put_contents($tmpHtml, $html);
        $wk = shell_exec('which wkhtmltopdf 2>/dev/null');
        if ($wk) shell_exec('wkhtmltopdf --quiet --page-size A4 ' . escapeshellarg($tmpHtml) . ' ' . escapeshellarg($tmpPdf) . ' 2>/dev/null');
        if (!file_exists($tmpPdf) || filesize($tmpPdf) < 100) {
            if (class_exists('\Dompdf\Dompdf')) {
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                file_put_contents($tmpPdf, $dompdf->output());
            } else {
                return response($html, 200, ['Content-Type' => 'text/html', 'Content-Disposition' => 'attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"']);
            }
        }
        $pdf = file_get_contents($tmpPdf);
        @unlink($tmpHtml); @unlink($tmpPdf);
        return response($pdf, 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="' . $filename . '"']);
    }

    // ─── Leads: Clone ──────────────────────────────────────────────────────────
    public function salesLeadsClone($id)
    {
        $lead = \App\Models\CrmLead::where('user_id', auth()->id())->findOrFail($id);
        $clone = $lead->replicate();
        $clone->first_name = ($lead->first_name ?? $lead->name ?? 'Lead') . ' (Copy)';
        $clone->lead_status = 'New';
        $clone->is_converted = false;
        $clone->converted_date = null;
        $clone->created_at = now();
        $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.sales.leads.show', $clone->id)
            ->with('success', 'Lead cloned successfully.');
    }

    // ─── Leads: Bulk Delete ────────────────────────────────────────────────────
    public function salesLeadsBulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['error' => 'No leads selected.'], 422);
        }
        \App\Models\CrmLead::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'deleted' => count($ids)]);
    }

    // ─── Leads: Bulk Task ─────────────────────────────────────────────────────
    public function salesLeadsBulkTask(Request $request)
    {
        $ids = $request->input('ids', []);
        $subject = $request->input('subject', 'Follow-up Task');
        $dueDate = $request->input('due_date', now()->addDay()->format('Y-m-d'));
        $assignedTo = $request->input('assigned_to', auth()->id());

        if (empty($ids)) {
            return response()->json(['error' => 'No leads selected.'], 422);
        }

        $created = 0;
        foreach ($ids as $leadId) {
            $lead = \App\Models\CrmLead::where('user_id', auth()->id())->find($leadId);
            if (!$lead) continue;
            \App\Models\CrmActivity::create([
                'user_id'     => auth()->id(),
                'lead_id'     => $lead->id,
                'type'        => 'Task',
                'subject'     => $subject,
                'due_date'    => $dueDate,
                'assigned_to' => $assignedTo,
                'status'      => 'open',
                'description' => 'Bulk task created from Leads listing.',
            ]);
            $created++;
        }

        return response()->json(['success' => true, 'created' => $created]);
    }

    // ─── Contacts: Clone ─────────────────────────────────────────────────────────
    public function salesContactsClone($id)
    {
        $contact = CrmContact::where('user_id', auth()->id())->findOrFail($id);
        $clone = $contact->replicate();
        $clone->first_name = ($contact->first_name ?? 'Contact') . ' (Copy)';
        $clone->status = 'active';
        $clone->created_at = now();
        $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.sales.contacts.show', $clone->id)
            ->with('success', 'Contact cloned successfully.');
    }

    // ─── Contacts: Bulk Delete ────────────────────────────────────────────────────
    public function salesContactsBulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No contacts selected.');
        }
        CrmContact::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.sales.contacts')->with('success', count($ids).' contact(s) deleted.');
    }

    // ─── Contacts: Bulk Task ──────────────────────────────────────────────────────
    public function salesContactsBulkTask(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')));
        $subject = $request->input('subject', 'Follow-up Task');
        $dueDate = $request->input('due_date', now()->addDay()->format('Y-m-d'));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No contacts selected.');
        }
        foreach ($ids as $contactId) {
            $contact = CrmContact::where('user_id', auth()->id())->find($contactId);
            if (!$contact) continue;
            \App\Models\CrmActivity::create([
                'user_id'      => auth()->id(),
                'contact_id'   => $contact->id,
                'related_type' => 'contact',
                'related_id'   => $contact->id,
                'type'         => 'Task',
                'subject'      => $subject,
                'due_date'     => $dueDate,
                'assigned_to'  => auth()->id(),
                'status'       => 'open',
                'description'  => 'Bulk task created from Contacts listing.',
            ]);
        }
        return redirect()->route('admin.crm2.sales.contacts')->with('success', 'Tasks created for '.count($ids).' contact(s).');
    }

    // ─── Accounts: Clone ─────────────────────────────────────────────────────────
    public function salesAccountsClone($id)
    {
        $account = CrmAccount::where('user_id', auth()->id())->findOrFail($id);
        $clone = $account->replicate();
        $clone->name = ($account->name ?? 'Account') . ' (Copy)';
        $clone->status = 'active';
        $clone->created_at = now();
        $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.sales.accounts.show', $clone->id)
            ->with('success', 'Account cloned successfully.');
    }

    // ─── Accounts: Bulk Delete ────────────────────────────────────────────────────
    public function salesAccountsBulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No accounts selected.');
        }
        CrmAccount::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.sales.accounts')->with('success', count($ids).' account(s) deleted.');
    }

    // ─── Accounts: Bulk Task ──────────────────────────────────────────────────────
    public function salesAccountsBulkTask(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')));
        $subject = $request->input('subject', 'Follow-up Task');
        $dueDate = $request->input('due_date', now()->addDay()->format('Y-m-d'));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No accounts selected.');
        }
        foreach ($ids as $accountId) {
            $account = CrmAccount::where('user_id', auth()->id())->find($accountId);
            if (!$account) continue;
            \App\Models\CrmActivity::create([
                'user_id'      => auth()->id(),
                'account_id'   => $account->id,
                'related_type' => 'account',
                'related_id'   => $account->id,
                'type'         => 'Task',
                'subject'      => $subject,
                'due_date'     => $dueDate,
                'assigned_to'  => auth()->id(),
                'status'       => 'open',
                'description'  => 'Bulk task created from Accounts listing.',
            ]);
        }
        return redirect()->route('admin.crm2.sales.accounts')->with('success', 'Tasks created for '.count($ids).' account(s).');
    }

    // ─── Deals: Clone ────────────────────────────────────────────────────────────
    public function salesDealsClone($id)
    {
        $deal = CrmDeal::where('user_id', auth()->id())->findOrFail($id);
        $clone = $deal->replicate();
        $clone->name  = (($deal->name ?? $deal->title ?? 'Deal') . ' (Copy)');
        $clone->title = $clone->name;
        $clone->stage = 'prospecting';
        $clone->created_at = now();
        $clone->updated_at = now();
        $clone->save();
        return redirect()->route('admin.crm2.sales.deals.show', $clone->id)
            ->with('success', 'Deal cloned successfully.');
    }

    // ─── Deals: Bulk Delete ───────────────────────────────────────────────────────
    public function salesDealsBulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No deals selected.');
        }
        CrmDeal::where('user_id', auth()->id())->whereIn('id', $ids)->delete();
        return redirect()->route('admin.crm2.sales.deals')->with('success', count($ids).' deal(s) deleted.');
    }

    // ─── Deals: Bulk Task ─────────────────────────────────────────────────────────
    public function salesDealsBulkTask(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')));
        $subject = $request->input('subject', 'Follow-up Task');
        $dueDate = $request->input('due_date', now()->addDay()->format('Y-m-d'));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No deals selected.');
        }
        foreach ($ids as $dealId) {
            $deal = CrmDeal::where('user_id', auth()->id())->find($dealId);
            if (!$deal) continue;
            \App\Models\CrmActivity::create([
                'user_id'      => auth()->id(),
                'deal_id'      => $deal->id,
                'related_type' => 'deal',
                'related_id'   => $deal->id,
                'type'         => 'Task',
                'subject'      => $subject,
                'due_date'     => $dueDate,
                'assigned_to'  => auth()->id(),
                'status'       => 'open',
                'description'  => 'Bulk task created from Deals listing.',
            ]);
        }
        return redirect()->route('admin.crm2.sales.deals')->with('success', 'Tasks created for '.count($ids).' deal(s).');
    }

    // ══════════════════════════════════════════════════════════════
    // PROJECTS — ENHANCED METHODS
    // ══════════════════════════════════════════════════════════════

    /** Show project view page with all sub-module data */
    public function projectsShow(int $id)
    {
        $tid     = $this->tenantId();
        $project = CrmProject::where('user_id', $tid)->with(['account','deal'])->findOrFail($id);
        $tasks      = CrmProjectTask::where('project_id', $id)->with(['milestone'])->orderBy('sort_order')->orderByDesc('created_at')->get();
        $milestones = CrmProjectMilestone::where('project_id', $id)->orderBy('target_date')->get();
        $issues     = CrmProjectIssue::where('project_id', $id)->with(['task'])->orderByDesc('created_at')->get();
        $timeLogs   = CrmProjectTimeLog::where('project_id', $id)->with(['task','logger'])->orderByDesc('log_date')->get();
        $notes      = CrmProjectNote::where('project_id', $id)->with(['author'])->orderByDesc('created_at')->get();
        return view('admin.crm2.projects.view-project', compact('project','tasks','milestones','issues','timeLogs','notes'));
    }

    /** Clone a project */
    public function projectsClone(int $id)
    {
        $tid     = $this->tenantId();
        $project = CrmProject::where('user_id', $tid)->findOrFail($id);
        $clone   = $project->replicate();
        $clone->name       = 'Copy of ' . $project->name;
        $clone->status     = 'planning';
        $clone->created_at = now();
        $clone->updated_at = now();
        $clone->save();
        foreach (CrmProjectTask::where('project_id', $id)->get() as $task) {
            $t = $task->replicate();
            $t->project_id   = $clone->id;
            $t->status       = 'todo';
            $t->milestone_id = null;
            $t->save();
        }
        return redirect()->route('admin.crm2.projects.show', $clone->id)->with('success', 'Project cloned successfully.');
    }

    /** Enhanced project listing with status filter */
    public function projectsListEnhanced(Request $request)
    {
        $tid    = $this->tenantId();
        $search = $request->input('search');
        $status = $request->input('status');
        $q = CrmProject::where('user_id', $tid)->with(['account','deal'])->withCount('tasks');
        if ($search) $q->where('name','like',"%$search%");
        if ($status) $q->where('status', $status);
        $projects = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.projects.list', compact('projects'));
    }

    /** Enhanced create project form with accounts/deals */
    public function projectsListCreateEnhanced()
    {
        $tid           = $this->tenantId();
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $deals_list    = CrmDeal::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.projects.create-project', compact('accounts_list','deals_list'));
    }

    /** Enhanced edit project form with accounts/deals */
    public function projectsListEditEnhanced(int $id)
    {
        $tid           = $this->tenantId();
        $project       = CrmProject::where('user_id', $tid)->findOrFail($id);
        $accounts_list = CrmAccount::where('user_id', $tid)->orderBy('name')->get();
        $deals_list    = CrmDeal::where('user_id', $tid)->orderBy('name')->get();
        return view('admin.crm2.projects.edit-project', compact('project','accounts_list','deals_list'));
    }

    /** Enhanced tasks listing with filters */
    public function projectsTasksEnhanced(Request $request)
    {
        $tid           = $this->tenantId();
        $search        = $request->input('search');
        $project_id    = $request->input('project_id');
        $status        = $request->input('status');
        $priority      = $request->input('priority');
        $projects_list = CrmProject::where('user_id', $tid)->orderBy('name')->get();
        $q = CrmProjectTask::whereHas('project', fn($q) => $q->where('user_id', $tid))
                           ->with(['project','milestone']);
        if ($search)     $q->where('name','like',"%$search%");
        if ($project_id) $q->where('project_id', $project_id);
        if ($status)     $q->where('status', $status);
        if ($priority)   $q->where('priority', $priority);
        $tasks = $q->orderByDesc('created_at')->paginate(25)->withQueryString();
        return view('admin.crm2.projects.tasks', compact('tasks','projects_list'));
    }

    /** Bulk delete projects */
    public function projectsBulkDelete(Request $request)
    {
        $tid = $this->tenantId();
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            CrmProject::whereIn('id', $ids)->where('user_id', $tid)->delete();
        }
        return redirect()->route('admin.crm2.projects.list')->with('success', count($ids).' project(s) deleted.');
    }

    /** Bulk status update for projects */
    public function projectsBulkStatus(Request $request)
    {
        $tid    = $this->tenantId();
        $ids    = $request->input('ids', []);
        $status = $request->input('status');
        if (!empty($ids) && $status) {
            CrmProject::whereIn('id', $ids)->where('user_id', $tid)->update(['status' => $status]);
        }
        return redirect()->route('admin.crm2.projects.list')->with('success', count($ids).' project(s) updated.');
    }

    /** Bulk export projects as CSV */
    public function projectsBulkExport(Request $request)
    {
        $tid  = $this->tenantId();
        $ids  = $request->input('ids', []);
        $rows = CrmProject::whereIn('id', $ids)->where('user_id', $tid)->with(['account','deal'])->get();
        $csv  = "Name,Status,Priority,Account,Deal,Start Date,End Date,Budget,Progress\n";
        foreach ($rows as $r) {
            $csv .= implode(',', [
                '"'.$r->name.'"', $r->status, $r->priority??'medium',
                '"'.($r->account?->name??'').'"', '"'.($r->deal?->name??'').'"',
                $r->start_date?->format('d M Y')??'', $r->end_date?->format('d M Y')??'',
                $r->budget, $r->progress_percent.'%',
            ]) . "\n";
        }
        return response($csv, 200, ['Content-Type'=>'text/csv','Content-Disposition'=>'attachment;filename=projects.csv']);
    }

    /** Bulk delete tasks */
    public function projectsTasksBulkDelete(Request $request)
    {
        $tid = $this->tenantId();
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            CrmProjectTask::whereIn('id', $ids)
                ->whereHas('project', fn($q) => $q->where('user_id', $tid))
                ->delete();
        }
        return redirect()->route('admin.crm2.projects.tasks')->with('success', count($ids).' task(s) deleted.');
    }

    /** Bulk export tasks as CSV */
    public function projectsTasksBulkExport(Request $request)
    {
        $tid  = $this->tenantId();
        $ids  = $request->input('ids', []);
        $rows = CrmProjectTask::whereIn('id', $ids)
                    ->whereHas('project', fn($q) => $q->where('user_id', $tid))
                    ->with(['project','milestone'])->get();
        $csv  = "Title,Project,Milestone,Priority,Status,Due Date\n";
        foreach ($rows as $r) {
            $csv .= implode(',', [
                '"'.$r->name.'"', '"'.($r->project?->name??'').'"',
                '"'.($r->milestone?->name??'').'"', $r->priority, $r->status,
                $r->due_date?->format('d M Y')??'',
            ]) . "\n";
        }
        return response($csv, 200, ['Content-Type'=>'text/csv','Content-Disposition'=>'attachment;filename=tasks.csv']);
    }

    // ── Milestones ────────────────────────────────────────────────

    public function projectsMilestonesStore(Request $request, int $projectId)
    {
        $tid     = $this->tenantId();
        $project = CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectMilestone::create([
            'project_id'  => $project->id,
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'target_date' => $request->input('target_date') ?: null,
            'status'      => $request->input('status', 'pending'),
        ]);
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Milestone added.');
    }

    public function projectsMilestonesUpdate(Request $request, int $projectId, int $id)
    {
        $tid = $this->tenantId();
        CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectMilestone::where('id', $id)->where('project_id', $projectId)
            ->update($request->only(['name','description','target_date','status']));
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Milestone updated.');
    }

    public function projectsMilestonesDestroy(int $projectId, int $id)
    {
        $tid = $this->tenantId();
        CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectMilestone::where('id', $id)->where('project_id', $projectId)->delete();
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Milestone deleted.');
    }

    // ── Issues ────────────────────────────────────────────────────

    public function projectsIssuesStore(Request $request, int $projectId)
    {
        $tid     = $this->tenantId();
        $project = CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectIssue::create([
            'project_id'  => $project->id,
            'task_id'     => $request->input('task_id') ?: null,
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'severity'    => $request->input('severity', 'medium'),
            'status'      => $request->input('status', 'open'),
            'due_date'    => $request->input('due_date') ?: null,
            'assigned_to' => auth()->id(),
        ]);
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Issue reported.');
    }

    public function projectsIssuesUpdate(Request $request, int $projectId, int $id)
    {
        $tid = $this->tenantId();
        CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectIssue::where('id', $id)->where('project_id', $projectId)
            ->update($request->only(['title','description','severity','status','due_date']));
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Issue updated.');
    }

    public function projectsIssuesDestroy(int $projectId, int $id)
    {
        $tid = $this->tenantId();
        CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectIssue::where('id', $id)->where('project_id', $projectId)->delete();
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Issue deleted.');
    }

    // ── Time Logs ─────────────────────────────────────────────────

    public function projectsTimeLogStore(Request $request, int $projectId)
    {
        $tid     = $this->tenantId();
        $project = CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectTimeLog::create([
            'project_id' => $project->id,
            'task_id'    => $request->input('task_id') ?: null,
            'logged_by'  => auth()->id(),
            'log_date'   => $request->input('log_date', date('Y-m-d')),
            'hours'      => $request->input('hours'),
            'notes'      => $request->input('notes'),
        ]);
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Time logged.');
    }

    public function projectsTimeLogDestroy(int $projectId, int $id)
    {
        $tid = $this->tenantId();
        CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectTimeLog::where('id', $id)->where('project_id', $projectId)->delete();
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Time log deleted.');
    }

    // ── Notes ─────────────────────────────────────────────────────

    public function projectsNotesStore(Request $request, int $projectId)
    {
        $tid     = $this->tenantId();
        $project = CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectNote::create([
            'project_id' => $project->id,
            'created_by' => auth()->id(),
            'body'       => $request->input('body'),
        ]);
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Note saved.');
    }

    public function projectsNotesDestroy(int $projectId, int $id)
    {
        $tid = $this->tenantId();
        CrmProject::where('user_id', $tid)->findOrFail($projectId);
        CrmProjectNote::where('id', $id)->where('project_id', $projectId)->delete();
        return redirect()->route('admin.crm2.projects.show', $projectId)->with('success', 'Note deleted.');
    }
}
