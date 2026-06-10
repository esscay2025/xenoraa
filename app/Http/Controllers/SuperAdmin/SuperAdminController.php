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
        // Super admin only sees Xenoraa PLATFORM SUBSCRIBERS:
        // - Users with role=admin (tenant owners / Xenoraa subscribers)
        // - Users with role=superadmin (platform admins)
        // Sub-users (staff, visitor) created by tenant admins are NOT shown here.
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $superAdminRoleId = DB::table('roles')->where('name', 'superadmin')->value('id');

        $query = User::withCount(['blogPosts', 'crmLeads'])
            ->whereIn('role_id', array_filter([$adminRoleId, $superAdminRoleId]));

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
        // Only show tenant admin subscribers — exclude super admin and sub-users
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        $subscriptions = User::whereNotNull('plan')
            ->where('role_id', $adminRoleId)
            ->select('id', 'name', 'email', 'plan', 'status', 'created_at', 'custom_domain', 'username')
            ->latest()
            ->paginate(25);
        return view('superadmin.subscriptions', compact('subscriptions'));
    }

    /**
     * Plan Modules Management — show which modules each plan includes
     */
    public function planModules()
    {
        $planModules = config('xenoraa.plan_modules', []);
        $plans       = config('xenoraa.plans', []);
        $allModules  = [
            'site_builder' => ['label' => 'Site Builder',          'icon' => 'fa-globe',         'desc' => 'Page manager, branding, domain, menu builder'],
            'content'      => ['label' => 'Content (Blog + Forum)', 'icon' => 'fa-pen-nib',       'desc' => 'Blog posts, forum topics'],
            'ecommerce'    => ['label' => 'E-Commerce / Shop',      'icon' => 'fa-shopping-cart',  'desc' => 'Products, orders, shop management'],
            'recruitment'  => ['label' => 'Jobs / Recruitment',     'icon' => 'fa-briefcase',      'desc' => 'Job listings and applications'],
            'analytics'    => ['label' => 'Analytics',              'icon' => 'fa-chart-bar',      'desc' => 'Traffic and engagement analytics'],
            'crm'          => ['label' => 'CRM',                    'icon' => 'fa-users',          'desc' => 'Leads, contacts, projects, services, sales pipeline'],
            'ai'           => ['label' => 'AI Hub (AI Assistance)', 'icon' => 'fa-robot',          'desc' => 'AI chatbot, AI conversations'],
            'pos'          => ['label' => 'Point of Sale (POS)',    'icon' => 'fa-cash-register',  'desc' => 'POS terminal, orders, sessions'],
            'newsletter'   => ['label' => 'Newsletter',             'icon' => 'fa-envelope',       'desc' => 'Email campaigns and subscriber management'],
        ];
        return view('superadmin.plan-modules', compact('planModules', 'plans', 'allModules'));
    }

    /**
     * Save updated plan module assignments (writes to xenoraa.php config file)
     */
    public function savePlanModules(Request $request)
    {
        $planKeys   = array_keys(config('xenoraa.plans', []));
        $allModKeys = ['site_builder','content','ecommerce','recruitment','analytics','crm','ai','pos','newsletter'];
        $newMap     = [];
        foreach ($planKeys as $plan) {
            $newMap[$plan] = array_values(array_intersect($allModKeys, $request->input($plan, [])));
        }
        // Persist to config file
        $configPath = config_path('xenoraa.php');
        $current    = file_get_contents($configPath);
        // Build the plan_modules PHP array string
        $lines = "    'plan_modules' => [\n";
        foreach ($newMap as $plan => $mods) {
            $quoted = array_map(fn($m) => "'$m'", $mods);
            $lines .= "        '$plan' => [" . implode(', ', $quoted) . "],\n";
        }
        $lines .= "    ],\n";
        // Replace existing plan_modules block
        $updated = preg_replace(
            "/'plan_modules'\s*=>\s*\[.*?\],\n/s",
            $lines,
            $current
        );
        if ($updated && $updated !== $current) {
            file_put_contents($configPath, $updated);
            \Illuminate\Support\Facades\Artisan::call('config:cache');
        }
        return back()->with('success', 'Plan module access updated successfully.');
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
     * SEO Management
     */
    public function seo()
    {
        $settings = DB::table('site_settings')->whereNull('user_id')->pluck('value', 'key')->toArray();
        return view('superadmin.seo', compact('settings'));
    }

    /**
     * Update SEO Settings
     */
    public function updateSeo(Request $request)
    {
        $seoKeys = [
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
            'seo_canonical_url', 'seo_robots',
            'google_tag_id', 'google_tag_enabled',
            'og_title', 'og_description', 'og_image', 'og_type', 'og_site_name',
            'twitter_card', 'twitter_site', 'twitter_title', 'twitter_description', 'twitter_image',
            'sitemap_enabled', 'sitemap_frequency', 'sitemap_priority',
            'schema_org_type', 'schema_org_name', 'schema_org_url', 'schema_org_logo',
            'schema_org_description', 'schema_org_phone', 'schema_org_email', 'schema_org_address',
            'custom_head_scripts', 'custom_body_scripts',
        ];
        foreach ($seoKeys as $key) {
            $value = $request->input($key, '');
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key, 'user_id' => null],
                ['value' => $value]
            );
        }
        return back()->with('success', 'SEO settings saved successfully.');
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
