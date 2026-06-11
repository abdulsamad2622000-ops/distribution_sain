@extends('layouts.app')

@section('title', $warehouse->name)

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $warehouse->name }}</span>
                <a href="{{ route('warehouses.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted">Code</td>
                        <td><span class="badge bg-secondary">{{ $warehouse->code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">City</td>
                        <td>{{ $warehouse->city ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td>{{ $warehouse->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Incharge</td>
                        <td>{{ $warehouse->incharge_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $warehouse->incharge_phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'danger' }}">
                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                </table>
                <a href="{{ route('warehouses.edit', $warehouse) }}"
                    class="btn btn-warning btn-sm w-100">
                    <i class="bi bi-pencil"></i> Edit Warehouse
                </a>
            </div>
        </div>

        {{-- Stock Adjustment --}}
        <div class="card">
            <div class="card-header">Stock Adjustment</div>
            <div class="card-body">
                <form action="{{ route('warehouses.adjust', $warehouse) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Product</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">-- Select Product --</option>
                            @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                            <option value="adjustment">Adjustment</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="number" name="qty" class="form-control form-control-sm"
                            min="0.01" step="0.01" placeholder="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason</label>
                        <input type="text" name="notes" class="form-control form-control-sm"
                            placeholder="e.g. Damage, Audit correction">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg"></i> Save Adjustment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Stock Summary --}}
        <div class="card mb-3">
            <div class="card-header">Current Stock in this Warehouse</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stock_summary as $item)
                            <tr>
                                <td class="ps-3">{{ $item->product->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->current_stock > 0 ? 'success' : 'danger' }}">
                                        {{ $item->current_stock }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No stock movements yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Movement History --}}
        <div class="card">
            <div class="card-header">Stock Movement History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Before</th>
                                <th>After</th>
                                <th>Notes</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $mov)
                            <tr>
                                <td class="ps-3">{{ $mov->product->name }}</td>
                                <td>
                                    @php
                                        $colors = ['in' => 'success', 'out' => 'danger', 'transfer' => 'primary', 'adjustment' => 'warning'];
                                    @endphp
                                    <span class="badge bg-{{ $colors[$mov->type] ?? 'secondary' }}">
                                        {{ ucfirst($mov->type) }}
                                    </span>
                                </td>
                                <td>{{ $mov->qty }}</td>
                                <td>{{ $mov->before_qty }}</td>
                                <td>{{ $mov->after_qty }}</td>
                                <td>{{ $mov->notes ?? '-' }}</td>
                                <td>{{ $mov->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No movements yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection