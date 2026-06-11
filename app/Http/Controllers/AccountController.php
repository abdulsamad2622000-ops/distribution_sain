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
}