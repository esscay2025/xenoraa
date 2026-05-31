<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter subscription form submission.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name'  => 'nullable|string|max:100',
        ]);

        $email = strtolower(trim($request->input('email')));

        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing) {
            if ($existing->status === 'unsubscribed') {
                // Re-subscribe
                $existing->update([
                    'status'            => 'active',
                    'subscribed_at'     => now(),
                    'unsubscribed_at'   => null,
                ]);
                return $this->successResponse($request, 'Welcome back! You have been re-subscribed successfully.');
            }
            return $this->successResponse($request, 'You are already subscribed. Thank you!');
        }

        NewsletterSubscriber::create([
            'email'         => $email,
            'name'          => $request->input('name'),
            'subscribed_at' => now(),
        ]);

        return $this->successResponse($request, 'Thank you for subscribing! You will receive updates on my latest projects, AI tools, automation tips, and business insights.');
    }

    /**
     * Handle unsubscribe via token link.
     */
    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Invalid unsubscribe link.');
        }

        if ($subscriber->status === 'unsubscribed') {
            return redirect()->route('home')->with('info', 'You are already unsubscribed.');
        }

        $subscriber->update([
            'status'          => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);

        return redirect()->route('home')->with('success', 'You have been successfully unsubscribed.');
    }

    /**
     * Return appropriate response (JSON for AJAX, redirect for standard forms).
     */
    private function successResponse(Request $request, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('newsletter_success', $message);
    }
}
