@extends('layouts.app')

@section('title', 'Purchase Order — ' . $purchaseOrder->po_number)

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $purchaseOrder->po_number }}</span>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted">Supplier</td>
                        <td><strong>{{ $purchaseOrder->supplier->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Order Date</td>
                        <td>{{ $purchaseOrder->order_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Expected Date</td>
                        <td>{{ $purchaseOrder->expected_date ? $purchaseOrder->expected_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Amount</td>
                        <td><strong>PKR {{ number_format($purchaseOrder->total_amount) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
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
                            <span class="badge bg-{{ $colors[$purchaseOrder->status] ?? 'secondary' }}">
                                {{ ucfirst($purchaseOrder->status) }}
                            </span>
                        </td>
                    </tr>
                    @if($purchaseOrder->approvedBy)
                    <tr>
                        <td class="text-muted">Approved By</td>
                        <td>{{ $purchaseOrder->approvedBy->name }}</td>
                    </tr>
                    @endif
                    @if($purchaseOrder->notes)
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $purchaseOrder->notes }}</td>
                    </tr>
                    @endif
                </table>

                {{-- Action Buttons --}}
                @if($purchaseOrder->status === 'draft')
                <form action="{{ route('purchase-orders.approve', $purchaseOrder) }}"
                    method="POST" class="mb-2">
                    @csrf
                    <button class="btn btn-success w-100">
                        <i class="bi bi-check-circle"></i> Approve PO
                    </button>
                </form>
                @endif

                @if(in_array($purchaseOrder->status, ['approved', 'partial']))
                <a href="{{ route('grns.create', ['po_id' => $purchaseOrder->id]) }}"
                    class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-box-arrow-in-down"></i> Receive Stock (GRN)
                </a>
                @endif

                @if(in_array($purchaseOrder->status, ['draft', 'approved']))
                <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}"
                    method="POST" onsubmit="return confirm('Cancel this PO?')">
                    @csrf
                    <button class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i> Cancel PO
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Order Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Ordered Qty</th>
                                <th>Received Qty</th>
                                <th>Remaining</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td class="ps-3">{{ $item->product->name }}</td>
                                <td>{{ $item->qty }}</td>
                                <td class="text-success">{{ $item->received_qty }}</td>
                                <td class="{{ $item->remaining_qty > 0 ? 'text-warning' : 'text-success' }}">
                                    {{ $item->remaining_qty }}
                                </td>
                                <td>PKR {{ number_format($item->unit_price) }}</td>
                                <td>PKR {{ number_format($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($purchaseOrder->grns->count() > 0)
        <div class="card">
            <div class="card-header">Goods Received Notes</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">GRN Number</th>
                                <th>Received Date</th>
                                <th>Received By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->grns as $grn)
                            <tr>
                                <td class="ps-3"><strong>{{ $grn->grn_number }}</strong></td>
                                <td>{{ $grn->received_date->format('d M Y') }}</td>
                                <td>{{ $grn->user->name }}</td>
                                <td>
                                    <a href="{{ route('grns.show', $grn) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection