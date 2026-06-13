<?php

namespace App\Http\Controllers\Xenoraa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class XenoraaController extends Controller
{
    public function home()
    {
        $stats = [
            'total_users'    => User::count(),
            'showcase_users' => User::where('status', 'active')->whereNotNull('username')->take(6)->get(),
        ];
        return view('xenoraa.home', compact('stats'));
    }

    public function features()
    {
        return view('xenoraa.features');
    }

    public function pricing()
    {
        return view('xenoraa.pricing');
    }

    public function showcase()
    {
        $users = User::where('status', 'active')
            ->whereNotNull('username')
            ->withCount(['blogPosts'])
            ->latest()
            ->get();
        return view('xenoraa.showcase', compact('users'));
    }

    public function blog()
    {
        return view('xenoraa.blog');
    }

    public function getStarted()
    {
        return view('xenoraa.get-started');
    }

    public function about()
    {
        return view('xenoraa.about');
    }

    public function careers()
    {
        return view('xenoraa.careers');
    }

}
