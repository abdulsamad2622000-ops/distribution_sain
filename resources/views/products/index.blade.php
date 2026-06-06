 
@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam me-2"></i>Products</span>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Supplier</th>
                        <th>Purchase Price</th>
                        <th>Selling Price</th>
                        <th>Stock</th>
                        <th>Unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                        <td>PKR {{ number_format($product->purchase_price) }}</td>
                        <td>PKR {{ number_format($product->selling_price) }}</td>
                        <td>
                            <span class="badge bg-{{ $product->stock_qty <= $product->low_stock_alert ? 'danger' : 'success' }}">
                                {{ $product->stock_qty }}
                            </span>
                        </td>
                        <td>{{ $product->unit }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No products found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="card-footer">{{ $products->links() }}</div>
    @endif
</div>
@endsection