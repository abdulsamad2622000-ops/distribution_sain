<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Sale, SaleItem, Customer, Product, Recovery};

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::with('customer', 'user')
            ->when($request->customer_id, fn($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from, fn($q) => $q->whereDate('sale_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('sale_date', '<=', $request->to))
            ->latest('sale_date')->paginate(20);

        $customers = Customer::orderBy('name')->get();
        return view('sales.index', compact('sales', 'customers'));
    }

    public function create()
    {
        $customers  = Customer::orderBy('name')->get();
        $products   = Product::where('stock_qty', '>', 0)->orderBy('name')->get();
        $invoice_no = Sale::generateInvoiceNo();
        return view('sales.create', compact('customers', 'products', 'invoice_no'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'          => 'required|exists:customers,id',
            'sale_date'            => 'required|date',
            'payment_type'         => 'required',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total      = 0;
            $items_data = [];

            foreach ($request->items as $item) {
                $product    = Product::findOrFail($item['product_id']);
                $line_total = $item['qty'] * $item['unit_price'];
                $total     += $line_total;

                $items_data[] = [
                    'product_id'     => $item['product_id'],
                    'qty'            => $item['qty'],
                    'unit_price'     => $item['unit_price'],
                    'purchase_price' => $product->purchase_price,
                    'total_price'    => $line_total,
                ];

                $product->decrement('stock_qty', $item['qty']);
            }

            $discount   = $request->discount ?? 0;
            $net_amount = $total - $discount;
            $paid       = $request->paid_amount ?? 0;
            $due        = $net_amount - $paid;

            $sale = Sale::create([
                'invoice_no'   => Sale::generateInvoiceNo(),
                'customer_id'  => $request->customer_id,
                'user_id'      => auth()->id(),
                'total_amount' => $total,
                'discount'     => $discount,
                'net_amount'   => $net_amount,
                'paid_amount'  => $paid,
                'due_amount'   => $due,
                'payment_type' => $request->payment_type,
                'status'       => $due <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid'),
                'sale_date'    => $request->sale_date,
                'notes'        => $request->notes,
            ]);

            $sale->items()->createMany($items_data);

            if ($due > 0) {
                Customer::find($request->customer_id)->increment('balance', $due);
            }

            if ($paid > 0) {
                Recovery::create([
                    'sale_id'        => $sale->id,
                    'customer_id'    => $request->customer_id,
                    'user_id'        => auth()->id(),
                    'amount'         => $paid,
                    'payment_method' => $request->payment_method ?? 'cash',
                    'payment_date'   => $request->sale_date,
                    'notes'          => 'Initial payment on sale',
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load('customer', 'user', 'items.product', 'recoveries.user');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if ($sale->status === 'paid') {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Paid sales cannot be edited.');
        }

        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        $sale->load('items.product');
        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->status === 'paid') {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Paid sales cannot be edited.');
        }

        $request->validate([
            'customer_id'          => 'required|exists:customers,id',
            'sale_date'            => 'required|date',
            'payment_type'         => 'required',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $sale) {
            foreach ($sale->items as $old_item) {
                Product::find($old_item->product_id)?->increment('stock_qty', $old_item->qty);
            }

            if ($sale->due_amount > 0) {
                Customer::find($sale->customer_id)?->decrement('balance', $sale->due_amount);
            }

            $sale->items()->delete();

            $total      = 0;
            $items_data = [];

            foreach ($request->items as $item) {
                $product    = Product::findOrFail($item['product_id']);
                $line_total = $item['qty'] * $item['unit_price'];
                $total     += $line_total;

                $items_data[] = [
                    'product_id'     => $item['product_id'],
                    'qty'            => $item['qty'],
                    'unit_price'     => $item['unit_price'],
                    'purchase_price' => $product->purchase_price,
                    'total_price'    => $line_total,
                ];

                $product->decrement('stock_qty', $item['qty']);
            }

            $discount   = $request->discount ?? 0;
            $net_amount = $total - $discount;
            $paid       = $sale->paid_amount;
            $due        = $net_amount - $paid;

            $sale->update([
                'customer_id'  => $request->customer_id,
                'total_amount' => $total,
                'discount'     => $discount,
                'net_amount'   => $net_amount,
                'due_amount'   => $due,
                'payment_type' => $request->payment_type,
                'status'       => $due <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid'),
                'sale_date'    => $request->sale_date,
                'notes'        => $request->notes,
            ]);

            $sale->items()->createMany($items_data);

            if ($due > 0) {
                Customer::find($request->customer_id)?->increment('balance', $due);
            }
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                Product::find($item->product_id)?->increment('stock_qty', $item->qty);
            }
            if ($sale->due_amount > 0) {
                Customer::find($sale->customer_id)?->decrement('balance', $sale->due_amount);
            }
            $sale->items()->delete();
            $sale->recoveries()->delete();
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale deleted!');
    }

    public function invoice(Sale $sale)
    {
        $sale->load('customer', 'items.product', 'user');
        return view('sales.invoice', compact('sale'));
    }
}