<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\SaRole;
use App\Models\AgentSubscriptionAllotment;
use App\Models\AgentAssignedSubscription;
use App\Models\AgentCommissionPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\TenantBootstrapService;

class AgentController extends Controller
{
    // ── List all agents ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $this->authorize_sa('agents.view');

        $query = Agent::with('user')->withCount('assignedSubscriptions');

        if ($request->search) {
            $q = $request->search;
            $query->whereHas('user', fn($w) => $w->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%"));
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $agents = $query->latest()->paginate(20)->withQueryString();
        return view('superadmin.agents.index', compact('agents'));
    }

    // ── Show single agent ──────────────────────────────────────────────────
    public function show($id)
    {
        $this->authorize_sa('agents.view');
        $agent = Agent::with([
            'user',
            'allotments.assignedBy',
            'assignedSubscriptions.customer',
            'commissionPayouts.processedBy',
        ])->findOrFail($id);

        $pendingCommission  = $agent->pending_commission;
        $totalEarned        = $agent->total_commission_earned;
        $totalPaid          = $agent->total_commission_paid;
        $activeSubscribers  = $agent->active_subscribers_count;

        $recentSubscriptions = $agent->assignedSubscriptions()
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('superadmin.agents.show', compact(
            'agent', 'pendingCommission', 'totalEarned', 'totalPaid',
            'activeSubscribers', 'recentSubscriptions'
        ));
    }

    // ── Create agent form ──────────────────────────────────────────────────
    public function create()
    {
        $this->authorize_sa('agents.create');
        return view('superadmin.agents.create');
    }

    // ── Store new agent ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorize_sa('agents.create');

        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:8',
            'phone'           => 'nullable|string|max:20',
            'company_name'    => 'nullable|string|max:150',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'pan_number'      => 'nullable|string|max:20',
            'gst_number'      => 'nullable|string|max:20',
            'bank_name'       => 'nullable|string|max:100',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_ifsc'       => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $agentSaRole = SaRole::where('name', 'agent')->first();

            $user = User::create([
                'name'          => $validated['name'],
                'email'         => $validated['email'],
                'password'      => Hash::make($validated['password']),
                'phone'         => $validated['phone'] ?? null,
                'city'          => $validated['city'] ?? null,
                'status'        => 'active',
                'sa_role_id'    => $agentSaRole?->id,
                'created_by_sa' => auth()->id(),
            ]);

            $agent = Agent::create([
                'user_id'         => $user->id,
                'agent_code'      => Agent::generateAgentCode(),
                'company_name'    => $validated['company_name'] ?? null,
                'phone'           => $validated['phone'] ?? null,
                'city'            => $validated['city'] ?? null,
                'state'           => $validated['state'] ?? null,
                'country'         => $validated['country'] ?? 'India',
                'commission_rate' => $validated['commission_rate'],
                'pan_number'      => $validated['pan_number'] ?? null,
                'gst_number'      => $validated['gst_number'] ?? null,
                'bank_name'       => $validated['bank_name'] ?? null,
                'bank_account_no' => $validated['bank_account_no'] ?? null,
                'bank_ifsc'       => $validated['bank_ifsc'] ?? null,
                'status'          => 'active',
            ]);

            DB::commit();
            return redirect()->route('superadmin.agents.show', $agent->id)
                ->with('success', "Agent {$user->name} ({$agent->agent_code}) created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create agent: ' . $e->getMessage());
        }
    }

    // ── Edit agent form ────────────────────────────────────────────────────
    public function edit($id)
    {
        $this->authorize_sa('agents.edit');
        $agent = Agent::with('user')->findOrFail($id);
        return view('superadmin.agents.edit', compact('agent'));
    }

    // ── Update agent ───────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $this->authorize_sa('agents.edit');
        $agent = Agent::with('user')->findOrFail($id);

        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'company_name'    => 'nullable|string|max:150',
            'phone'           => 'nullable|string|max:20',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'status'          => 'required|in:active,inactive,suspended',
            'pan_number'      => 'nullable|string|max:20',
            'gst_number'      => 'nullable|string|max:20',
            'bank_name'       => 'nullable|string|max:100',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_ifsc'       => 'nullable|string|max:20',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $agent->update($validated);
        $agent->user->update([
            'name'  => $request->name ?? $agent->user->name,
            'phone' => $validated['phone'] ?? $agent->user->phone,
        ]);

