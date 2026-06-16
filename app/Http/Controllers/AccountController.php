<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('code')->paginate(20);
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.form', ['account' => new Account()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:accounts,code',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        Account::create($request->all());

        return redirect()->route('accounts.index')
                         ->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load('journalLines.journalEntry');
        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        return view('accounts.form', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:accounts,code,' . $account->id,
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        $account->update($request->all());

        return redirect()->route('accounts.index')
                         ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')
                         ->with('success', 'Account deleted.');
    }

    public function trialBalance()
    {
        $accounts = Account::with('journalLines')->get()->map(function ($account) {
            $debit  = $account->journalLines->where('type', 'debit')->sum('amount');
            $credit = $account->journalLines->where('type', 'credit')->sum('amount');
            return [
                'code'    => $account->code,
                'name'    => $account->name,
                'type'    => $account->type,
                'debit'   => $debit,
                'credit'  => $credit,
                'balance' => $debit - $credit,
            ];
        })->sortBy('code')->values();

        $totalDebit  = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');

        return view('accounts.trial_balance', compact('accounts', 'totalDebit', 'totalCredit'));
    }

    public function balanceSheet()
    {
        $accounts = Account::with('journalLines')->get();
        $asOfDate = now()->format('d M Y');

        $assets = $accounts->where('type', 'asset')->map(function ($account) {
            $debit  = $account->journalLines->where('type', 'debit')->sum('amount');
            $credit = $account->journalLines->where('type', 'credit')->sum('amount');
            return [
                'name'    => $account->name,
                'code'    => $account->code,
                'balance' => $debit - $credit,
            ];
        })->values();

        $liabilities = $accounts->where('type', 'liability')->map(function ($account) {
            $debit  = $account->journalLines->where('type', 'debit')->sum('amount');
            $credit = $account->journalLines->where('type', 'credit')->sum('amount');
            return [
                'name'    => $account->name,
                'code'    => $account->code,
                'balance' => $credit - $debit,
            ];
        })->values();

        $equity = $accounts->where('type', 'equity')->map(function ($account) {
            $debit  = $account->journalLines->where('type', 'debit')->sum('amount');
            $credit = $account->journalLines->where('type', 'credit')->sum('amount');
            return [
                'name'    => $account->name,
                'code'    => $account->code,
                'balance' => $credit - $debit,
            ];
        })->values();

        $revenue = $accounts->where('type', 'revenue')->sum(function ($account) {
            return $account->journalLines->where('type', 'credit')->sum('amount')
                 - $account->journalLines->where('type', 'debit')->sum('amount');
        });

        $expense = $accounts->where('type', 'expense')->sum(function ($account) {
            return $account->journalLines->where('type', 'debit')->sum('amount')
                 - $account->journalLines->where('type', 'credit')->sum('amount');
        });

        $netProfit = $revenue - $expense;

        $totalAssets      = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity      = $equity->sum('balance') + $netProfit;

        return view('accounts.balance_sheet', compact(
            'assets', 'liabilities', 'equity', 'netProfit',
            'totalAssets', 'totalLiabilities', 'totalEquity', 'asOfDate'
        ));
    }

    public function incomeStatement()
    {
        $accounts = Account::with('journalLines')->get();

        $revenues = $accounts->where('type', 'revenue')->map(function ($account) {
            $debit  = $account->journalLines->where('type', 'debit')->sum('amount');
            $credit = $account->journalLines->where('type', 'credit')->sum('amount');
            return [
                'name'    => $account->name,
                'code'    => $account->code,
                'balance' => $credit - $debit,
            ];
        })->values();

        $expenses = $accounts->where('type', 'expense')->map(function ($account) {
            $debit  = $account->journalLines->where('type', 'debit')->sum('amount');
            $credit = $account->journalLines->where('type', 'credit')->sum('amount');
            return [
                'name'    => $account->name,
                'code'    => $account->code,
                'balance' => $debit - $credit,
            ];
        })->values();

        $totalRevenue  = $revenues->sum('balance');
        $totalExpenses = $expenses->sum('balance');
        $netIncome     = $totalRevenue - $totalExpenses;

        return view('accounts.income_statement', compact(
            'revenues', 'expenses', 'totalRevenue', 'totalExpenses', 'netIncome'
        ));
    }
}