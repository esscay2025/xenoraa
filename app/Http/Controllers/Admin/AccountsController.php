<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AccBankAccount;
use App\Models\AccTransaction;
use App\Models\AccIncome;
use App\Models\AccExpense;
use App\Models\AccChartOfAccount;
use App\Models\AccJournalEntry;
use App\Models\AccJournalLine;
use App\Models\AccTaxRate;

class AccountsController extends Controller
{
    // ─── Helpers ────────────────────────────────────────────────
    private function tenantId()
    {
        return Auth::user()->tenant_owner_id ?? Auth::id();
    }

    private function userId()
    {
        return Auth::id();
    }

    // ─── Dashboard ──────────────────────────────────────────────
    public function dashboard()
    {
        $tid = $this->tenantId();

        $bankAccounts     = AccBankAccount::where('tenant_owner_id', $tid)->where('is_active', true)->get();
        $totalCashBalance = $bankAccounts->sum('current_balance');
        $bankAccountCount = $bankAccounts->count();

        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd   = $now->copy()->endOfMonth();

        $incomeThisMonth      = AccIncome::where('tenant_owner_id', $tid)->whereBetween('income_date', [$monthStart, $monthEnd])->where('status', 'received')->sum('amount');
        $incomeThisMonthCount = AccIncome::where('tenant_owner_id', $tid)->whereBetween('income_date', [$monthStart, $monthEnd])->count();
        $expensesThisMonth      = AccExpense::where('tenant_owner_id', $tid)->whereBetween('expense_date', [$monthStart, $monthEnd])->where('status', 'paid')->sum('amount');
        $expensesThisMonthCount = AccExpense::where('tenant_owner_id', $tid)->whereBetween('expense_date', [$monthStart, $monthEnd])->count();
        $netProfitThisMonth   = $incomeThisMonth - $expensesThisMonth;

        $totalReceivables = AccIncome::where('tenant_owner_id', $tid)->where('status', 'pending')->sum('amount');
        $totalPayables    = AccExpense::where('tenant_owner_id', $tid)->where('status', 'pending')->sum('amount');

        $recentTransactions = AccTransaction::where('tenant_owner_id', $tid)->orderByDesc('transaction_date')->limit(8)->get();
        $recentIncome       = AccIncome::where('tenant_owner_id', $tid)->orderByDesc('income_date')->limit(5)->get();
        $recentExpenses     = AccExpense::where('tenant_owner_id', $tid)->orderByDesc('expense_date')->limit(5)->get();

        // Cash flow chart — last 6 months
        $cashFlowLabels   = [];
        $cashFlowIncome   = [];
        $cashFlowExpenses = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $cashFlowLabels[]   = $month->format('M Y');
            $cashFlowIncome[]   = (float) AccIncome::where('tenant_owner_id', $tid)->whereYear('income_date', $month->year)->whereMonth('income_date', $month->month)->where('status', 'received')->sum('amount');
            $cashFlowExpenses[] = (float) AccExpense::where('tenant_owner_id', $tid)->whereYear('expense_date', $month->year)->whereMonth('expense_date', $month->month)->where('status', 'paid')->sum('amount');
        }

        // Expense category pie
        $expCats = AccExpense::where('tenant_owner_id', $tid)->where('status', 'paid')->selectRaw("COALESCE(category, 'Other') as cat, SUM(amount) as total")->groupBy('cat')->orderByDesc('total')->limit(8)->pluck('total', 'cat');
        $expenseCategoryLabels = $expCats->keys()->toArray();
        $expenseCategoryData   = $expCats->values()->map(fn($v) => (float)$v)->toArray();

