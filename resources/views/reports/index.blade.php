@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<!-- Tabs -->
<div class="card mb-3">
    <div class="card-body p-2">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('reports.sales') }}" class="btn btn-sm {{ request()->routeIs('reports.sales') ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="bi bi-receipt"></i> Sales Report
            </a>
            <a href="{{ route('reports.recovery') }}" class="btn btn-sm {{ request()->routeIs('reports.recovery') ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="bi bi-cash-coin"></i> Recovery Report
            </a>
            <a href="{{ route('reports.stock') }}" class="btn btn-sm {{ request()->routeIs('reports.stock') ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="bi bi-box-seam"></i> Stock Report
            </a>
            <a href="{{ route('reports.profit-loss') }}" class="btn btn-sm {{ request()->routeIs('reports.profit-loss') ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="bi bi-graph-up"></i> Profit & Loss
            </a>
            <a href="{{ route('reports.customer-ledger') }}" class="btn btn-sm {{ request()->routeIs('reports.customer-ledger') ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="bi bi-journal-text"></i> Customer Ledger
            </a>
        </div>
    </div>
</div>

@if(request()->routeIs('reports.index'))
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-bar-chart text-muted" style="font-size:60px;"></i>
        <h5 class="mt-3 text-muted">Select a report from above</h5>
    </div>
</div>

@elseif(request()->routeIs('reports.sales'))
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.sales') }}" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold">From</label>
                <input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">To</label>
                <input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Generate</button></div>
            <div class="col-md-2">
                <a href="{{ route('reports.sales.pdf', ['from' => $from, 'to' => $to]) }}"
                    class="btn btn-danger w-100">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
            </div>
        </form>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Total Sales</div>
                <div class="fw-bold fs-4 text-success">PKR {{ number_format($total_sales) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Total Recovered</div>
                <div class="fw-bold fs-4 text-info">PKR {{ number_format($total_recovered) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Outstanding</div>
                <div class="fw-bold fs-4 text-danger">PKR {{ number_format($total_due) }}</div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th class="ps-3">Invoice #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr></thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="ps-3">{{ $sale->invoice_no }}</td>
                        <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                        <td>PKR {{ number_format($sale->net_amount) }}</td>
                        <td>PKR {{ number_format($sale->paid_amount) }}</td>
                        <td class="text-danger">PKR {{ number_format($sale->due_amount) }}</td>
                        <td><span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }}">{{ ucfirst($sale->status) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@elseif(request()->routeIs('reports.recovery'))
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.recovery') }}" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold">From</label>
                <input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">To</label>
                <input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Generate</button></div>
            <div class="col-md-2">
                <a href="{{ route('reports.recovery.pdf', ['from' => $from, 'to' => $to]) }}"
                    class="btn btn-danger w-100">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
            </div>
        </form>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body text-center">
        <div class="text-muted">Total Recovered</div>
        <div class="fw-bold fs-3 text-success">PKR {{ number_format($total_recovered) }}</div>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th class="ps-3">Customer</th>
                    <th>Invoice #</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Date</th>
                </tr></thead>
                <tbody>
                    @forelse($recoveries as $recovery)
                    <tr>
                        <td class="ps-3">{{ $recovery->customer->name ?? 'N/A' }}</td>
                        <td>{{ $recovery->sale->invoice_no ?? 'N/A' }}</td>
                        <td class="text-success fw-bold">PKR {{ number_format($recovery->amount) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $recovery->payment_method)) }}</td>
                        <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@elseif(request()->routeIs('reports.stock'))
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam me-2"></i>Stock Report</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th class="ps-3">Product</th>
                    <th>SKU</th>
                    <th>Supplier</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Status</th>
                </tr></thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-3"><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                        <td>PKR {{ number_format($product->purchase_price) }}</td>
                        <td>PKR {{ number_format($product->selling_price) }}</td>
                        <td><span class="badge bg-{{ $product->stock_qty <= $product->low_stock_alert ? 'danger' : 'success' }}">
                            {{ $product->stock_qty }}
                        </span></td>
                        <td>{{ $product->unit }}</td>
                        <td>{{ $product->stock_qty == 0 ? '❌ Out' : ($product->stock_qty <= $product->low_stock_alert ? '⚠️ Low' : '✅ OK') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No products</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@elseif(request()->routeIs('reports.profit-loss'))
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.profit-loss') }}" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold">From</label>
                <input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">To</label>
                <input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Calculate</button></div>
            <div class="col-md-2">
                <a href="{{ route('reports.profit-loss.pdf', ['from' => $from, 'to' => $to]) }}"
                    class="btn btn-danger w-100">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
            </div>
        </form>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-graph-up text-success fs-2"></i>
                <div class="text-muted mt-2">Sales Revenue</div>
                <div class="fw-bold fs-4 text-success">PKR {{ number_format($total_sales) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-box-seam text-danger fs-2"></i>
                <div class="text-muted mt-2">Purchase Cost</div>
                <div class="fw-bold fs-4 text-danger">PKR {{ number_format($total_cost) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-wallet2 text-danger fs-2"></i>
                <div class="text-muted mt-2">Expenses</div>
                <div class="fw-bold fs-4 text-danger">PKR {{ number_format($total_expenses) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-currency-dollar fs-2 text-{{ $net_profit >= 0 ? 'success' : 'danger' }}"></i>
                <div class="text-muted mt-2">Net Profit</div>
                <div class="fw-bold fs-4 text-{{ $net_profit >= 0 ? 'success' : 'danger' }}">
                    PKR {{ number_format($net_profit) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><td>Total Sales Revenue</td><td class="text-end text-success fw-bold">PKR {{ number_format($total_sales) }}</td></tr>
            <tr><td>(-) Purchase Cost of Sold Items</td><td class="text-end text-danger">PKR {{ number_format($total_cost) }}</td></tr>
            <tr><td class="fw-bold">Gross Profit</td><td class="text-end fw-bold">PKR {{ number_format($gross_profit) }}</td></tr>
            <tr><td>(-) Expenses</td><td class="text-end text-danger">PKR {{ number_format($total_expenses) }}</td></tr>
            <tr style="border-top:2px solid #333;">
                <td class="fw-bold fs-5">Net Profit / Loss</td>
                <td class="text-end fw-bold fs-5 text-{{ $net_profit >= 0 ? 'success' : 'danger' }}">
                    PKR {{ number_format($net_profit) }}
                </td>
            </tr>
        </table>
    </div>
</div>

@elseif(request()->routeIs('reports.customer-ledger'))
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.customer-ledger') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Select Customer</label>
                <select name="customer_id" class="form-select">
                    <option value="">-- All Customers --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Generate</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reports.customer-ledger.pdf') }}" class="btn btn-danger w-100">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th class="ps-3">Customer</th>
                    <th>Area</th>
                    <th>Total Sales</th>
                    <th>Recovered</th>
                    <th>Outstanding</th>
                </tr></thead>
                <tbody>
                    @forelse($customers_data as $c)
                    <tr>
                        <td class="ps-3"><strong>{{ $c->name }}</strong></td>
                        <td>{{ $c->area ?? 'N/A' }}</td>
                        <td>PKR {{ number_format($c->totalSales()) }}</td>
                        <td class="text-success">PKR {{ number_format($c->totalRecovered()) }}</td>
                        <td class="text-{{ $c->outstanding() > 0 ? 'danger' : 'success' }} fw-bold">
                            PKR {{ number_format($c->outstanding()) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection