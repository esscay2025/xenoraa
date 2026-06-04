<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SuperAdminController extends Controller
{
    /**
     * Super Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users'       => User::count(),
            'new_users_month'   => User::whereMonth('created_at', now()->month)->count(),
            'active_subs'       => User::where('status', 'active')->count(),
            'sub_growth'        => 12,
            'monthly_revenue'   => $this->calculateMRR(),
            'custom_domains'    => User::whereNotNull('custom_domain')->count(),
            'starter_count'     => User::where('plan', 'starter')->orWhereNull('plan')->count(),
            'pro_count'         => User::where('plan', 'professional')->count(),
            'business_count'    => User::where('plan', 'business')->count(),
            'total_posts'       => DB::table('blog_posts')->count(),
            'total_leads'       => DB::table('crm_leads')->count(),
            'total_chats'       => DB::table('chatbot_conversations')->count(),
            'total_subscribers' => DB::table('newsletter_subscribers')->count(),
            'total_products'    => DB::table('products')->count(),
            'total_events'      => DB::table('calendar_events')->count(),
        ];

        $recentUsers = User::latest()->take(8)->get();
        $domains = User::whereNotNull('custom_domain')->take(10)->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers', 'domains'));
    }

    /**
     * Users List
     */
    public function users(Request $request)
    {
        $query = User::withCount(['blogPosts', 'crmLeads']);

        if ($request->plan && $request->plan !== 'all') {
            if ($request->plan === 'suspended') {
                $query->where('status', 'suspended');
            } else {
                $query->where('plan', $request->plan);
            }
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->export === 'csv') {
            return $this->exportUsersCsv($query->get());
        }

        $users = $query->latest()->paginate(20);
        return view('superadmin.users', compact('users'));
    }

    /**
     * User Detail
     */
    public function showUser($id)
    {
        $user = User::withCount(['blogPosts', 'crmLeads'])->findOrFail($id);
        $recentPosts = DB::table('blog_posts')->where('user_id', $id)->latest()->take(5)->get();
        $recentLeads = DB::table('crm_leads')->where('user_id', $id)->latest()->take(5)->get();
        return view('superadmin.user-show', compact('user', 'recentPosts', 'recentLeads'));
    }

    /**
     * Impersonate User (Login as User)
     */
    public function impersonateUser($id)
    {
        $user = User::findOrFail($id);
        Session::put('superadmin_id', auth()->id());
        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Now logged in as ' . $user->name . '. Use "Exit Impersonation" to return.');
    }

    /**
     * Exit Impersonation
     */
    public function exitImpersonation()
    {
        $superAdminId = Session::pull('superadmin_id');
        if ($superAdminId) {
            Auth::loginUsingId($superAdminId);
            return redirect()->route('superadmin.users')->with('success', 'Returned to Super Admin.');
        }
        return redirect()->route('superadmin.dashboard');
    }

    /**
     * Toggle User Status (Activate / Suspend)
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();
        return back()->with('success', 'User status updated to ' . $user->status . '.');
    }

    /**
     * Subscriptions
     */
    public function subscriptions()
    {
        $subscriptions = User::whereNotNull('plan')
            ->select('id', 'name', 'email', 'plan', 'status', 'created_at', 'custom_domain')
            ->latest()
            ->paginate(25);
        return view('superadmin.subscriptions', compact('subscriptions'));
    }

    /**
     * Revenue Overview
     */
    public function revenue()
    {
        $planPrices = ['starter' => 499, 'professional' => 999, 'business' => 1999];
        $revenueByPlan = [];
        foreach ($planPrices as $plan => $price) {
            $count = User::where('plan', $plan)->where('status', 'active')->count();
            $revenueByPlan[$plan] = ['count' => $count, 'price' => $price, 'total' => $count * $price];
        }
        $mrr = array_sum(array_column($revenueByPlan, 'total'));
        $arr = $mrr * 12;
        return view('superadmin.revenue', compact('revenueByPlan', 'mrr', 'arr'));
    }

    /**
     * Custom Domains
     */
    public function domains()
    {
        $domains = User::whereNotNull('custom_domain')->paginate(25);
        return view('superadmin.domains', compact('domains'));
    }

    /**
     * Update Custom Domain
     */
    public function updateDomain(Request $request, $id)
    {
        $request->validate(['custom_domain' => 'nullable|string|max:255']);
        $user = User::findOrFail($id);
        $user->custom_domain = $request->custom_domain;
        $user->save();
        return back()->with('success', 'Domain updated for ' . $user->name);
    }

    /**
     * Blog Posts (all users)
     */
    public function blog()
    {
        $posts = DB::table('blog_posts')
            ->join('users', 'blog_posts.user_id', '=', 'users.id')
            ->select('blog_posts.*', 'users.name as author_name', 'users.username')
            ->latest('blog_posts.created_at')
            ->paginate(20);
        return view('superadmin.blog', compact('posts'));
    }

    /**
     * Showcase Management
     */
    public function showcase()
    {
        $users = User::where('status', 'active')
            ->whereNotNull('username')
            ->withCount('blogPosts')
            ->paginate(20);
        return view('superadmin.showcase', compact('users'));
    }

    /**
     * Platform Settings
     */
    public function settings()
    {
        $settings = DB::table('site_settings')->pluck('value', 'key')->toArray();
        return view('superadmin.settings', compact('settings'));
    }

    /**
     * Update Platform Settings
     */
    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Platform settings updated.');
    }

    /**
     * Email Templates
     */
    public function emails()
    {
        return view('superadmin.emails');
    }

    /**
     * Activity Logs
     */
    public function logs()
    {
        try {
            $logs = DB::table('activity_logs')
                ->join('users', 'activity_logs.user_id', '=', 'users.id')
                ->select('activity_logs.*', 'users.name as user_name')
                ->latest('activity_logs.created_at')
                ->paginate(30);
        } catch (\Exception $e) {
            $logs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 30);
        }
        return view('superadmin.logs', compact('logs'));
    }

    /**
     * Analytics
     */
    public function analytics()
    {
        $signupsByMonth = User::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();
        return view('superadmin.analytics', compact('signupsByMonth'));
    }

    /**
     * Calculate MRR
     */
    private function calculateMRR(): int
    {
        $prices = ['starter' => 499, 'professional' => 999, 'business' => 1999];
        $mrr = 0;
        foreach ($prices as $plan => $price) {
            $mrr += User::where('plan', $plan)->where('status', 'active')->count() * $price;
        }
        return $mrr;
    }

    /**
     * Export Users CSV
     */
    private function exportUsersCsv($users)
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=xenoraa-users.csv'];
        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Username', 'Plan', 'Status', 'Custom Domain', 'Joined']);
            foreach ($users as $u) {
                fputcsv($file, [$u->id, $u->name, $u->email, $u->username ?? '', $u->plan ?? 'starter', $u->status ?? 'active', $u->custom_domain ?? '', $u->created_at->format('Y-m-d')]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