        return view('admin.accounts.dashboard', compact(
            'bankAccounts', 'totalCashBalance', 'bankAccountCount',
            'incomeThisMonth', 'incomeThisMonthCount',
            'expensesThisMonth', 'expensesThisMonthCount',
            'netProfitThisMonth', 'totalReceivables', 'totalPayables',
            'recentTransactions', 'recentIncome', 'recentExpenses',
            'cashFlowLabels', 'cashFlowIncome', 'cashFlowExpenses',
            'expenseCategoryLabels', 'expenseCategoryData'
        ));
    }

    // ─── Bank Accounts ──────────────────────────────────────────
    public function bankAccounts()
    {
        $bankAccounts = AccBankAccount::where('tenant_owner_id', $this->tenantId())->orderBy('name')->get();
        return view('admin.accounts.bank-accounts', compact('bankAccounts'));
    }

    public function bankAccountsStore(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:191',
            'account_type'    => 'required|in:bank,cash,credit_card,savings,wallet',
            'bank_name'       => 'nullable|string|max:191',
            'account_number'  => 'nullable|string|max:50',
            'ifsc_code'       => 'nullable|string|max:20',
            'currency'        => 'nullable|string|max:10',
            'opening_balance' => 'nullable|numeric',
        ]);
        $data['user_id']          = $this->userId();
        $data['tenant_owner_id']  = $this->tenantId();
        $data['current_balance']  = $data['opening_balance'] ?? 0;
        AccBankAccount::create($data);
        return back()->with('success', 'Bank account added.');
    }

    public function bankAccountsUpdate(Request $request, $id)
    {
        $ba = AccBankAccount::where('tenant_owner_id', $this->tenantId())->findOrFail($id);
        $data = $request->validate([
            'name'            => 'required|string|max:191',
            'account_type'    => 'required|in:bank,cash,credit_card,savings,wallet',
            'bank_name'       => 'nullable|string|max:191',
            'account_number'  => 'nullable|string|max:50',
            'ifsc_code'       => 'nullable|string|max:20',
            'currency'        => 'nullable|string|max:10',
            'opening_balance' => 'nullable|numeric',
        ]);
        $ba->update($data);
        return back()->with('success', 'Bank account updated.');
    }

    public function bankAccountsDelete($id)
    {
        AccBankAccount::where('tenant_owner_id', $this->tenantId())->findOrFail($id)->delete();
        return back()->with('success', 'Bank account deleted.');
    }

    // ─── Transactions ────────────────────────────────────────────
    public function transactions(Request $request)
    {
        $tid = $this->tenantId();
        $bankAccounts = AccBankAccount::where('tenant_owner_id', $tid)->orderBy('name')->get();

        $query = AccTransaction::where('tenant_owner_id', $tid)->with('bankAccount')->orderByDesc('transaction_date');
        if ($request->bank_account_id) $query->where('bank_account_id', $request->bank_account_id);
        if ($request->type)            $query->where('type', $request->type);
        if ($request->date_from)       $query->where('transaction_date', '>=', $request->date_from);
        if ($request->date_to)         $query->where('transaction_date', '<=', $request->date_to);

        $transactions = $query->paginate(25);
        return view('admin.accounts.transactions', compact('transactions', 'bankAccounts'));
    }

    public function transactionsStore(Request $request)
    {
        $data = $request->validate([
            'bank_account_id'  => 'required|integer',
            'type'             => 'required|in:credit,debit',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description'      => 'nullable|string|max:500',
            'category'         => 'nullable|string|max:100',
            'payee'            => 'nullable|string|max:191',
        ]);
        $data['user_id']         = $this->userId();
        $data['tenant_owner_id'] = $this->tenantId();
        $txn = AccTransaction::create($data);

        // Update bank account balance
        $ba = AccBankAccount::find($data['bank_account_id']);
        if ($ba) {
            $ba->current_balance += $data['type'] === 'credit' ? $data['amount'] : -$data['amount'];
            $ba->save();
        }
        return back()->with('success', 'Transaction recorded.');
    }

    public function transactionsDelete($id)
    {
        $txn = AccTransaction::where('tenant_owner_id', $this->tenantId())->findOrFail($id);
        // Reverse balance
        $ba = AccBankAccount::find($txn->bank_account_id);
        if ($ba) {
            $ba->current_balance += $txn->type === 'credit' ? -$txn->amount : $txn->amount;
            $ba->save();
        }
        $txn->delete();
        return back()->with('success', 'Transaction deleted.');
    }

    // ─── Income ──────────────────────────────────────────────────
    public function income(Request $request)
    {
        $tid = $this->tenantId();
        $bankAccounts = AccBankAccount::where('tenant_owner_id', $tid)->orderBy('name')->get();

        $query = AccIncome::where('tenant_owner_id', $tid)->orderByDesc('income_date');
        if ($request->status)   $query->where('status', $request->status);
        if ($request->category) $query->where('category', $request->category);
        if ($request->date_from) $query->where('income_date', '>=', $request->date_from);
        if ($request->date_to)   $query->where('income_date', '<=', $request->date_to);

        $incomes           = $query->paginate(25);
        $totalIncome       = AccIncome::where('tenant_owner_id', $tid)->where('status', 'received')->sum('amount');
        $thisMonthIncome   = AccIncome::where('tenant_owner_id', $tid)->where('status', 'received')->whereMonth('income_date', now()->month)->whereYear('income_date', now()->year)->sum('amount');
        $recurringCount = AccIncome::where('tenant_owner_id', $tid)->where('is_recurring', true)->count();
        $pendingIncome     = AccIncome::where('tenant_owner_id', $tid)->where('status', 'pending')->sum('amount');
        $categories = AccIncome::where('tenant_owner_id', $tid)->whereNotNull('category')->distinct()->pluck('category')->sort()->values();
        return view('admin.accounts.income', compact('incomes', 'bankAccounts', 'totalIncome', 'thisMonthIncome', 'pendingIncome', 'recurringCount', 'categories'));
    }

    public function incomeStore(Request $request)
    {
        $data = $request->validate([
            'title'               => 'required|string|max:191',
            'category'            => 'nullable|string|max:100',
            'bank_account_id'     => 'nullable|integer',
            'amount'              => 'required|numeric|min:0',
            'tax_amount'          => 'nullable|numeric|min:0',
            'income_date'         => 'required|date',
            'customer_name'       => 'nullable|string|max:191',
            'reference'           => 'nullable|string|max:100',
            'status'              => 'required|in:received,pending,cancelled',
            'is_recurring'        => 'nullable|boolean',
            'recurring_frequency' => 'nullable|string|max:20',
            'notes'               => 'nullable|string',
        ]);
        $data['user_id']         = $this->userId();
        $data['tenant_owner_id'] = $this->tenantId();
        $data['is_recurring']    = $request->boolean('is_recurring');
        AccIncome::create($data);
        return back()->with('success', 'Income recorded.');
    }

    public function incomeUpdate(Request $request, $id)
    {
        $inc = AccIncome::where('tenant_owner_id', $this->tenantId())->findOrFail($id);
        $data = $request->validate([
            'title'               => 'required|string|max:191',
            'category'            => 'nullable|string|max:100',
            'bank_account_id'     => 'nullable|integer',
            'amount'              => 'required|numeric|min:0',
            'tax_amount'          => 'nullable|numeric|min:0',
            'income_date'         => 'required|date',
            'customer_name'       => 'nullable|string|max:191',
            'reference'           => 'nullable|string|max:100',
            'status'              => 'required|in:received,pending,cancelled',
            'is_recurring'        => 'nullable|boolean',
            'recurring_frequency' => 'nullable|string|max:20',
            'notes'               => 'nullable|string',
        ]);
        $data['is_recurring'] = $request->boolean('is_recurring');
        $inc->update($data);
        return back()->with('success', 'Income updated.');
    }

    public function incomeDelete($id)
    {
        AccIncome::where('tenant_owner_id', $this->tenantId())->findOrFail($id)->delete();
        return back()->with('success', 'Income deleted.');
    }

    // ─── Expenses ────────────────────────────────────────────────
    public function expenses(Request $request)
    {
        $tid = $this->tenantId();
        $bankAccounts = AccBankAccount::where('tenant_owner_id', $tid)->orderBy('name')->get();

        $query = AccExpense::where('tenant_owner_id', $tid)->orderByDesc('expense_date');
        if ($request->status)   $query->where('status', $request->status);
        if ($request->category) $query->where('category', $request->category);
        if ($request->date_from) $query->where('expense_date', '>=', $request->date_from);
        if ($request->date_to)   $query->where('expense_date', '<=', $request->date_to);

        $expenses           = $query->paginate(25);
        $totalExpenses      = AccExpense::where('tenant_owner_id', $tid)->where('status', 'paid')->sum('amount');
        $thisMonthExpenses  = AccExpense::where('tenant_owner_id', $tid)->where('status', 'paid')->whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount');
        $pendingExpenses    = AccExpense::where('tenant_owner_id', $tid)->where('status', 'pending')->sum('amount');
        $billableExpenses   = AccExpense::where('tenant_owner_id', $tid)->where('is_billable', true)->sum('amount');

        $categories = AccExpense::where('tenant_owner_id', $tid)->whereNotNull('category')->distinct()->pluck('category')->sort()->values();
        return view('admin.accounts.expenses', compact('expenses', 'bankAccounts', 'totalExpenses', 'thisMonthExpenses', 'pendingExpenses', 'billableExpenses', 'categories'));
    }

    public function expensesStore(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:191',
            'category'        => 'nullable|string|max:100',
            'bank_account_id' => 'nullable|integer',
            'amount'          => 'required|numeric|min:0',
            'tax_amount'      => 'nullable|numeric|min:0',
            'expense_date'    => 'required|date',
            'vendor_name'     => 'nullable|string|max:191',
            'reference'       => 'nullable|string|max:100',
            'status'          => 'required|in:paid,pending,cancelled',
            'is_billable'     => 'nullable|boolean',
            'notes'           => 'nullable|string',
        ]);
        $data['user_id']         = $this->userId();
        $data['tenant_owner_id'] = $this->tenantId();
        $data['is_billable']     = $request->boolean('is_billable');
        AccExpense::create($data);
        return back()->with('success', 'Expense recorded.');
    }

    public function expensesUpdate(Request $request, $id)
    {
        $exp = AccExpense::where('tenant_owner_id', $this->tenantId())->findOrFail($id);
        $data = $request->validate([
            'title'           => 'required|string|max:191',
            'category'        => 'nullable|string|max:100',
            'bank_account_id' => 'nullable|integer',
            'amount'          => 'required|numeric|min:0',
            'tax_amount'      => 'nullable|numeric|min:0',
            'expense_date'    => 'required|date',
            'vendor_name'     => 'nullable|string|max:191',
            'reference'       => 'nullable|string|max:100',
            'status'          => 'required|in:paid,pending,cancelled',
            'is_billable'     => 'nullable|boolean',
            'notes'           => 'nullable|string',
        ]);
        $data['is_billable'] = $request->boolean('is_billable');
        $exp->update($data);
        return back()->with('success', 'Expense updated.');
    }

    public function expensesDelete($id)
    {
        AccExpense::where('tenant_owner_id', $this->tenantId())->findOrFail($id)->delete();
        return back()->with('success', 'Expense deleted.');
    }

    // ─── Chart of Accounts ──────────────────────────────────────
    public function coa()
    {
        $accounts = AccChartOfAccount::where('tenant_owner_id', $this->tenantId())->orderBy('type')->orderBy('code')->orderBy('name')->get();
        $accountsByType = $accounts->groupBy('type'); return view('admin.accounts.chart-of-accounts', compact('accounts', 'accountsByType'));
    }

    public function coaStore(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:191',
            'type'            => 'required|in:asset,liability,equity,income,expense',
            'code'            => 'nullable|string|max:20',
            'sub_type'        => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric',
            'is_active'       => 'nullable|boolean',
            'description'     => 'nullable|string',
        ]);
        $data['user_id']         = $this->userId();
        $data['tenant_owner_id'] = $this->tenantId();
        $data['is_active']       = $request->boolean('is_active', true);
        AccChartOfAccount::create($data);
        return back()->with('success', 'Account added to chart.');
    }

    public function coaUpdate(Request $request, $id)
    {
        $acc = AccChartOfAccount::where('tenant_owner_id', $this->tenantId())->where('is_system', false)->findOrFail($id);
        $data = $request->validate([
            'name'            => 'required|string|max:191',
            'type'            => 'required|in:asset,liability,equity,income,expense',
            'code'            => 'nullable|string|max:20',
            'sub_type'        => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric',
            'is_active'       => 'nullable|boolean',
            'description'     => 'nullable|string',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $acc->update($data);
        return back()->with('success', 'Account updated.');
    }

    public function coaDelete($id)
    {
        AccChartOfAccount::where('tenant_owner_id', $this->tenantId())->where('is_system', false)->findOrFail($id)->delete();
        return back()->with('success', 'Account deleted.');
    }

    // ─── Journal Entries ─────────────────────────────────────────
    public function journal()
    {
        $tid = $this->tenantId();
        $journalEntries = AccJournalEntry::where('tenant_owner_id', $tid)->with('lines')->orderByDesc('entry_date')->paginate(20);
        $chartAccounts  = AccChartOfAccount::where('tenant_owner_id', $tid)->where('is_active', true)->orderBy('type')->orderBy('name')->get();
        return view('admin.accounts.journal-entries', compact('journalEntries', 'chartAccounts'));
    }

    public function journalStore(Request $request)
    {
        $request->validate([
            'entry_date'  => 'required|date',
            'lines'       => 'required|array|min:2',
            'lines.*.chart_account_id' => 'required|integer',
            'lines.*.debit'  => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ]);

        $tid = $this->tenantId();
        $totalDebit  = collect($request->lines)->sum(fn($l) => (float)($l['debit'] ?? 0));
        $totalCredit = collect($request->lines)->sum(fn($l) => (float)($l['credit'] ?? 0));

        $lastNum = AccJournalEntry::where('tenant_owner_id', $tid)->count() + 1;

        $je = AccJournalEntry::create([
            'user_id'         => $this->userId(),
            'tenant_owner_id' => $tid,
            'journal_number'  => 'JE-' . str_pad($lastNum, 4, '0', STR_PAD_LEFT),
            'entry_date'      => $request->entry_date,
            'narration'       => $request->narration,
            'reference'       => $request->reference,
            'status'          => $request->status ?? 'draft',
            'total_debit'     => $totalDebit,
            'total_credit'    => $totalCredit,
        ]);

        foreach ($request->lines as $line) {
            if (empty($line['chart_account_id'])) continue;
            AccJournalLine::create([
                'journal_entry_id'  => $je->id,
                'chart_account_id'  => $line['chart_account_id'],
                'description'       => $line['description'] ?? null,
                'debit'             => (float)($line['debit'] ?? 0),
                'credit'            => (float)($line['credit'] ?? 0),
            ]);
        }

        return back()->with('success', 'Journal entry saved.');
    }

    public function journalDelete($id)
    {
        $je = AccJournalEntry::where('tenant_owner_id', $this->tenantId())->findOrFail($id);
        $je->lines()->delete();
        $je->delete();
        return back()->with('success', 'Journal entry deleted.');
    }

    // ─── Reports ─────────────────────────────────────────────────
    public function reports(Request $request)
    {
        $tid      = $this->tenantId();
        $dateFrom = $request->date_from ?? now()->startOfYear()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');

        // P&L
        $plIncome = AccIncome::where('tenant_owner_id', $tid)->where('status', 'received')
            ->whereBetween('income_date', [$dateFrom, $dateTo])
            ->selectRaw("COALESCE(category, 'Other Income') as cat, SUM(amount) as total")
            ->groupBy('cat')->orderByDesc('total')->pluck('total', 'cat')->map(fn($v) => (float)$v)->toArray();
        $plTotalIncome = array_sum($plIncome);

        $plExpenses = AccExpense::where('tenant_owner_id', $tid)->where('status', 'paid')
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->selectRaw("COALESCE(category, 'Other Expense') as cat, SUM(amount) as total")
            ->groupBy('cat')->orderByDesc('total')->pluck('total', 'cat')->map(fn($v) => (float)$v)->toArray();
        $plTotalExpenses = array_sum($plExpenses);

        // Balance Sheet
        $bsAssets      = ['Cash & Bank' => AccBankAccount::where('tenant_owner_id', $tid)->sum('current_balance'), 'Receivables' => AccIncome::where('tenant_owner_id', $tid)->where('status', 'pending')->sum('amount')];
        $bsTotalAssets = array_sum($bsAssets);
        $bsLiabilities      = ['Payables' => AccExpense::where('tenant_owner_id', $tid)->where('status', 'pending')->sum('amount')];
        $bsTotalLiabilities = array_sum($bsLiabilities);
        $bsEquity      = ['Retained Earnings' => $bsTotalAssets - $bsTotalLiabilities];
        $bsTotalEquity = array_sum($bsEquity);

        // Cash Flow
        $cfIncome   = (float) AccIncome::where('tenant_owner_id', $tid)->where('status', 'received')->whereBetween('income_date', [$dateFrom, $dateTo])->sum('amount');
        $cfExpenses = (float) AccExpense::where('tenant_owner_id', $tid)->where('status', 'paid')->whereBetween('expense_date', [$dateFrom, $dateTo])->sum('amount');

        // Aged Receivables
        $agedReceivables = AccIncome::where('tenant_owner_id', $tid)->where('status', 'pending')
            ->get()->map(function ($inc) {
                $days = now()->diffInDays($inc->income_date, false);
                $absDays = abs($days);
                return [
                    'customer'  => $inc->customer_name ?? 'Unknown',
                    'reference' => $inc->reference ?? $inc->income_number ?? '—',
                    'due_date'  => $inc->income_date->format('d M Y'),
                    '0_30'      => $absDays <= 30 ? $inc->amount : 0,
                    '31_60'     => ($absDays > 30 && $absDays <= 60) ? $inc->amount : 0,
                    '61_90'     => ($absDays > 60 && $absDays <= 90) ? $inc->amount : 0,
                    '90_plus'   => $absDays > 90 ? $inc->amount : 0,
                    'total'     => $inc->amount,
                ];
            })->toArray();

        // Aged Payables
        $agedPayables = AccExpense::where('tenant_owner_id', $tid)->where('status', 'pending')
            ->get()->map(function ($exp) {
                $days = now()->diffInDays($exp->expense_date, false);
                $absDays = abs($days);
                return [
                    'vendor'    => $exp->vendor_name ?? 'Unknown',
                    'reference' => $exp->reference ?? $exp->expense_number ?? '—',
                    'date'      => $exp->expense_date->format('d M Y'),
                    '0_30'      => $absDays <= 30 ? $exp->amount : 0,
                    '31_60'     => ($absDays > 30 && $absDays <= 60) ? $exp->amount : 0,
                    '61_90'     => ($absDays > 60 && $absDays <= 90) ? $exp->amount : 0,
                    '90_plus'   => $absDays > 90 ? $exp->amount : 0,
                    'total'     => $exp->amount,
                ];
            })->toArray();

        // Expense Summary
        $expenseSummary = AccExpense::where('tenant_owner_id', $tid)->where('status', 'paid')
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->selectRaw("COALESCE(category, 'Other') as cat, SUM(amount) as total")
            ->groupBy('cat')->orderByDesc('total')->pluck('total', 'cat')->map(fn($v) => (float)$v)->toArray();

        return view('admin.accounts.reports', compact(
            'dateFrom', 'dateTo',
            'plIncome', 'plTotalIncome', 'plExpenses', 'plTotalExpenses',
            'bsAssets', 'bsTotalAssets', 'bsLiabilities', 'bsTotalLiabilities', 'bsEquity', 'bsTotalEquity',
            'cfIncome', 'cfExpenses',
            'agedReceivables', 'agedPayables', 'expenseSummary'
        ));
    }
}
