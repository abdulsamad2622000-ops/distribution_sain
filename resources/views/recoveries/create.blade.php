 
@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Record Payment</div>
    <div class="card-body">
        <form action="{{ route('recoveries.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" id="customerSelect" class="form-select @error('customer_id') is-invalid @enderror"
                        onchange="loadInvoices(this.value)">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ (old('customer_id') == $customer->id || request('customer_id') == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }} — Outstanding: PKR {{ number_format($customer->outstanding()) }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Invoice <span class="text-danger">*</span></label>
                    <select name="sale_id" id="invoiceSelect" class="form-select @error('sale_id') is-invalid @enderror">
                        <option value="">-- Select Invoice --</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}"
                                data-customer="{{ $sale->customer_id }}"
                                data-due="{{ $sale->due_amount }}"
                                {{ (old('sale_id') == $sale->id || request('sale_id') == $sale->id) ? 'selected' : '' }}>
                                {{ $sale->invoice_no }} — Due: PKR {{ number_format($sale->due_amount) }}
                            </option>
                        @endforeach
                    </select>
                    @error('sale_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amountInput" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount') }}" step="0.01" placeholder="0.00">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Reference No</label>
                    <input type="text" name="reference_no" class="form-control"
                        value="{{ old('reference_no') }}" placeholder="Cheque/Transaction no">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Notes</label>
                    <input type="text" name="notes" class="form-control" placeholder="Optional">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Payment</button>
                    <a href="{{ route('recoveries.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto select invoice if sale_id in URL
    const urlParams = new URLSearchParams(window.location.search);
    const saleId = urlParams.get('sale_id');
    const customerId = urlParams.get('customer_id');

    if (saleId) {
        document.getElementById('invoiceSelect').value = saleId;
        const option = document.querySelector(`#invoiceSelect option[value="${saleId}"]`);
        if (option) {
            document.getElementById('amountInput').value = option.dataset.due;
        }
    }

    document.getElementById('invoiceSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.dataset.due) {
            document.getElementById('amountInput').value = option.dataset.due;
        }
    });

    function loadInvoices(customerId) {
        const select = document.getElementById('invoiceSelect');
        const options = select.querySelectorAll('option');
        options.forEach(opt => {
            if (opt.value === '') return;
            opt.style.display = (opt.dataset.customer == customerId) ? '' : 'none';
        });
    }

    // Init
    if (customerId) {
        loadInvoices(customerId);
    }
</script>
@endsection