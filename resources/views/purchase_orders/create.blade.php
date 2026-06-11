@extends('layouts.app')

@section('title', 'New Purchase Order')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>New Purchase Order — {{ $po_number }}</span>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Order Date <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" class="form-control"
                        value="{{ old('order_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Expected Delivery Date</label>
                    <input type="date" name="expected_date" class="form-control"
                        value="{{ old('expected_date') }}">
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <span>Order Items</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addRow()">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Product</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr id="row_0">
                                    <td class="ps-3">
                                        <select name="items[0][product_id]"
                                            class="form-select form-select-sm">
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-price="{{ $product->purchase_price }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][qty]"
                                            id="qty_0" class="form-control form-control-sm"
                                            placeholder="0" min="1" onchange="calcRow(0)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]"
                                            id="price_0" class="form-control form-control-sm"
                                            placeholder="0" step="0.01" onchange="calcRow(0)">
                                    </td>
                                    <td>
                                        <input type="text" id="total_0"
                                            class="form-control form-control-sm bg-light"
                                            readonly value="0">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeRow(0)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-3">Total Amount:</td>
                                    <td><strong id="grandTotal" class="text-success fs-5">PKR 0</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create Purchase Order
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary ms-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let rowCount = 1;

const productOptions = `@foreach($products as $p)
<option value="{{ $p->id }}" data-price="{{ $p->purchase_price }}">{{ $p->name }}</option>
@endforeach`;

function addRow() {
    const idx = rowCount++;
    const row = `
    <tr id="row_${idx}">
        <td class="ps-3">
            <select name="items[${idx}][product_id]" class="form-select form-select-sm">
                <option value="">-- Select Product --</option>
                ${productOptions}
            </select>
        </td>
        <td><input type="number" name="items[${idx}][qty]" id="qty_${idx}"
            class="form-control form-control-sm" placeholder="0" min="1"
            onchange="calcRow(${idx})"></td>
        <td><input type="number" name="items[${idx}][unit_price]" id="price_${idx}"
            class="form-control form-control-sm" placeholder="0" step="0.01"
            onchange="calcRow(${idx})"></td>
        <td><input type="text" id="total_${idx}"
            class="form-control form-control-sm bg-light" readonly value="0"></td>
        <td><button type="button" class="btn btn-sm btn-danger"
            onclick="removeRow(${idx})"><i class="bi bi-trash"></i></button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
}

function removeRow(idx) {
    document.getElementById('row_' + idx)?.remove();
    calcTotal();
}

function calcRow(idx) {
    const qty   = parseFloat(document.getElementById('qty_' + idx)?.value)   || 0;
    const price = parseFloat(document.getElementById('price_' + idx)?.value) || 0;
    const total = qty * price;
    const el    = document.getElementById('total_' + idx);
    if (el) el.value = total.toFixed(2);
    calcTotal();
}

function calcTotal() {
    let grand = 0;
    document.querySelectorAll('[id^="total_"]').forEach(el => {
        grand += parseFloat(el.value) || 0;
    });
    document.getElementById('grandTotal').textContent = 'PKR ' + grand.toLocaleString();
}
</script>
@endsection