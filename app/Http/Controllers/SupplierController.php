<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(20);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier added!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted!');
    }

    public function show(Supplier $supplier)
    {
        return redirect()->route('suppliers.index');
    }
}