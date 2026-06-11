@extends('layouts.app')

@section('title', 'New Salary')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">New Salary</h1>
        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
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

    <div class="card shadow-sm" style="max-width:650px;">
        <div class="card-body">
            <form action="{{ route('salaries.store') }}" method="POST" id="salaryForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" id="employeeSelect" required>
                        <option value="">-- Select Employee --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                    data-salary="{{ $employee->basic_salary }}"
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->employee_id }} — {{ $employee->name }}
                                ({{ $employee->designation }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Month <span class="text-danger">*</span></label>
                        <select name="month" class="form-select" required>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}"
                                    {{ old('month', date('n')) == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0,0,0,$m,1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                        <input type="number" name="year" class="form-control"
                               value="{{ old('year', date('Y')) }}"
                               min="2000" max="2100" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Basic Salary <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rs.</span>
                        <input type="number" name="basic_salary" id="basicSalary"
                               class="form-control" step="0.01" min="0"
                               value="{{ old('basic_salary', 0) }}"
                               placeholder="0.00" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Allowances</label>
                        <div class="input-group">
                            <span class="input-group-text text-success">+</span>
                            <input type="number" name="allowances" id="allowances"
                                   class="form-control" step="0.01" min="0"
                                   value="{{ old('allowances', 0) }}"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Deductions</label>
                        <div class="input-group">
                            <span class="input-group-text text-danger">-</span>
                            <input type="number" name="deductions" id="deductions"
                                   class="form-control" step="0.01" min="0"
                                   value="{{ old('deductions', 0) }}"
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>

                {{-- Net Salary Preview --}}
                <div class="alert alert-info py-2 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Net Salary:</span>
                        <span class="fw-bold fs-5" id="netSalaryDisplay">Rs. 0.00</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                              placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Salary
                    </button>
                    <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateNet() {
        const basic      = parseFloat(document.getElementById('basicSalary').value)  || 0;
        const allowances = parseFloat(document.getElementById('allowances').value)   || 0;
        const deductions = parseFloat(document.getElementById('deductions').value)   || 0;
        const net        = basic + allowances - deductions;
        document.getElementById('netSalaryDisplay').textContent = 'Rs. ' + net.toFixed(2);
    }

    document.getElementById('employeeSelect').addEventListener('change', function() {
        const salary = this.options[this.selectedIndex].dataset.salary || 0;
        document.getElementById('basicSalary').value = salary;
        updateNet();
    });

    document.getElementById('basicSalary').addEventListener('input', updateNet);
    document.getElementById('allowances').addEventListener('input', updateNet);
    document.getElementById('deductions').addEventListener('input', updateNet);

    updateNet();
</script>
@endpush