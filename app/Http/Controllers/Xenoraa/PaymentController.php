<?php

namespace App\Http\Controllers\Xenoraa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    protected Api $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );
    }

    /**
     * Show the pricing / checkout page
     */
    public function pricing()
    {
        $plans = [
            'starter' => [
                'name'        => 'Starter',
                'monthly'     => 499,
                'yearly'      => 4999,
                'description' => 'Perfect for individuals and freelancers',
                'features'    => [
                    'Portfolio website (xenoraa.com/username)',
                    'Blog — up to 20 posts',
                    'AI Chatbot (basic)',
                    'Newsletter (up to 500 subscribers)',
                    'Contact form',
                    'SEO tools',
                    'SSL included',
                ],
                'color'       => '#7c3aed',
            ],
            'professional' => [
                'name'        => 'Professional',
                'monthly'     => 999,
                'yearly'      => 9999,
                'description' => 'For professionals who want to stand out',
                'features'    => [
                    'Everything in Starter',
                    'Custom domain mapping',
                    'Unlimited blog posts',
                    'CRM & Lead management',
                    'AI Chatbot (GPT-4 powered)',
                    'Newsletter (unlimited)',
                    'Chat Monitor & Reply',
                    'Calendar & Notes',
                    'Priority support',
                ],
                'color'       => '#a855f7',
                'popular'     => true,
            ],
            'business' => [
                'name'        => 'Business Pro',
                'monthly'     => 1999,
                'yearly'      => 19999,
                'description' => 'For businesses and agencies',
                'features'    => [
                    'Everything in Professional',
                    'E-commerce store',
                    'Up to 5 team members',
                    'Advanced analytics',
                    'White-label option',
                    'API access',
                    'Dedicated support',
                    'Custom integrations',
                ],
                'color'       => '#fbbf24',
            ],
        ];

        return view('xenoraa.pricing', compact('plans'));
    }

    /**
     * Create a Razorpay order (AJAX endpoint)
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan'     => 'required|in:starter,professional,business',
            'billing'  => 'required|in:monthly,yearly',
        ]);

        $prices = [
            'starter'      => ['monthly' => 49900,  'yearly' => 499900],
            'professional' => ['monthly' => 99900,  'yearly' => 999900],
            'business'     => ['monthly' => 199900, 'yearly' => 1999900],
        ];

        $plan    = $request->plan;
        $billing = $request->billing;
        $amount  = $prices[$plan][$billing];

        try {
            $order = $this->razorpay->order->create([
                'amount'          => $amount,
                'currency'        => 'INR',
                'receipt'         => 'xenoraa_' . $plan . '_' . time(),
                'payment_capture' => 1,
                'notes'           => [
                    'plan'    => $plan,
                    'billing' => $billing,
                    'user_id' => Auth::id() ?? 'guest',
                ],
            ]);

            return response()->json([
                'success'    => true,
                'order_id'   => $order->id,
                'amount'     => $amount,
                'currency'   => 'INR',
                'key_id'     => config('services.razorpay.key_id'),
                'plan'       => $plan,
                'billing'    => $billing,
                'user_name'  => Auth::user()?->name ?? '',
                'user_email' => Auth::user()?->email ?? '',
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment initiation failed. Please try again.'], 500);
        }
    }

    /**
     * Verify payment signature and activate subscription
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
            'plan'                => 'required|in:starter,professional,business',
            'billing'             => 'required|in:monthly,yearly',
        ]);

        try {
            $this->razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (SignatureVerificationError $e) {
            Log::warning('Razorpay signature verification failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment verification failed. Contact support.'], 400);
        }

        // Activate subscription for authenticated user
        if (Auth::check()) {
            $user = Auth::user();
            $trialDays = $billing === 'yearly' ? 0 : 0; // already paid
            $expiresAt = $request->billing === 'yearly'
                ? now()->addYear()
                : now()->addMonth();

            $user->update([
                'plan'             => $request->plan,
                'plan_billing'     => $request->billing,
                'plan_expires_at'  => $expiresAt,
                'payment_id'       => $request->razorpay_payment_id,
                'status'           => 'active',
            ]);

            Log::info("Subscription activated: User #{$user->id} → {$request->plan} ({$request->billing}) | Payment: {$request->razorpay_payment_id}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment successful! Your subscription is now active.',
            'redirect' => Auth::check() ? route('dashboard') : route('login'),
        ]);
    }

    /**
     * Payment success page
     */
    public function success(Request $request)
    {
        return view('xenoraa.payment.success', [
            'plan'       => $request->query('plan', 'starter'),
            'payment_id' => $request->query('payment_id', ''),
        ]);
    }

    /**
     * Payment failed page
     */
    public function failed()
    {
        return view('xenoraa.payment.failed');
    }
}
