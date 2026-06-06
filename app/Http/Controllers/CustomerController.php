<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Customer, Recovery};

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::withSum('sales', 'net_amount')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->outstanding, fn($q) => $q->where('balance', '>', 0))
            ->paginate(20);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer added!');
    }

    public function show(Customer $customer)
    {
        $sales      = $customer->sales()->with('items.product')->latest()->paginate(10);
        $recoveries = $customer->recoveries()->latest()->paginate(10);
        return view('customers.show', compact('customer', 'sales', 'recoveries'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($request->only('name', 'phone', 'address', 'area'));
        return redirect()->route('customers.show', $customer)->with('success', 'Customer updated!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted!');
    }

    public function ledger(Customer $customer)
    {
        $entries = collect();

        $customer->sales()->get()->each(function ($sale) use (&$entries) {
            $entries->push([
                'date'        => $sale->sale_date,
                'type'        => 'sale',
                'description' => 'Invoice #' . $sale->invoice_no,
                'debit'       => $sale->net_amount,
                'credit'      => 0,
                'ref_id'      => $sale->id,
            ]);
        });

        $customer->recoveries()->get()->each(function ($rec) use (&$entries) {
            $entries->push([
                'date'        => $rec->payment_date,
                'type'        => 'recovery',
                'description' => 'Payment - ' . ucfirst($rec->payment_method),
                'debit'       => 0,
                'credit'      => $rec->amount,
                'ref_id'      => $rec->id,
            ]);
        });

        $entries = $entries->sortBy('date')->values();

        $balance = 0;
        $entries = $entries->map(function ($entry) use (&$balance) {
            $balance += $entry['debit'] - $entry['credit'];
            $entry['balance'] = $balance;
            return $entry;
        });

        return view('customers.ledger', compact('customer', 'entries'));
    }
}