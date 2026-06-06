 
@extends('layouts.app')

@section('title', 'Sales Invoices')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>All Invoices</span>
        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> New Invoice
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><strong>{{ $sale->invoice_no }}</strong></td>
                        <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                        <td>PKR {{ number_format($sale->net_amount) }}</td>
                        <td>PKR {{ number_format($sale->paid_amount) }}</td>
                        <td class="text-danger">PKR {{ number_format($sale->due_amount) }}</td>
                        <td>{{ ucfirst($sale->payment_type) }}</td>
                        <td>
                            <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('sales.print', $sale) }}" target="_blank" class="btn btn-sm btn-secondary">
                                <i class="bi bi-printer"></i>
                            </a>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">No invoices found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
    <div class="card-footer">{{ $sales->links() }}</div>
    @endif
</div>
@endsection