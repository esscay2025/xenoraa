<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'category', 'approver']);

        // Admin sees all; staff sees only their own
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20);
        $categories = ExpenseCategory::all();

        // Summary stats
        $totalPersonal = Expense::when(!auth()->user()->isAdmin(), fn($q) => $q->where('user_id', auth()->id()))
            ->where('type', 'personal')->sum('amount');
        $totalBusiness = Expense::when(!auth()->user()->isAdmin(), fn($q) => $q->where('user_id', auth()->id()))
            ->where('type', 'business')->sum('amount');

        return view('admin.expenses.index', compact('expenses', 'categories', 'totalPersonal', 'totalBusiness'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('admin.expenses.create', compact('categories'));
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'category_id' => ['required', 'exists:expense_categories,id'],
            'type' => ['required', 'in:personal,business'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'description' => $request->description,
            'receipt_path' => $receiptPath,
            'type' => $request->type,
            'status' => auth()->user()->isAdmin() ? 'approved' : 'pending',
            'approved_by' => auth()->user()->isAdmin() ? auth()->id() : null,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        // Staff can only edit their own expenses
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = ExpenseCategory::all();
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense.
     */
    public function update(Request $request, Expense $expense)
    {
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'category_id' => ['required', 'exists:expense_categories,id'],
            'type' => ['required', 'in:personal,business'],
        ]);

        $expense->update($request->only(['title', 'amount', 'expense_date', 'category_id', 'description', 'type']));

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(Expense $expense)
    {
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403);
        }

        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Approve an expense (admin only).
     */
    public function approve(Expense $expense)
    {
        $expense->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return back()->with('success', 'Expense approved.');
    }

    /**
     * Reject an expense (admin only).
     */
    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);
        return back()->with('success', 'Expense rejected.');
    }
}
