@extends('layouts.app')

@section('title', 'New Journal Entry')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">New Journal Entry</h1>
        <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('journal-entries.store') }}" method="POST" id="journalForm">
                @csrf

                {{-- Date & Description --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control"
                               value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control"
                               value="{{ old('description') }}"
                               placeholder="e.g. Cash sale received" required>
                    </div>
                </div>

                {{-- Journal Lines Table --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle" id="linesTable">
                        <thead class="table-dark">
                            <tr>
                                <th style="min-width:220px;">Account</th>
                                <th style="min-width:120px;">Type</th>
                                <th style="min-width:150px;">Amount (Rs.)</th>
                                <th style="min-width:180px;">Note</th>
                                <th style="width:50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="linesBody">
                            <tr>
                                <td>
                                    <select name="lines[0][account_id]" class="form-select" required>
                                        <option value="">-- Select Account --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} — {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="lines[0][type]" class="form-select type-select" required>
                                        <option value="debit">Debit</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="lines[0][amount]"
                                           class="form-control amount-input"
                                           step="0.01" min="0.01" placeholder="0.00" required>
                                </td>
                                <td>
                                    <input type="text" name="lines[0][description]"
                                           class="form-control" placeholder="Optional">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="lines[1][account_id]" class="form-select" required>
                                        <option value="">-- Select Account --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} — {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="lines[1][type]" class="form-select type-select" required>
                                        <option value="debit">Debit</option>
                                        <option value="credit" selected>Credit</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="lines[1][amount]"
                                           class="form-control amount-input"
                                           step="0.01" min="0.01" placeholder="0.00" required>
                                </td>
                                <td>
                                    <input type="text" name="lines[1][description]"
                                           class="form-control" placeholder="Optional">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="2" class="fw-semibold">Totals</td>
                                <td colspan="3">
                                    <span class="text-success fw-semibold">
                                        Dr: Rs. <span id="totalDebit">0.00</span>
                                    </span>
                                    &nbsp;|&nbsp;
                                    <span class="text-danger fw-semibold">
                                        Cr: Rs. <span id="totalCredit">0.00</span>
                                    </span>
                                    &nbsp;|&nbsp;
                                    <span id="balanceStatus"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Add Line Button --}}
                <div class="mb-4">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">
                        <i class="bi bi-plus-circle"></i> Add Line
                    </button>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Post Journal Entry
                    </button>
                    <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let rowIndex = 2;
    const accounts = @json($accounts);

    function accountOptions() {
        let opts = '<option value="">-- Select Account --</option>';
        accounts.forEach(a => {
            opts += `<option value="${a.id}">${a.code} — ${a.name}</option>`;
        });
        return opts;
    }

    function newRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="lines[${rowIndex}][account_id]" class="form-select" required>
                    ${accountOptions()}
                </select>
            </td>
            <td>
                <select name="lines[${rowIndex}][type]" class="form-select type-select" required>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            </td>
            <td>
                <input type="number" name="lines[${rowIndex}][amount]"
                       class="form-control amount-input"
                       step="0.01" min="0.01" placeholder="0.00" required>
            </td>
            <td>
                <input type="text" name="lines[${rowIndex}][description]"
                       class="form-control" placeholder="Optional">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        document.getElementById('linesBody').appendChild(tr);
        rowIndex++;
        bindEvents();
        updateTotals();
    }

    function updateTotals() {
        let debit = 0, credit = 0;
        document.querySelectorAll('#linesBody tr').forEach(row => {
            const type   = row.querySelector('.type-select')?.value;
            const amount = parseFloat(row.querySelector('.amount-input')?.value) || 0;
            if (type === 'debit')  debit  += amount;
            if (type === 'credit') credit += amount;
        });

        document.getElementById('totalDebit').textContent  = debit.toFixed(2);
        document.getElementById('totalCredit').textContent = credit.toFixed(2);

        const status = document.getElementById('balanceStatus');
        if (debit > 0 && Math.abs(debit - credit) < 0.01) {
            status.innerHTML = '<span class="badge bg-success">Balanced ✓</span>';
        } else {
            status.innerHTML = '<span class="badge bg-danger">Not Balanced</span>';
        }
    }

    function bindEvents() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.onclick = function () {
                const rows = document.querySelectorAll('#linesBody tr');
                if (rows.length > 2) {
                    this.closest('tr').remove();
                    updateTotals();
                } else {
                    alert('Minimum 2 lines required.');
                }
            };
        });
        document.querySelectorAll('.type-select, .amount-input').forEach(el => {
            el.oninput  = updateTotals;
            el.onchange = updateTotals;
        });
    }

    document.getElementById('addRow').addEventListener('click', newRow);
    bindEvents();
    updateTotals();
</script>
@endpush