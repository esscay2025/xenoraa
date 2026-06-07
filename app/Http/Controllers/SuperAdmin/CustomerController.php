<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\AgentAssignedSubscription;
use App\Models\SaRole;
use App\Services\TenantBootstrapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    private TenantBootstrapService $bootstrap;

    public function __construct(TenantBootstrapService $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    // ── List all customers ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $this->authorize_sa('customers.view');

        $query = User::whereNotNull('username')
            ->whereNull('sa_role_id')
            ->with('agentProfile');

        if ($request->search) {
            $q = $request->search;
            $query->where(fn($w) => $w->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('username', 'like', "%$q%"));
        }
        if ($request->plan) {
            $query->where('plan', $request->plan);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(25)->withQueryString();
        $agents    = Agent::with('user')->where('status', 'active')->get();

        return view('superadmin.customers.index', compact('customers', 'agents'));
    }

    // ── Show single customer ───────────────────────────────────────────────
    public function show($id)
    {
        $this->authorize_sa('customers.view');
        $customer = User::whereNotNull('username')->findOrFail($id);
        $subscription = AgentAssignedSubscription::where('customer_user_id', $id)
            ->with('agent.user')
            ->latest()
            ->first();
        return view('superadmin.customers.show', compact('customer', 'subscription'));
    }

    // ── Create customer form ───────────────────────────────────────────────
    public function create()
    {
        $this->authorize_sa('customers.create');
        $agents = Agent::with('user')->where('status', 'active')->get();
        $professions = [
            'influencer'   => 'Influencer / Content Creator',
            'consultant'   => 'Consultant / Coach',
            'advocate'     => 'Advocate / Lawyer',
            'entrepreneur' => 'Entrepreneur / Startup',
            'doctor'       => 'Doctor / Healthcare',
            'politician'   => 'Politician / Public Figure',
        ];
        return view('superadmin.customers.create', compact('agents', 'professions'));
    }

    // ── Store new customer ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorize_sa('customers.create');

        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|max:50|unique:users,username|regex:/^[a-z0-9_]+$/',
            'profession' => 'required|string',
            'plan'       => 'required|in:starter,professional,business',
            'password'   => 'required|string|min:8',
            'phone'      => 'nullable|string|max:20',
            'city'       => 'nullable|string|max:100',
            'agent_id'   => 'nullable|exists:agents,id',
            'duration_months' => 'nullable|integer|min:1|max:24',
        ]);

        DB::beginTransaction();
        try {
            // Get the admin role ID
            $adminRole = DB::table('roles')->where('name', 'admin')->first();

            $customer = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'username'   => $validated['username'],
                'password'   => Hash::make($validated['password']),
                'profession' => $validated['profession'],
                'plan'       => $validated['plan'],
                'phone'      => $validated['phone'] ?? null,
                'city'       => $validated['city'] ?? null,
                'role_id'    => $adminRole?->id,
                'status'     => 'active',
                'created_by_sa' => auth()->id(),
                'plan_expires_at' => now()->addMonths($validated['duration_months'] ?? 1),
                'onboarding_completed' => true,
            ]);

            // Bootstrap tenant data
            $this->bootstrap->bootstrap($customer);

            // If assigned to an agent, record the subscription
            if (!empty($validated['agent_id'])) {
                $agent = Agent::findOrFail($validated['agent_id']);
                $months = $validated['duration_months'] ?? 1;
                $planPrice = AgentAssignedSubscription::planPrice($validated['plan'], $months);
                $commissionAmount = round($planPrice * $agent->commission_rate / 100, 2);

                // Find a valid allotment
                $allotment = $agent->allotments()
                    ->where('plan', $validated['plan'])
                    ->where('used', '<', DB::raw('quantity'))
                    ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
                    ->orderBy('created_at')
                    ->first();

                AgentAssignedSubscription::create([
                    'agent_id'          => $agent->id,
                    'customer_user_id'  => $customer->id,
                    'allotment_id'      => $allotment?->id,
                    'plan'              => $validated['plan'],
                    'duration_months'   => $months,
                    'starts_at'         => now()->toDateString(),
                    'expires_at'        => now()->addMonths($months)->toDateString(),
                    'status'            => 'active',
                    'plan_price'        => $planPrice,
                    'commission_rate'   => $agent->commission_rate,
                    'commission_amount' => $commissionAmount,
                    'commission_status' => 'pending',
                ]);

                // Update allotment used count
                if ($allotment) {
                    $allotment->increment('used');
                }

                // Update agent usage
                $agent->increment('subscriptions_used');
            }

            DB::commit();
            return redirect()->route('superadmin.customers.show', $customer->id)
                ->with('success', "Customer {$customer->name} created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    // ── Edit customer ──────────────────────────────────────────────────────
    public function edit($id)
    {
        $this->authorize_sa('customers.edit');
        $customer = User::whereNotNull('username')->findOrFail($id);
        return view('superadmin.customers.edit', compact('customer'));
    }

    // ── Update customer ────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $this->authorize_sa('customers.edit');
        $customer = User::whereNotNull('username')->findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $id,
            'plan'       => 'required|in:starter,professional,business',
            'status'     => 'required|in:active,inactive,suspended',
            'phone'      => 'nullable|string|max:20',
            'city'       => 'nullable|string|max:100',
            'plan_expires_at' => 'nullable|date',
        ]);

        $customer->update($validated);
        return back()->with('success', 'Customer updated successfully.');
    }

    // ── Assign subscription ────────────────────────────────────────────────
    public function assignSubscription(Request $request, $id)
    {
        $this->authorize_sa('subscriptions.assign');
        $customer = User::findOrFail($id);

        $validated = $request->validate([
            'plan'            => 'required|in:starter,professional,business',
            'duration_months' => 'required|integer|min:1|max:24',
            'agent_id'        => 'nullable|exists:agents,id',
        ]);

        $months = $validated['duration_months'];
        $customer->update([
            'plan'            => $validated['plan'],
            'plan_expires_at' => now()->addMonths($months),
            'status'          => 'active',
        ]);

        if (!empty($validated['agent_id'])) {
            $agent = Agent::findOrFail($validated['agent_id']);
            $planPrice = AgentAssignedSubscription::planPrice($validated['plan'], $months);
            $commissionAmount = round($planPrice * $agent->commission_rate / 100, 2);

            AgentAssignedSubscription::create([
                'agent_id'          => $agent->id,
                'customer_user_id'  => $customer->id,
                'plan'              => $validated['plan'],
                'duration_months'   => $months,
                'starts_at'         => now()->toDateString(),
                'expires_at'        => now()->addMonths($months)->toDateString(),
                'status'            => 'active',
                'plan_price'        => $planPrice,
                'commission_rate'   => $agent->commission_rate,
                'commission_amount' => $commissionAmount,
                'commission_status' => 'pending',
            ]);

            $agent->increment('subscriptions_used');
        }

        return back()->with('success', 'Subscription assigned successfully.');
    }

    // ── Delete customer ────────────────────────────────────────────────────
    public function destroy($id)
    {
        $this->authorize_sa('customers.delete');
        $customer = User::whereNotNull('username')->findOrFail($id);
        $customer->delete();
        return redirect()->route('superadmin.customers.index')
            ->with('success', 'Customer deleted.');
    }

    // ── Permission gate ────────────────────────────────────────────────────
    private function authorize_sa(string $permission): void
    {
        if (!auth()->user()->hasSaPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }
}
