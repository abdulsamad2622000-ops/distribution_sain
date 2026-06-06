<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product, Supplier};

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('supplier')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->low_stock, fn($q) => $q->whereColumn('stock_qty', '<=', 'low_stock_alert'))
            ->paginate(20);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.form', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_qty'      => 'required|integer|min:0',
            'unit'           => 'required|string',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.edit', $product);
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.form', compact('product', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_qty'      => 'required|integer|min:0',
            'unit'           => 'required|string',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted!');
    }

    public function getPrice(Product $product)
    {
        return response()->json([
            'purchase_price' => $product->purchase_price,
            'selling_price'  => $product->selling_price,
            'stock_qty'      => $product->stock_qty,
            'unit'           => $product->unit,
        ]);
    }
}