<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\Product;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('stockMovements')->latest()->get();
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:warehouses,code|alpha_num',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created successfully!');
    }

    public function show(Warehouse $warehouse)
    {
        $movements = StockMovement::with('product', 'user')
            ->where('warehouse_id', $warehouse->id)
            ->latest()->paginate(20);

        $stock_summary = StockMovement::where('warehouse_id', $warehouse->id)
            ->selectRaw('product_id, SUM(CASE WHEN type="in" THEN qty WHEN type="out" THEN -qty ELSE 0 END) as current_stock')
            ->groupBy('product_id')
            ->with('product')
            ->get();

        return view('warehouses.show', compact('warehouse', 'movements', 'stock_summary'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.form', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|alpha_num|unique:warehouses,code,' . $warehouse->id,
        ]);

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated successfully!');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse deleted.');
    }

    public function adjust(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out,adjustment',
            'qty'        => 'required|numeric|min:0.01',
            'notes'      => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        StockMovement::create([
            'product_id'   => $request->product_id,
            'warehouse_id' => $warehouse->id,
            'type'         => $request->type,
            'qty'          => $request->qty,
            'before_qty'   => $product->stock_qty,
            'after_qty'    => $product->stock_qty + ($request->type === 'out' ? -$request->qty : $request->qty),
            'notes'        => $request->notes,
            'user_id'      => auth()->id(),
        ]);

        if ($request->type === 'out') {
            $product->decrement('stock_qty', $request->qty);
        } else {
            $product->increment('stock_qty', $request->qty);
        }

        return back()->with('success', 'Stock adjustment saved successfully!');
    }
}