 
@extends('layouts.app')

@section('title', 'Customer Ledger')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-person me-2"></i>{{ $customer->name }}</span>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Phone</td><td>{{ $customer->phone ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Area</td><td>{{ $customer->area ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Address</td><td>{{ $customer->address ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Total Sales</td><td>PKR {{ number_format($customer->totalSales()) }}</td></tr>
                    <tr><td class="text-muted">Total Recovered</td><td class="text-success">PKR {{ number_format($customer->totalRecovered()) }}</td></tr>
                    <tr><td class="text-muted">Outstanding</td><td class="text-danger fw-bold">PKR {{ number_format($customer->outstanding()) }}</td></tr>
                </table>
                <a href="{{ route('recoveries.create') }}?customer_id={{ $customer->id }}" class="btn btn-success w-100 mt-2">
                    <i class="bi bi-cash-coin"></i> Record Payment
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Sales -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Invoices</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Invoice #</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none fw-bold">
                                        {{ $sale->invoice_no }}
                                    </a>
                                </td>
                                <td>PKR {{ number_format($sale->net_amount) }}</td>
                                <td>PKR {{ number_format($sale->paid_amount) }}</td>
                                <td class="text-danger">PKR {{ number_format($sale->due_amount) }}</td>
                                <td>
                                    <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No invoices</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recoveries -->
        <div class="card">
            <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Payment History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Invoice #</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recoveries as $recovery)
                            <tr>
                                <td class="ps-3">{{ $recovery->sale->invoice_no ?? 'N/A' }}</td>
                                <td class="text-success fw-bold">PKR {{ number_format($recovery->amount) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $recovery->payment_method)) }}</td>
                                <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No payments</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection