<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Expense;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\CrmLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $tenantId = Auth::user()->getTenantId();

        $stats = [
            'total_users'        => User::where('tenant_owner_id', $tenantId)->count(),
            'total_posts'        => BlogPost::where('user_id', $tenantId)->where('status', 'published')->count(),
            'active_jobs'        => Job::where('user_id', $tenantId)->where('status', 'active')->count(),
            'total_applications' => JobApplication::whereHas('job', fn($q) => $q->where('user_id', $tenantId))->count(),
            'pending_expenses'   => Expense::where('user_id', $tenantId)->where('status', 'pending')->count(),
            'total_expenses'     => Expense::where('user_id', $tenantId)->sum('amount'),
            'total_leads'        => CrmLead::where('user_id', $tenantId)->count(),
        ];

        $recentPosts = BlogPost::with('author')
            ->where('user_id', $tenantId)
            ->orderBy('created_at', 'desc')->take(5)->get();

        $recentApplications = JobApplication::with('job')
            ->whereHas('job', fn($q) => $q->where('user_id', $tenantId))
            ->orderBy('created_at', 'desc')->take(5)->get();

        $recentExpenses = Expense::with(['user', 'category'])
            ->where('user_id', $tenantId)
            ->orderBy('created_at', 'desc')->take(5)->get();

        $recentLeads = CrmLead::where('user_id', $tenantId)
            ->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentPosts', 'recentApplications', 'recentExpenses', 'recentLeads'
        ));
    }
}
