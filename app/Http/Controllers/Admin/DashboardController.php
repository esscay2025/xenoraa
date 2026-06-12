<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\CrmLead;
use App\Models\CrmAccount;
use App\Models\CrmContact;
use App\Models\CrmDeal;
use App\Models\CrmQuote;
use App\Models\CrmSalesOrder;
use App\Models\CrmPurchaseOrder;
use App\Models\CrmInvoice;
use App\Models\CrmVendor;
use App\Models\CrmActivity;
use App\Models\Product;
use App\Models\PosOrder;
use App\Models\PosSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tid = Auth::user()->getTenantId();

        // ── CRM Stats ──────────────────────────────────────────────────────────
        $crmLeads       = CrmLead::where('user_id', $tid)->count();
        $crmAccounts    = CrmAccount::where('user_id', $tid)->count();
        $crmContacts    = CrmContact::where('user_id', $tid)->count();
        $crmDeals       = CrmDeal::where('user_id', $tid)->count();
        $crmDealsOpen   = CrmDeal::where('user_id', $tid)->whereNotIn('stage', ['closed_won', 'closed_lost'])->count();
        $crmDealsWon    = CrmDeal::where('user_id', $tid)->where('stage', 'closed_won')->count();
        $crmActivities  = CrmActivity::where('user_id', $tid)->where('status', 'open')->count();
        $crmPipelineVal = CrmDeal::where('user_id', $tid)->whereNotIn('stage', ['closed_won', 'closed_lost'])->sum('amount') ?? 0;
        $crmWonValue    = CrmDeal::where('user_id', $tid)->where('stage', 'closed_won')->sum('amount') ?? 0;

        // ── Inventory Stats ────────────────────────────────────────────────────
        $invQuotes      = CrmQuote::where('user_id', $tid)->count();
        $invSalesOrders = CrmSalesOrder::where('user_id', $tid)->count();
        $invPOs         = CrmPurchaseOrder::where('user_id', $tid)->count();
        $invInvoices    = CrmInvoice::where('user_id', $tid)->count();
        $invVendors     = CrmVendor::where('user_id', $tid)->count();
        $invInvoicesDue = CrmInvoice::where('user_id', $tid)->whereIn('status', ['Draft', 'Sent', 'Overdue'])->count();
        $invRevenue     = CrmInvoice::where('user_id', $tid)->where('status', 'Paid')->sum('grand_total') ?? 0;

        // ── E-Commerce Stats ───────────────────────────────────────────────────
        $ecomProducts   = Product::where('user_id', $tid)->count();
        $ecomActive     = Product::where('user_id', $tid)->where('is_active', true)->count();

        // ── POS Stats ──────────────────────────────────────────────────────────
        $posOrders         = PosOrder::where('tenant_id', $tid)->count();
        $posTodaySales     = PosOrder::where('tenant_id', $tid)->whereDate('created_at', today())->sum('total') ?? 0;
        $posActiveSessions = PosSession::where('tenant_id', $tid)->where('status', 'open')->count();

        // ── Site Builder Stats ─────────────────────────────────────────────────
        $sitePosts  = BlogPost::where('user_id', $tid)->where('status', 'published')->count();
        $siteDrafts = BlogPost::where('user_id', $tid)->where('status', 'draft')->count();
        $siteUsers  = User::where('tenant_owner_id', $tid)->orWhere('id', $tid)->count();

        // ── Monthly Invoice Revenue (last 6 months) ────────────────────────────
        $monthlyRevenue = CrmInvoice::where('user_id', $tid)
            ->where('status', 'Paid')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw("TO_CHAR(created_at, 'Mon') as month, EXTRACT(MONTH FROM created_at) as month_num, SUM(grand_total) as revenue")
            ->groupByRaw("TO_CHAR(created_at, 'Mon'), EXTRACT(MONTH FROM created_at)")
            ->orderByRaw("EXTRACT(MONTH FROM created_at)")
            ->get();

        // ── Deals by Stage ─────────────────────────────────────────────────────
        $dealsByStage = CrmDeal::where('user_id', $tid)
            ->selectRaw('stage, COUNT(*) as count, SUM(COALESCE(amount, 0)) as total')
            ->groupBy('stage')
            ->orderBy('count', 'desc')
            ->get();

        // ── Recent Records ─────────────────────────────────────────────────────
        $recentLeads    = CrmLead::where('user_id', $tid)->orderByDesc('created_at')->take(5)->get();
        $recentDeals    = CrmDeal::where('user_id', $tid)->orderByDesc('created_at')->take(5)->get();
        $recentInvoices = CrmInvoice::where('user_id', $tid)->orderByDesc('created_at')->take(5)->get();
        $recentPosts    = BlogPost::where('user_id', $tid)->orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'crmLeads', 'crmAccounts', 'crmContacts', 'crmDeals',
            'crmDealsOpen', 'crmDealsWon', 'crmActivities', 'crmPipelineVal', 'crmWonValue',
            'invQuotes', 'invSalesOrders', 'invPOs', 'invInvoices',
            'invVendors', 'invInvoicesDue', 'invRevenue',
            'ecomProducts', 'ecomActive',
            'posOrders', 'posTodaySales', 'posActiveSessions',
            'sitePosts', 'siteDrafts', 'siteUsers',
            'monthlyRevenue', 'dealsByStage',
            'recentLeads', 'recentDeals', 'recentInvoices', 'recentPosts'
        ));
    }
}
