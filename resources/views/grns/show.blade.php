@extends('layouts.app')

@section('title', 'GRN — ' . $grn->grn_number)

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $grn->grn_number }}</span>
                <a href="{{ route('grns.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted">PO Number</td>
                        <td>
                            <a href="{{ route('purchase-orders.show', $grn->purchaseOrder) }}">
                                {{ $grn->purchaseOrder->po_number }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Supplier</td>
                        <td><strong>{{ $grn->supplier->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Received Date</td>
                        <td>{{ $grn->received_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Received By</td>
                        <td>{{ $grn->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-success">{{ ucfirst($grn->status) }}</span>
                        </td>
                    </tr>
                    @if($grn->notes)
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $grn->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Received Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Qty Received</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grn->items as $item)
                            <tr>
                                <td class="ps-3">{{ $item->product->name }}</td>
                                <td><strong>{{ $item->qty_received }}</strong></td>
                                <td>PKR {{ number_format($item->unit_price) }}</td>
                                <td>PKR {{ number_format($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td><strong class="text-success">
                                    PKR {{ number_format($grn->items->sum('total_price')) }}
                                </strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection