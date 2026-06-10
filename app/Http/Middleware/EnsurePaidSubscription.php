<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsurePaidSubscription
 *
 * Gates admin dashboard and onboarding routes behind a valid, paid subscription.
 *
 * - Tenant admins with status 'pending_payment' are redirected to the checkout page.
 * - Tenant admins with an expired subscription (plan_expires_at in the past) are also
 *   redirected to checkout.
 * - Sub-users (staff/visitor) of an unpaid tenant receive a 403 error.
 * - SuperAdmins, SA Staff, and SA Agents are always allowed through.
 */
class EnsurePaidSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // SuperAdmins, SA Staff, SA Agents are always exempt from payment checks
        if ($user->isSuperAdmin() || $user->isSaStaff() || $user->isSaAgent()) {
            return $next($request);
        }

        // Resolve the tenant owner (the admin user who owns the subscription)
        $tenant = $user->isAdmin() ? $user : ($user->tenantOwner ?? null);

        // If we cannot resolve a tenant (e.g., visitor on a public page), allow through
        if (!$tenant || !$tenant->isAdmin()) {
            return $next($request);
        }

        // Check if the tenant's subscription is pending payment
        if ($tenant->status === 'pending_payment') {
            if ($user->id === $tenant->id) {
                // Redirect the admin to the checkout page
                return redirect()->route('payment.checkout', [
                    'plan'    => $tenant->plan ?? 'starter',
                    'billing' => session('pending_billing', 'monthly'),
                ])->with('info', 'Please complete your payment to access your dashboard.');
            } else {
                // Sub-user of an unpaid tenant
                abort(403, 'Your organization\'s subscription is pending payment. Please contact your administrator.');
            }
        }

        // Check if the tenant's subscription has expired
        if ($tenant->plan_expires_at && $tenant->plan_expires_at->isPast()) {
            if ($user->id === $tenant->id) {
                return redirect()->route('payment.checkout', [
                    'plan'    => $tenant->plan ?? 'starter',
                    'billing' => session('pending_billing', 'monthly'),
                ])->with('error', 'Your subscription has expired. Please renew to continue.');
            } else {
                abort(403, 'Your organization\'s subscription has expired. Please contact your administrator.');
            }
        }

        return $next($request);
    }
}
