<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::with('user')
            ->when($request->from, fn($q) => $q->whereDate('expense_date', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->whereDate('expense_date', '<=', $request->to))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest('expense_date')->paginate(20);

        $total      = $expenses->sum('amount');
        $categories = Expense::distinct()->pluck('category');

        return view('expenses.index', compact('expenses', 'total', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category'     => 'required|string',
        ]);

        Expense::create([
            'title'        => $request->title,
            'category'     => $request->category,
            'amount'       => $request->amount,
            'user_id'      => auth()->id(),
            'expense_date' => $request->expense_date,
            'notes'        => $request->notes,
        ]);

        return back()->with('success', 'Expense added!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Expense deleted!');
    }
}