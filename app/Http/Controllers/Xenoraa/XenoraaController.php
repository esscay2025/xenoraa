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
        $posts = \App\Models\BlogPost::where('status', 'published')
            ->whereIn('slug', [
                'indian-businesses-unified-saas-platform',
                'xenoraa-vs-5-tools-cost-comparison',
                'freelance-consultant-digital-presence-xenoraa',
                'crm-guide-small-businesses-india-2026',
                'launch-online-store-no-code-xenoraa',
                'xenoraa-business-os-unified-dashboard',
                'outgrown-spreadsheets-switch-to-crm',
                'pos-ecommerce-unified-inventory',
                'build-professional-website-30-minutes-xenoraa',
                'disconnected-tools-data-silos-business-growth',
            ])
            ->orderBy('published_at', 'desc')
            ->get();
        $featured = $posts->first();
        $gridPosts = $posts->skip(1)->values();
        return view('xenoraa.blog', compact('posts', 'featured', 'gridPosts'));
    }

    public function blogShow($slug)
    {
        $post = \App\Models\BlogPost::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        $related = \App\Models\BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->whereIn('slug', [
                'indian-businesses-unified-saas-platform',
                'xenoraa-vs-5-tools-cost-comparison',
                'freelance-consultant-digital-presence-xenoraa',
                'crm-guide-small-businesses-india-2026',
                'launch-online-store-no-code-xenoraa',
                'xenoraa-business-os-unified-dashboard',
                'outgrown-spreadsheets-switch-to-crm',
                'pos-ecommerce-unified-inventory',
                'build-professional-website-30-minutes-xenoraa',
                'disconnected-tools-data-silos-business-growth',
            ])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        return view('xenoraa.blog-show', compact('post', 'related'));
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
