 
@extends('layouts.app')

@section('title', 'Invoice Detail')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-receipt me-2"></i>{{ $sale->invoice_no }}</span>
                <div>
                    <a href="{{ route('sales.print', $sale) }}" target="_blank" class="btn btn-secondary btn-sm">
                        <i class="bi bi-printer"></i>
                    </a>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Customer</td><td><strong>{{ $sale->customer->name }}</strong></td></tr>
                    <tr><td class="text-muted">Date</td><td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Type</td><td>{{ ucfirst($sale->payment_type) }}</td></tr>
                    <tr><td class="text-muted">Total</td><td>PKR {{ number_format($sale->total_amount) }}</td></tr>
                    <tr><td class="text-muted">Discount</td><td>-PKR {{ number_format($sale->discount) }}</td></tr>
                    <tr><td class="text-muted">Net Amount</td><td><strong>PKR {{ number_format($sale->net_amount) }}</strong></td></tr>
                    <tr><td class="text-muted">Paid</td><td class="text-success">PKR {{ number_format($sale->paid_amount) }}</td></tr>
                    <tr><td class="text-muted">Due</td><td class="text-danger fw-bold">PKR {{ number_format($sale->due_amount) }}</td></tr>
                    <tr><td class="text-muted">Status</td>
                        <td><span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }}">
                            {{ ucfirst($sale->status) }}
                        </span></td>
                    </tr>
                </table>

                @if($sale->due_amount > 0)
                <a href="{{ route('recoveries.create') }}?sale_id={{ $sale->id }}&customer_id={{ $sale->customer_id }}"
                    class="btn btn-success w-100 mt-2">
                    <i class="bi bi-cash-coin"></i> Record Payment
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-list me-2"></i>Invoice Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Product</th>
                                <th>Sell Rate</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>PKR {{ number_format($item->unit_price) }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>PKR {{ number_format($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="4" class="text-end">Subtotal:</td><td>PKR {{ number_format($sale->total_amount) }}</td></tr>
                            <tr><td colspan="4" class="text-end">Discount:</td><td>-PKR {{ number_format($sale->discount) }}</td></tr>
                            <tr class="fw-bold"><td colspan="4" class="text-end">Net Total:</td><td>PKR {{ number_format($sale->net_amount) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Payment History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->recoveries as $recovery)
                            <tr>
                                <td class="ps-3 text-success fw-bold">PKR {{ number_format($recovery->amount) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $recovery->payment_method)) }}</td>
                                <td>{{ $recovery->reference_no ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No payments yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection