<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmAccount;
use App\Models\CrmContact;
use App\Models\CrmDeal;
use App\Models\CrmActivity;
use App\Models\CrmLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewCrmController extends Controller
{
    private function tid(): int
    {
        return auth()->user()->getTenantId();
    }

    // ─────────────────────────────────────────────────────────────
    // CRM Dashboard
    // ─────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $tid = $this->tid();

        $stats = [
            'accounts'  => CrmAccount::where('user_id', $tid)->count(),
            'contacts'  => CrmContact::where('user_id', $tid)->count(),
            'leads'     => CrmLead::where('user_id', $tid)->count(),
            'open_deals'=> CrmDeal::where('user_id', $tid)->whereNotIn('stage', ['closed_won','closed_lost'])->count(),
            'won_value' => CrmDeal::where('user_id', $tid)->where('stage','closed_won')->sum('value'),
            'pipeline_value' => CrmDeal::where('user_id', $tid)->whereNotIn('stage', ['closed_won','closed_lost'])->sum('value'),
        ];

        $recentLeads = CrmLead::where('user_id', $tid)
            ->orderByDesc('created_at')->take(5)->get();

        $recentDeals = CrmDeal::with(['account','contact'])
            ->where('user_id', $tid)
            ->orderByDesc('created_at')->take(5)->get();

        $upcomingActivities = CrmActivity::where('user_id', $tid)
            ->where('status', 'pending')
            ->whereNotNull('due_at')
            ->orderBy('due_at')
            ->take(5)->get();

        $dealsByStage = CrmDeal::where('user_id', $tid)
            ->whereNotIn('stage', ['closed_won','closed_lost'])
            ->select('stage', DB::raw('count(*) as count'), DB::raw('sum(value) as total'))
            ->groupBy('stage')
            ->get();

        return view('admin.crm.dashboard', compact(
            'stats', 'recentLeads', 'recentDeals', 'upcomingActivities', 'dealsByStage'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // ACCOUNTS
    // ─────────────────────────────────────────────────────────────
    public function accountsIndex(Request $request)
    {
        $tid = $this->tid();
        $query = CrmAccount::where('user_id', $tid);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('industry', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->type) $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);

        $accounts = $query->withCount(['contacts','deals','leads'])->orderByDesc('created_at')->paginate(20);

        return view('admin.crm.accounts.index', compact('accounts'));
    }

    public function accountCreate()
    {
        return view('admin.crm.accounts.form', ['account' => null]);
    }

    public function accountStore(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:prospect,customer,partner,vendor',
            'industry'       => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'annual_revenue' => 'nullable|numeric|min:0',
            'employees'      => 'nullable|integer|min:0',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);
        $data['user_id'] = $this->tid();
        CrmAccount::create($data);

        return redirect()->route('admin.newcrm.accounts')->with('success', 'Account created.');
    }

    public function accountEdit(CrmAccount $account)
    {
        $this->authorise($account);
        return view('admin.crm.accounts.form', compact('account'));
    }

    public function accountUpdate(Request $request, CrmAccount $account)
    {
        $this->authorise($account);
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:prospect,customer,partner,vendor',
            'industry'       => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'annual_revenue' => 'nullable|numeric|min:0',
            'employees'      => 'nullable|integer|min:0',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);
        $account->update($data);
        return redirect()->route('admin.newcrm.accounts')->with('success', 'Account updated.');
    }

    public function accountDestroy(CrmAccount $account)
    {
        $this->authorise($account);
        $account->delete();
        return redirect()->route('admin.newcrm.accounts')->with('success', 'Account deleted.');
    }

    public function accountShow(CrmAccount $account)
    {
        $this->authorise($account);
        $account->load(['contacts','deals','leads']);
        $activities = CrmActivity::where('user_id', $this->tid())
            ->where('related_type', 'CrmAccount')
            ->where('related_id', $account->id)
            ->orderByDesc('created_at')->take(10)->get();
        return view('admin.crm.accounts.show', compact('account', 'activities'));
    }

    // ─────────────────────────────────────────────────────────────
    // CONTACTS
    // ─────────────────────────────────────────────────────────────
    public function contactsIndex(Request $request)
    {
        $tid = $this->tid();
        $query = CrmContact::with('account')->where('user_id', $tid);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->source) $query->where('source', $request->source);
        if ($request->account_id) $query->where('account_id', $request->account_id);

        $contacts = $query->orderByDesc('created_at')->paginate(20);
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();

        return view('admin.crm.contacts.index', compact('contacts', 'accounts'));
    }

    public function contactCreate()
    {
        $accounts = CrmAccount::where('user_id', $this->tid())->orderBy('name')->get();
        return view('admin.crm.contacts.form', ['contact' => null, 'accounts' => $accounts]);
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'job_title'  => 'nullable|string|max:150',
            'department' => 'nullable|string|max:100',
            'city'       => 'nullable|string|max:100',
            'country'    => 'nullable|string|max:100',
            'account_id' => 'nullable|integer',
            'source'     => 'required|in:manual,ai_chatbot,website_form,referral,linkedin,cold_outreach,other',
            'notes'      => 'nullable|string',
            'status'     => 'required|in:active,inactive,unsubscribed',
        ]);
        $data['user_id'] = $this->tid();
        CrmContact::create($data);
        return redirect()->route('admin.newcrm.contacts')->with('success', 'Contact created.');
    }

    public function contactEdit(CrmContact $contact)
    {
        $this->authorise($contact);
        $accounts = CrmAccount::where('user_id', $this->tid())->orderBy('name')->get();
        return view('admin.crm.contacts.form', compact('contact', 'accounts'));
    }

    public function contactUpdate(Request $request, CrmContact $contact)
    {
        $this->authorise($contact);
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'job_title'  => 'nullable|string|max:150',
            'department' => 'nullable|string|max:100',
            'city'       => 'nullable|string|max:100',
            'country'    => 'nullable|string|max:100',
            'account_id' => 'nullable|integer',
            'source'     => 'required|in:manual,ai_chatbot,website_form,referral,linkedin,cold_outreach,other',
            'notes'      => 'nullable|string',
            'status'     => 'required|in:active,inactive,unsubscribed',
        ]);
        $contact->update($data);
        return redirect()->route('admin.newcrm.contacts')->with('success', 'Contact updated.');
    }

    public function contactDestroy(CrmContact $contact)
    {
        $this->authorise($contact);
        $contact->delete();
        return redirect()->route('admin.newcrm.contacts')->with('success', 'Contact deleted.');
    }

    // ─────────────────────────────────────────────────────────────
    // LEADS (unified — manual + AI chatbot + all sources)
    // ─────────────────────────────────────────────────────────────
    public function leadsIndex(Request $request)
    {
        $tid = $this->tid();
        $query = CrmLead::with(['account'])->where('user_id', $tid);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->source) $query->where('source', $request->source);
        if ($request->priority) $query->where('priority', $request->priority);

        $leads = $query->orderByDesc('created_at')->paginate(25);
        $accounts = CrmAccount::where('user_id', $tid)->orderBy('name')->get();

        $stats = [
            'total'    => CrmLead::where('user_id', $tid)->count(),
            'new'      => CrmLead::where('user_id', $tid)->where('status', 'new')->count(),
            'ai'       => CrmLead::where('user_id', $tid)->where('source', 'ai_chatbot')->count(),
            'manual'   => CrmLead::where('user_id', $tid)->where('source', 'manual')->count(),
            'converted'=> CrmLead::where('user_id', $tid)->where('status', 'converted')->count(),
        ];

        return view('admin.crm.leads.index', compact('leads', 'accounts', 'stats'));
    }

    public function leadCreate()
    {
        $accounts = CrmAccount::where('user_id', $this->tid())->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $this->tid())->orderBy('first_name')->get();
        return view('admin.crm.leads.form', ['lead' => null, 'accounts' => $accounts, 'contacts' => $contacts]);
    }

    public function leadStore(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'company'    => 'nullable|string|max:255',
            'message'    => 'nullable|string',
            'status'     => 'required|in:new,contacted,qualified,proposal,converted,lost',
            'source'     => 'required|in:manual,ai_chatbot,website_form,referral,linkedin,cold_outreach,other',
            'priority'   => 'required|in:low,medium,high,urgent',
            'deal_value' => 'nullable|numeric|min:0',
            'account_id' => 'nullable|integer',
            'contact_id' => 'nullable|integer',
        ]);
        $data['user_id'] = $this->tid();
        CrmLead::create($data);
        return redirect()->route('admin.newcrm.leads')->with('success', 'Lead created.');
    }

    public function leadShow(CrmLead $lead)
    {
        $this->authorise($lead);
        $lead->load(['account', 'requirements', 'conversations']);
        $activities = CrmActivity::where('user_id', $this->tid())
            ->where('related_type', 'CrmLead')
            ->where('related_id', $lead->id)
            ->orderByDesc('created_at')->get();
        return view('admin.crm.leads.show', compact('lead', 'activities'));
    }

    public function leadEdit(CrmLead $lead)
    {
        $this->authorise($lead);
        $accounts = CrmAccount::where('user_id', $this->tid())->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $this->tid())->orderBy('first_name')->get();
        return view('admin.crm.leads.form', compact('lead', 'accounts', 'contacts'));
    }

    public function leadUpdate(Request $request, CrmLead $lead)
    {
        $this->authorise($lead);
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'company'    => 'nullable|string|max:255',
            'message'    => 'nullable|string',
            'status'     => 'required|in:new,contacted,qualified,proposal,converted,lost',
            'source'     => 'required|in:manual,ai_chatbot,website_form,referral,linkedin,cold_outreach,other',
            'priority'   => 'required|in:low,medium,high,urgent',
            'deal_value' => 'nullable|numeric|min:0',
            'account_id' => 'nullable|integer',
            'contact_id' => 'nullable|integer',
        ]);
        $lead->update($data);
        return redirect()->route('admin.newcrm.leads')->with('success', 'Lead updated.');
    }

    public function leadDestroy(CrmLead $lead)
    {
        $this->authorise($lead);
        $lead->delete();
        return redirect()->route('admin.newcrm.leads')->with('success', 'Lead deleted.');
    }

    // Convert lead to deal
    public function leadConvert(Request $request, CrmLead $lead)
    {
        $this->authorise($lead);
        $data = $request->validate([
            'deal_title' => 'required|string|max:255',
            'deal_value' => 'nullable|numeric|min:0',
            'stage'      => 'required|in:prospecting,qualification,proposal,negotiation',
            'expected_close' => 'nullable|date',
        ]);

        $deal = CrmDeal::create([
            'user_id'        => $this->tid(),
            'lead_id'        => $lead->id,
            'account_id'     => $lead->account_id,
            'contact_id'     => $lead->contact_id,
            'title'          => $data['deal_title'],
            'value'          => $data['deal_value'] ?? $lead->deal_value ?? 0,
            'stage'          => $data['stage'],
            'expected_close' => $data['expected_close'],
        ]);

        $lead->update(['status' => 'converted']);

        return redirect()->route('admin.newcrm.deals')->with('success', 'Lead converted to deal #' . $deal->id . '.');
    }

    // ─────────────────────────────────────────────────────────────
    // DEALS & PIPELINE
    // ─────────────────────────────────────────────────────────────
    public function dealsIndex(Request $request)
    {
        $tid = $this->tid();
        $query = CrmDeal::with(['account','contact'])->where('user_id', $tid);

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->stage) $query->where('stage', $request->stage);

        $deals = $query->orderByDesc('created_at')->paginate(25);

        $pipeline = [];
        foreach (CrmDeal::STAGES as $key => $info) {
            $pipeline[$key] = [
                'info'  => $info,
                'deals' => CrmDeal::with(['account','contact'])
                    ->where('user_id', $tid)
                    ->where('stage', $key)
                    ->orderByDesc('created_at')
                    ->get(),
                'total' => CrmDeal::where('user_id', $tid)->where('stage', $key)->sum('value'),
            ];
        }

        return view('admin.crm.deals.index', compact('deals', 'pipeline'));
    }

    public function dealCreate()
    {
        $accounts = CrmAccount::where('user_id', $this->tid())->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $this->tid())->orderBy('first_name')->get();
        return view('admin.crm.deals.form', ['deal' => null, 'accounts' => $accounts, 'contacts' => $contacts]);
    }

    public function dealStore(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'value'          => 'nullable|numeric|min:0',
            'currency'       => 'required|string|max:5',
            'stage'          => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability'    => 'nullable|integer|min:0|max:100',
            'expected_close' => 'nullable|date',
            'account_id'     => 'nullable|integer',
            'contact_id'     => 'nullable|integer',
            'notes'          => 'nullable|string',
            'lost_reason'    => 'nullable|string|max:255',
        ]);
        $data['user_id'] = $this->tid();
        if (in_array($data['stage'], ['closed_won','closed_lost'])) {
            $data['closed_at'] = now();
        }
        CrmDeal::create($data);
        return redirect()->route('admin.newcrm.deals')->with('success', 'Deal created.');
    }

    public function dealEdit(CrmDeal $deal)
    {
        $this->authorise($deal);
        $accounts = CrmAccount::where('user_id', $this->tid())->orderBy('name')->get();
        $contacts = CrmContact::where('user_id', $this->tid())->orderBy('first_name')->get();
        return view('admin.crm.deals.form', compact('deal', 'accounts', 'contacts'));
    }

    public function dealUpdate(Request $request, CrmDeal $deal)
    {
        $this->authorise($deal);
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'value'          => 'nullable|numeric|min:0',
            'currency'       => 'required|string|max:5',
            'stage'          => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability'    => 'nullable|integer|min:0|max:100',
            'expected_close' => 'nullable|date',
            'account_id'     => 'nullable|integer',
            'contact_id'     => 'nullable|integer',
            'notes'          => 'nullable|string',
            'lost_reason'    => 'nullable|string|max:255',
        ]);
        if (in_array($data['stage'], ['closed_won','closed_lost']) && !$deal->closed_at) {
            $data['closed_at'] = now();
        }
        $deal->update($data);
        return redirect()->route('admin.newcrm.deals')->with('success', 'Deal updated.');
    }

    public function dealDestroy(CrmDeal $deal)
    {
        $this->authorise($deal);
        $deal->delete();
        return redirect()->route('admin.newcrm.deals')->with('success', 'Deal deleted.');
    }

    // AJAX: update deal stage from Kanban
    public function dealUpdateStage(Request $request, CrmDeal $deal)
    {
        $this->authorise($deal);
        $request->validate(['stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost']);
        $update = ['stage' => $request->stage];
        if (in_array($request->stage, ['closed_won','closed_lost'])) {
            $update['closed_at'] = now();
        }
        $deal->update($update);
        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // ACTIVITIES
    // ─────────────────────────────────────────────────────────────
    public function activitiesIndex(Request $request)
    {
        $tid = $this->tid();
        $query = CrmActivity::where('user_id', $tid);

        if ($request->type) $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);

        $activities = $query->orderByDesc('created_at')->paginate(25);

        return view('admin.crm.activities.index', compact('activities'));
    }

    public function activityStore(Request $request)
    {
        $data = $request->validate([
            'type'         => 'required|in:call,email,meeting,note,task,demo',
            'subject'      => 'required|string|max:255',
            'description'  => 'nullable|string',
            'related_type' => 'nullable|string|in:CrmLead,CrmDeal,CrmContact,CrmAccount',
            'related_id'   => 'nullable|integer',
            'due_at'       => 'nullable|date',
            'status'       => 'required|in:pending,completed,cancelled',
        ]);
        $data['user_id'] = $this->tid();
        if ($data['status'] === 'completed') {
            $data['completed_at'] = now();
        }
        CrmActivity::create($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Activity logged.');
    }

    public function activityComplete(CrmActivity $activity)
    {
        $this->authorise($activity);
        $activity->update(['status' => 'completed', 'completed_at' => now()]);
        return redirect()->back()->with('success', 'Activity marked complete.');
    }

    public function activityDestroy(CrmActivity $activity)
    {
        $this->authorise($activity);
        $activity->delete();
        return redirect()->back()->with('success', 'Activity deleted.');
    }

    // ─────────────────────────────────────────────────────────────
    // Authorization helper
    // ─────────────────────────────────────────────────────────────
    private function authorise($model): void
    {
        if ($model->user_id !== $this->tid()) {
            abort(403);
        }
    }
}
