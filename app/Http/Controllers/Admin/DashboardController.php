<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Expense;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => BlogPost::where('status', 'published')->count(),
            'active_jobs' => Job::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'pending_expenses' => Expense::where('status', 'pending')->count(),
            'total_expenses' => Expense::sum('amount'),
        ];

        $recentPosts = BlogPost::with('author')->orderBy('created_at', 'desc')->take(5)->get();
        $recentApplications = JobApplication::with('job')->orderBy('created_at', 'desc')->take(5)->get();
        $recentExpenses = Expense::with(['user', 'category'])->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'recentApplications', 'recentExpenses'));
    }
}
