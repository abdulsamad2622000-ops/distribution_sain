 
@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Product — {{ $product->name }}</div>
    <div class="card-body">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unit</label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Purchase Price <span class="text-danger">*</span></label>
                    <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror"
                        value="{{ old('purchase_price', $product->purchase_price) }}" step="0.01">
                    @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Selling Price <span class="text-danger">*</span></label>
                    <input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
                        value="{{ old('selling_price', $product->selling_price) }}" step="0.01">
                    @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock Quantity</label>
                    <input type="number" name="stock_qty" class="form-control" value="{{ old('stock_qty', $product->stock_qty) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Low Stock Alert</label>
                    <input type="number" name="low_stock_alert" class="form-control" value="{{ old('low_stock_alert', $product->low_stock_alert) }}" min="0">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection