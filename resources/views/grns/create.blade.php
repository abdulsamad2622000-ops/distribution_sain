@extends('layouts.app')

@section('title', 'New GRN')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>New Goods Received Note — {{ $grn_number }}</span>
        <a href="{{ route('grns.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('grns.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Purchase Order <span class="text-danger">*</span></label>
                    <select name="purchase_order_id" class="form-select" id="poSelect"
                        onchange="loadPoItems(this)">
                        <option value="">-- Select Approved PO --</option>
                        @foreach($approved_pos as $apo)
                            <option value="{{ $apo->id }}"
                                {{ ($po && $po->id == $apo->id) ? 'selected' : '' }}>
                                {{ $apo->po_number }} — {{ $apo->supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Received Date <span class="text-danger">*</span></label>
                    <input type="date" name="received_date" class="form-control"
                        value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div id="itemsSection" class="{{ $po ? '' : 'd-none' }}">
                <div class="card mb-3">
                    <div class="card-header">Items to Receive</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Product</th>
                                        <th>Ordered</th>
                                        <th>Already Received</th>
                                        <th>Remaining</th>
                                        <th>Receiving Now</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    @if($po)
                                        @foreach($po->items as $idx => $item)
                                        <tr>
                                            <td class="ps-3">
                                                <input type="hidden" name="items[{{ $idx }}][po_item_id]" value="{{ $item->id }}">
                                                <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                                                {{ $item->product->name }}
                                            </td>
                                            <td>{{ $item->qty }}</td>
                                            <td class="text-success">{{ $item->received_qty }}</td>
                                            <td class="text-warning">{{ $item->remaining_qty }}</td>
                                            <td>
                                                <input type="number" name="items[{{ $idx }}][qty_received]"
                                                    id="recv_{{ $idx }}" class="form-control form-control-sm"
                                                    value="{{ $item->remaining_qty }}"
                                                    min="0" max="{{ $item->remaining_qty }}"
                                                    step="0.01" onchange="calcRow({{ $idx }})">
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $idx }}][unit_price]"
                                                    id="uprice_{{ $idx }}" class="form-control form-control-sm"
                                                    value="{{ $item->unit_price }}"
                                                    step="0.01" onchange="calcRow({{ $idx }})">
                                            </td>
                                            <td>
                                                <input type="text" id="rowtotal_{{ $idx }}"
                                                    class="form-control form-control-sm bg-light"
                                                    readonly value="{{ $item->remaining_qty * $item->unit_price }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"
                            placeholder="Optional notes"></textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save GRN & Update Stock
                    </button>
                    <a href="{{ route('grns.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function calcRow(idx) {
    const recv  = parseFloat(document.getElementById('recv_' + idx)?.value)   || 0;
    const price = parseFloat(document.getElementById('uprice_' + idx)?.value) || 0;
    const el    = document.getElementById('rowtotal_' + idx);
    if (el) el.value = (recv * price).toFixed(2);
}

function loadPoItems(select) {
    const poId = select.value;
    if (!poId) return;
    window.location.href = '{{ route('grns.create') }}?po_id=' + poId;
}
</script>
@endsection