        return back()->with('success', 'Agent updated successfully.');
    }

    // ── Allot subscriptions to agent ──────────────────────────────────────
    public function allot(Request $request, $id)
    {
        $this->authorize_sa('agents.allot');
        $agent = Agent::findOrFail($id);

        $validated = $request->validate([
            'plan'       => 'required|in:starter,professional,business',
            'quantity'   => 'required|integer|min:1|max:10000',
            'expires_at' => 'nullable|date|after:today',
            'notes'      => 'nullable|string|max:500',
        ]);

        AgentSubscriptionAllotment::create([
            'agent_id'    => $agent->id,
            'assigned_by' => auth()->id(),
            'plan'        => $validated['plan'],
            'quantity'    => $validated['quantity'],
            'used'        => 0,
            'expires_at'  => $validated['expires_at'] ?? null,
            'notes'       => $validated['notes'] ?? null,
        ]);

        // Update total quota
        $agent->increment('subscription_quota', $validated['quantity']);

        return back()->with('success', "{$validated['quantity']} {$validated['plan']} subscriptions allotted to {$agent->user->name}.");
    }

    // ── Approve commission ─────────────────────────────────────────────────
    public function approveCommission(Request $request, $agentId, $subscriptionId)
    {
        $this->authorize_sa('agents.commissions');

        $sub = AgentAssignedSubscription::where('agent_id', $agentId)
            ->findOrFail($subscriptionId);

        $sub->update(['commission_status' => 'approved']);
        return back()->with('success', 'Commission approved.');
    }

    // ── Pay commission ─────────────────────────────────────────────────────
    public function payCommission(Request $request, $id)
    {
        $this->authorize_sa('agents.commissions');
        $agent = Agent::findOrFail($id);

        $validated = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,upi,cheque,cash',
            'reference_no'   => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            AgentCommissionPayout::create([
                'agent_id'       => $agent->id,
                'processed_by'   => auth()->id(),
                'amount'         => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_no'   => $validated['reference_no'] ?? null,
                'paid_at'        => now()->toDateString(),
                'notes'          => $validated['notes'] ?? null,
            ]);

            // Mark approved commissions as paid
            $agent->assignedSubscriptions()
                ->where('commission_status', 'approved')
                ->update(['commission_status' => 'paid', 'commission_paid_at' => now()]);

            DB::commit();
            return back()->with('success', '₹' . number_format($validated['amount'], 2) . ' commission paid to ' . $agent->user->name . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process payout: ' . $e->getMessage());
        }
    }

    // ── Agent dashboard (for agent users) ─────────────────────────────────
    public function agentDashboard()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;

        if (!$agent) {
            abort(403, 'Agent profile not found.');
        }

        $subscribers = $agent->assignedSubscriptions()
            ->with('customer')
            ->latest()
            ->paginate(20);

        $allotments = $agent->allotments()
            ->with('assignedBy')
            ->latest()
            ->get();

        $payouts = $agent->commissionPayouts()
            ->with('processedBy')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_subscribers'  => $agent->assignedSubscriptions()->count(),
            'active_subscribers' => $agent->assignedSubscriptions()->where('status', 'active')->count(),
            'pending_commission' => $agent->pending_commission,
            'total_earned'       => $agent->total_commission_earned,
            'total_paid'         => $agent->total_commission_paid,
            'available_quota'    => $agent->available_quota,
        ];

        return view('agent.dashboard', compact('agent', 'subscribers', 'allotments', 'payouts', 'stats'));
    }

    // ── Agent create customer (limited to their quota) ─────────────────────
    public function agentCreateCustomer()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent || $agent->available_quota <= 0) {
            return back()->with('error', 'You have no available subscription quota. Contact your administrator.');
        }
        $professions = [
            'influencer'   => 'Influencer / Content Creator',
            'consultant'   => 'Consultant / Coach',
            'advocate'     => 'Advocate / Lawyer',
            'entrepreneur' => 'Entrepreneur / Startup',
            'doctor'       => 'Doctor / Healthcare',
            'politician'   => 'Politician / Public Figure',
        ];
        // Get plans the agent has allotments for
        $availablePlans = $agent->allotments()
            ->where('used', '<', DB::raw('quantity'))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->pluck('plan')
            ->unique()
            ->values()
            ->toArray();

        return view('agent.create-customer', compact('agent', 'professions', 'availablePlans'));
    }

    // ── Agent store customer ───────────────────────────────────────────────
    public function agentStoreCustomer(Request $request)
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;

        if (!$agent || $agent->available_quota <= 0) {
            return back()->with('error', 'No available subscription quota.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|max:50|unique:users,username|regex:/^[a-z0-9_]+$/',
            'profession' => 'required|string',
            'plan'       => 'required|in:starter,professional,business',
            'password'   => 'required|string|min:8',
            'phone'      => 'nullable|string|max:20',
        ]);

        // Verify agent has allotment for this plan
        $allotment = $agent->allotments()
            ->where('plan', $validated['plan'])
            ->where('used', '<', DB::raw('quantity'))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->orderBy('created_at')
            ->first();

        if (!$allotment) {
            return back()->withInput()->with('error', "You don't have available quota for the {$validated['plan']} plan.");
        }

        DB::beginTransaction();
        try {
            $bootstrap = app(TenantBootstrapService::class);
            $adminRole = DB::table('roles')->where('name', 'admin')->first();

            $customer = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'username'   => $validated['username'],
                'password'   => Hash::make($validated['password']),
                'profession' => $validated['profession'],
                'plan'       => $validated['plan'],
                'phone'      => $validated['phone'] ?? null,
                'role_id'    => $adminRole?->id,
                'status'     => 'active',
                'created_by_sa' => $user->id,
                'plan_expires_at' => now()->addMonth(),
                'onboarding_completed' => true,
            ]);

            $bootstrap->bootstrap($customer);

            $planPrice = AgentAssignedSubscription::planPrice($validated['plan'], 1);
            $commissionAmount = round($planPrice * $agent->commission_rate / 100, 2);

            AgentAssignedSubscription::create([
                'agent_id'          => $agent->id,
                'customer_user_id'  => $customer->id,
                'allotment_id'      => $allotment->id,
                'plan'              => $validated['plan'],
                'duration_months'   => 1,
                'starts_at'         => now()->toDateString(),
                'expires_at'        => now()->addMonth()->toDateString(),
                'status'            => 'active',
                'plan_price'        => $planPrice,
                'commission_rate'   => $agent->commission_rate,
                'commission_amount' => $commissionAmount,
                'commission_status' => 'pending',
            ]);

            $allotment->increment('used');
            $agent->increment('subscriptions_used');

            DB::commit();
            return redirect()->route('agent.dashboard')
                ->with('success', "Customer {$customer->name} created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    // ── Agent: My Customers ───────────────────────────────────────────────
    public function agentMyCustomers()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent) abort(403, 'Agent profile not found.');
        $subscribers = $agent->assignedSubscriptions()
            ->with('customer')
            ->latest()
            ->paginate(20);
        return view('agent.my-customers', compact('agent', 'subscribers'));
    }

    // ── Agent: Quota ───────────────────────────────────────────────────────
    public function agentQuota()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent) abort(403, 'Agent profile not found.');
        $allotments = $agent->allotments()->with('assignedBy')->latest()->get();
        $stats = [
            'total_allotted' => $allotments->sum('quantity'),
            'total_used'     => $allotments->sum('used'),
            'available'      => $agent->available_quota,
        ];
        return view('agent.quota', compact('agent', 'allotments', 'stats'));
    }

    // ── Agent: Commissions ────────────────────────────────────────────────
    public function agentCommissions()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent) abort(403, 'Agent profile not found.');
        $commissions = $agent->assignedSubscriptions()
            ->with('customer')
            ->whereNotNull('commission_amount')
            ->latest()
            ->paginate(20);
        $stats = [
            'total_earned'       => $agent->total_commission_earned ?? 0,
            'pending_commission' => $agent->pending_commission ?? 0,
            'total_paid'         => $agent->total_commission_paid ?? 0,
        ];
        return view('agent.commissions', compact('agent', 'commissions', 'stats'));
    }

    // ── Agent: Payouts ────────────────────────────────────────────────────
    public function agentPayouts()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent) abort(403, 'Agent profile not found.');
        $payouts = $agent->commissionPayouts()->with('processedBy')->latest()->paginate(20);
        $stats = [
            'total_paid'         => $agent->total_commission_paid ?? 0,
            'pending_commission' => $agent->pending_commission ?? 0,
        ];
        return view('agent.payouts', compact('agent', 'payouts', 'stats'));
    }

    // ── Agent: Profile ────────────────────────────────────────────────────
    public function agentProfile()
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent) abort(403, 'Agent profile not found.');
        return view('agent.profile', compact('agent', 'user'));
    }

    public function agentUpdateProfile(Request $request)
    {
        $user  = auth()->user();
        $agent = $user->agentProfile;
        if (!$agent) abort(403, 'Agent profile not found.');
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update(['name' => $request->name]);
        $agent->update(['phone' => $request->phone]);
        return back()->with('success', 'Profile updated successfully.');
    }

    // ── Permission gate ────────────────────────────────────────────────────
    private function authorize_sa(string $permission): void
    {
        if (!auth()->user()->hasSaPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }
}
