<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{PurchaseOrder, PurchaseOrderItem, Supplier, Product};

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = PurchaseOrder::with('supplier', 'user')
            ->when($request->supplier_id, fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from, fn($q) => $q->whereDate('order_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('order_date', '<=', $request->to))
            ->latest()->paginate(20);

        $suppliers = Supplier::orderBy('name')->get();
        return view('purchase_orders.index', compact('orders', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        $po_number = PurchaseOrder::generatePoNumber();
        return view('purchase_orders.create', compact('suppliers', 'products', 'po_number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'order_date'           => 'required|date',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.qty'          => 'required|numeric|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total      = 0;
            $items_data = [];

            foreach ($request->items as $item) {
                $line_total  = $item['qty'] * $item['unit_price'];
                $total      += $line_total;
                $items_data[] = [
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'],
                    'unit_price'  => $item['unit_price'],
                    'total_price' => $line_total,
                    'received_qty'=> 0,
                ];
            }

            $po = PurchaseOrder::create([
                'po_number'     => PurchaseOrder::generatePoNumber(),
                'supplier_id'   => $request->supplier_id,
                'user_id'       => auth()->id(),
                'status'        => 'draft',
                'order_date'    => $request->order_date,
                'expected_date' => $request->expected_date,
                'total_amount'  => $total,
                'notes'         => $request->notes,
            ]);

            $po->items()->createMany($items_data);
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order created successfully!');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'user', 'approvedBy', 'items.product', 'grns.items.product');
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Only draft orders can be approved.');
        }

        $purchaseOrder->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Purchase Order approved successfully!');
    }

    public function cancel(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'approved'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);
        return back()->with('success', 'Purchase Order cancelled.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Only draft orders can be deleted.');
        }

        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order deleted.');
    }


    public function edit(PurchaseOrder $purchaseOrder)
{
    if ($purchaseOrder->status !== 'draft') {
        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('error', 'Only draft orders can be edited.');
    }

    $suppliers = Supplier::orderBy('name')->get();
    $products  = Product::orderBy('name')->get();
    $purchaseOrder->load('items.product');
    return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
}

public function update(Request $request, PurchaseOrder $purchaseOrder)
{
    if ($purchaseOrder->status !== 'draft') {
        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('error', 'Only draft orders can be edited.');
    }

    $request->validate([
        'supplier_id'          => 'required|exists:suppliers,id',
        'order_date'           => 'required|date',
        'items'                => 'required|array|min:1',
        'items.*.product_id'   => 'required|exists:products,id',
        'items.*.qty'          => 'required|numeric|min:1',
        'items.*.unit_price'   => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $purchaseOrder) {
        $purchaseOrder->items()->delete();

        $total      = 0;
        $items_data = [];

        foreach ($request->items as $item) {
            $line_total   = $item['qty'] * $item['unit_price'];
            $total       += $line_total;
            $items_data[] = [
                'product_id'   => $item['product_id'],
                'qty'          => $item['qty'],
                'unit_price'   => $item['unit_price'],
                'total_price'  => $line_total,
                'received_qty' => 0,
            ];
        }

        $purchaseOrder->update([
            'supplier_id'   => $request->supplier_id,
            'order_date'    => $request->order_date,
            'expected_date' => $request->expected_date,
            'total_amount'  => $total,
            'notes'         => $request->notes,
        ]);

        $purchaseOrder->items()->createMany($items_data);
    });

    return redirect()->route('purchase-orders.show', $purchaseOrder)
        ->with('success', 'Purchase Order updated successfully!');
}
}