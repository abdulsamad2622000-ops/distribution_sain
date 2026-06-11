@extends('layouts.app')

@section('title', 'Goods Received Notes')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Goods Received Notes</span>
        <a href="{{ route('grns.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> New GRN
        </a>
    </div>
    <div class="card-body">

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="supplier_id" class="form-select form-select-sm">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from" class="form-control form-control-sm"
                    value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="to" class="form-control form-control-sm"
                    value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary btn-sm w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>GRN Number</th>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Received Date</th>
                        <th>Received By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grns as $grn)
                    <tr>
                        <td><strong>{{ $grn->grn_number }}</strong></td>
                        <td>
                            <a href="{{ route('purchase-orders.show', $grn->purchaseOrder) }}">
                                {{ $grn->purchaseOrder->po_number }}
                            </a>
                        </td>
                        <td>{{ $grn->supplier->name }}</td>
                        <td>{{ $grn->received_date->format('d M Y') }}</td>
                        <td>{{ $grn->user->name }}</td>
                        <td>
                            <a href="{{ route('grns.show', $grn) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No GRNs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $grns->withQueryString()->links() }}
    </div>
</div>
@endsection