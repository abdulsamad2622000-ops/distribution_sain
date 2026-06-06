<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Recovery, Sale, Customer};

class RecoveryController extends Controller
{
    public function index(Request $request)
    {
        $recoveries = Recovery::with('customer', 'sale', 'user')
            ->when($request->customer_id, fn($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->from, fn($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->latest('payment_date')->paginate(20);

        $customers = Customer::orderBy('name')->get();
        return view('recoveries.index', compact('recoveries', 'customers'));
    }

    public function pending()
    {
        $customers = Customer::where('balance', '>', 0)
            ->with(['sales' => function ($q) {
                $q->where('status', '!=', 'paid')->with('items.product');
            }])->get();

        return view('recoveries.pending', compact('customers'));
    }

    public function create(Sale $sale)
    {
        $sale->load('customer');
        return view('recoveries.create', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id'        => 'required|exists:sales,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'payment_date'   => 'required|date',
        ]);

        $sale = Sale::findOrFail($request->sale_id);

        if ($request->amount > $sale->due_amount) {
            return back()->withErrors(['amount' => 'Amount exceeds due amount of Rs. ' . number_format($sale->due_amount, 2)]);
        }

        DB::transaction(function () use ($request, $sale) {
            Recovery::create([
                'sale_id'        => $sale->id,
                'customer_id'    => $sale->customer_id,
                'user_id'        => auth()->id(),
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_no'   => $request->reference_no,
                'payment_date'   => $request->payment_date,
                'notes'          => $request->notes,
            ]);

            $new_paid = $sale->paid_amount + $request->amount;
            $new_due  = $sale->due_amount  - $request->amount;

            $sale->update([
                'paid_amount' => $new_paid,
                'due_amount'  => $new_due,
                'status'      => $new_due <= 0 ? 'paid' : 'partial',
            ]);

            Customer::find($sale->customer_id)->decrement('balance', $request->amount);
        });

        return redirect()->route('recoveries.pending')->with('success', 'Payment recorded successfully!');
    }
}