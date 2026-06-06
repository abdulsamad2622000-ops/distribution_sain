 
@extends('layouts.app')

@section('title', 'New Invoice')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-receipt me-2"></i>Create New Invoice</div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} — {{ $customer->area ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sale Date <span class="text-danger">*</span></label>
                    <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Payment Type</label>
                    <select name="payment_type" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="credit">Credit</option>
                        <option value="installment">Installment</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Discount (PKR)</label>
                    <input type="number" name="discount" id="discountInput" class="form-control"
                        value="{{ old('discount', 0) }}" step="0.01" onchange="calcTotal()">
                </div>
            </div>

            <!-- Items -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <span>Invoice Items</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addRow()">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="itemsTable">
                            <thead>
                                <tr>
                                    <th class="ps-3">Product</th>
                                    <th>Sell Rate</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr id="row_0">
                                    <td class="ps-3">
                                        <select name="items[0][product_id]" class="form-select form-select-sm"
                                            onchange="setRate(0, this)">
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-rate="{{ $product->selling_price }}"
                                                    data-purchase="{{ $product->purchase_price }}"
                                                    data-stock="{{ $product->stock_qty }}">
                                                    {{ $product->name }} (Stock: {{ $product->stock_qty }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" id="rate_0"
                                            class="form-control form-control-sm" placeholder="0"
                                            step="0.01" onchange="calcRow(0)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][qty]" id="qty_0"
                                            class="form-control form-control-sm" placeholder="0"
                                            min="1" onchange="calcRow(0)">
                                    </td>
                                    <td>
                                        <input type="text" id="total_0"
                                            class="form-control form-control-sm bg-light" readonly value="0">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(0)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-3">Subtotal:</td>
                                    <td><strong id="subtotalDisplay">PKR 0</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-3">Discount:</td>
                                    <td><strong id="discountDisplay" class="text-danger">-PKR 0</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-3">Net Total:</td>
                                    <td><strong id="netDisplay" class="text-success fs-5">PKR 0</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Paid Amount</label>
                    <input type="number" name="paid_amount" id="paidAmount"
                        class="form-control" value="0" step="0.01" onchange="calcDue()">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Due Amount</label>
                    <input type="text" id="dueDisplay" class="form-control bg-light fw-bold text-danger" readonly value="PKR 0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Notes</label>
                    <input type="text" name="notes" class="form-control" placeholder="Optional">
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create Invoice
                </button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let rowCount = 1;

const productOptions = `@foreach($products as $p)
<option value="{{ $p->id }}" data-rate="{{ $p->selling_price }}" data-purchase="{{ $p->purchase_price }}" data-stock="{{ $p->stock_qty }}">
    {{ $p->name }} (Stock: {{ $p->stock_qty }})
</option>@endforeach`;

function addRow() {
    const idx = rowCount++;
    const row = `
    <tr id="row_${idx}">
        <td class="ps-3">
            <select name="items[${idx}][product_id]" class="form-select form-select-sm" onchange="setRate(${idx}, this)">
                <option value="">-- Select Product --</option>
                ${productOptions}
            </select>
        </td>
        <td><input type="number" name="items[${idx}][unit_price]" id="rate_${idx}" class="form-control form-control-sm" placeholder="0" step="0.01" onchange="calcRow(${idx})"></td>
        <td><input type="number" name="items[${idx}][qty]" id="qty_${idx}" class="form-control form-control-sm" placeholder="0" min="1" onchange="calcRow(${idx})"></td>
        <td><input type="text" id="total_${idx}" class="form-control form-control-sm bg-light" readonly value="0"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(${idx})"><i class="bi bi-trash"></i></button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
}

function removeRow(idx) {
    document.getElementById('row_' + idx)?.remove();
    calcTotal();
}

function setRate(idx, select) {
    const rate = select.options[select.selectedIndex]?.dataset.rate || 0;
    document.getElementById('rate_' + idx).value = rate;
    calcRow(idx);
}

function calcRow(idx) {
    const qty   = parseFloat(document.getElementById('qty_' + idx)?.value) || 0;
    const rate  = parseFloat(document.getElementById('rate_' + idx)?.value) || 0;
    const total = qty * rate;
    const el    = document.getElementById('total_' + idx);
    if (el) el.value = total.toFixed(2);
    calcTotal();
}

function calcTotal() {
    let subtotal = 0;
    document.querySelectorAll('[id^="total_"]').forEach(el => subtotal += parseFloat(el.value) || 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const net      = subtotal - discount;

    document.getElementById('subtotalDisplay').textContent = 'PKR ' + subtotal.toLocaleString();
    document.getElementById('discountDisplay').textContent = '-PKR ' + discount.toLocaleString();
    document.getElementById('netDisplay').textContent      = 'PKR ' + net.toFixed(2);
    calcDue();
}

function calcDue() {
    const netText = document.getElementById('netDisplay').textContent.replace('PKR ', '').replace(',', '');
    const net     = parseFloat(netText) || 0;
    const paid    = parseFloat(document.getElementById('paidAmount').value) || 0;
    document.getElementById('dueDisplay').value = 'PKR ' + Math.max(0, net - paid).toFixed(2);
}
</script>
@endsection