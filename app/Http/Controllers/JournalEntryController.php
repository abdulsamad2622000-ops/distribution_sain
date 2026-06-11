<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::orderBy('date', 'desc')->paginate(20);
        return view('journal_entries.index', compact('entries'));
    }

    public function create()
    {
        $accounts = Account::orderBy('code')->get();
        return view('journal_entries.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'          => 'required|date',
            'description'   => 'required|string|max:500',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.type'       => 'required|in:debit,credit',
            'lines.*.amount'     => 'required|numeric|min:0.01',
        ]);

        // Debit == Credit check
        $totalDebit  = collect($request->lines)
                        ->where('type', 'debit')->sum('amount');
        $totalCredit = collect($request->lines)
                        ->where('type', 'credit')->sum('amount');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()->withInput()
                         ->withErrors(['lines' => 'Total Debit must equal Total Credit.']);
        }

        DB::transaction(function () use ($request) {
            $entry = JournalEntry::create([
                'reference'   => 'JV-' . date('YmdHis'),
                'date'        => $request->date,
                'description' => $request->description,
                'status'      => 'posted',
            ]);

            foreach ($request->lines as $line) {
                JournalLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id'       => $line['account_id'],
                    'type'             => $line['type'],
                    'amount'           => $line['amount'],
                    'description'      => $line['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('journal-entries.index')
                         ->with('success', 'Journal Entry posted successfully.');
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('lines.account');
        return view('journal_entries.show', compact('journalEntry'));
    }

    public function destroy(JournalEntry $journalEntry)
    {
        $journalEntry->delete();
        return redirect()->route('journal-entries.index')
                         ->with('success', 'Journal Entry deleted.');
    }
}