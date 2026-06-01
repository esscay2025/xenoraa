<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('unsubscribed_at');
            } elseif ($request->status === 'unsubscribed') {
                $query->whereNotNull('unsubscribed_at');
            }
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->orderByDesc('subscribed_at')->paginate(30);

        $stats = [
            'total'        => NewsletterSubscriber::count(),
            'active'       => NewsletterSubscriber::whereNull('unsubscribed_at')->count(),
            'unsubscribed' => NewsletterSubscriber::whereNotNull('unsubscribed_at')->count(),
            'this_month'   => NewsletterSubscriber::whereMonth('subscribed_at', now()->month)
                                ->whereYear('subscribed_at', now()->year)->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed.');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::whereNull('unsubscribed_at')
            ->orderByDesc('subscribed_at')
            ->get(['email', 'subscribed_at']);

        $csv = "Email,Subscribed At\n";
        foreach ($subscribers as $s) {
            $csv .= "\"{$s->email}\",\"{$s->subscribed_at}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
