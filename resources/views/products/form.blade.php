@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'New Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ isset($product) ? 'Edit Product' : 'New Product' }}</span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($product)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">SKU</label>
                            <input type="text" name="sku" class="form-control"
                                value="{{ old('sku', $product->sku ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Unit</label>
                            <input type="text" name="unit" class="form-control"
                                value="{{ old('unit', $product->unit ?? '') }}"
                                placeholder="e.g. Pcs, Kg, Box">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">-- Select Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Purchase Price <span class="text-danger">*</span></label>
                            <input type="number" name="purchase_price"
                                class="form-control @error('purchase_price') is-invalid @enderror"
                                value="{{ old('purchase_price', $product->purchase_price ?? '') }}"
                                step="0.01" min="0" required>
                            @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" name="selling_price"
                                class="form-control @error('selling_price') is-invalid @enderror"
                                value="{{ old('selling_price', $product->selling_price ?? '') }}"
                                step="0.01" min="0" required>
                            @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Stock Quantity</label>
                            <input type="number" name="stock_qty" class="form-control"
                                value="{{ old('stock_qty', $product->stock_qty ?? 0) }}"
                                min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Low Stock Alert</label>
                            <input type="number" name="low_stock_alert" class="form-control"
                                value="{{ old('low_stock_alert', $product->low_stock_alert ?? 10) }}"
                                min="0">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            {{ isset($product) ? 'Update Product' : 'Create Product' }}
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection