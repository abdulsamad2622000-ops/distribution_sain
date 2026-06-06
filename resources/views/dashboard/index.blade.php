 
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Stats Row 1 -->
<div class="row g-3 mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#00b4d8;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-receipt text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Today Sales</div>
                    <div class="fw-bold fs-5">PKR {{ number_format($today_sales) }}</div>
                    <div style="font-size:11px;color:#6c757d;">{{ $today_sales_count }} invoices</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#27ae60;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-cash-coin text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Today Recovery</div>
                    <div class="fw-bold fs-5">PKR {{ number_format($today_recovery) }}</div>
                    <div style="font-size:11px;color:#6c757d;">Monthly: PKR {{ number_format($monthly_recovery) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e74c3c;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-exclamation-circle text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Total Outstanding</div>
                    <div class="fw-bold fs-5 text-danger">PKR {{ number_format($total_outstanding) }}</div>
                    <div style="font-size:11px;color:#6c757d;">Due amount</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#f39c12;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-graph-up text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Monthly Sales</div>
                    <div class="fw-bold fs-5">PKR {{ number_format($monthly_sales) }}</div>
                    <div style="font-size:11px;color:#6c757d;">{{ now()->format('M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 2 -->
<div class="row g-3 mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#8e44ad;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-people text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Total Customers</div>
                    <div class="fw-bold fs-5">{{ $total_customers }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e74c3c;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-seam text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Low Stock Items</div>
                    <div class="fw-bold fs-5 text-danger">{{ $low_stock }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e74c3c;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-wallet2 text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Monthly Expenses</div>
                    <div class="fw-bold fs-5">PKR {{ number_format($monthly_expenses) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#27ae60;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Net Profit</div>
                    @php $profit = $monthly_sales - $monthly_expenses; @endphp
                    <div class="fw-bold fs-5 text-{{ $profit >= 0 ? 'success' : 'danger' }}">
                        PKR {{ number_format($profit) }}
                    </div>
                    <div style="font-size:11px;color:#6c757d;">This month</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables -->
<div class="row g-3">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Recent Invoices</span>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Invoice #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_sales as $sale)
                            <tr>
                                <td class="ps-3"><a href="{{ route('sales.show', $sale) }}" class="text-decoration-none fw-bold">{{ $sale->invoice_no }}</a></td>
                                <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                <td>PKR {{ number_format($sale->net_amount) }}</td>
                                <td class="text-danger">PKR {{ number_format($sale->due_amount) }}</td>
                                <td>
                                    <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No sales today</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-coin me-2"></i>Recent Recoveries</span>
                <a href="{{ route('recoveries.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_recoveries as $recovery)
                            <tr>
                                <td class="ps-3">{{ $recovery->customer->name ?? 'N/A' }}</td>
                                <td class="text-success fw-bold">PKR {{ number_format($recovery->amount) }}</td>
                                <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">No recoveries</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Low Stock</span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Stock</th>
                                <th>Alert</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($low_stock_items as $product)
                            <tr>
                                <td class="ps-3">{{ $product->name }}</td>
                                <td><span class="badge bg-danger">{{ $product->stock_qty }}</span></td>
                                <td>{{ $product->low_stock_alert }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">All stock OK</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection