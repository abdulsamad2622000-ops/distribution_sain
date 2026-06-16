@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Purchase Orders</span>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> New Purchase Order
        </a>
    </div>
    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="supplier_id" class="form-select form-select-sm">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="partial"  {{ request('status') == 'partial'  ? 'selected' : '' }}>Partial</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled"{{ request('status') == 'cancelled'? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary btn-sm w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Expected Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->po_number }}</strong></td>
                        <td>{{ $order->supplier->name }}</td>
                        <td>{{ $order->order_date->format('d M Y') }}</td>
                        <td>{{ $order->expected_date ? $order->expected_date->format('d M Y') : '-' }}</td>
                        <td>PKR {{ number_format($order->total_amount) }}</td>
                        <td>
                            @php
                                $colors = [
                                    'draft'     => 'secondary',
                                    'approved'  => 'primary',
                                    'partial'   => 'warning',
                                    'received'  => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('purchase-orders.show', $order) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($order->status === 'draft')
<a href="{{ route('purchase-orders.edit', $order) }}"
    class="btn btn-sm btn-outline-warning">
    <i class="bi bi-pencil"></i>
</a>
@endif
                            @if($order->status === 'draft')
                            <form action="{{ route('purchase-orders.destroy', $order) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this PO?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No purchase orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection