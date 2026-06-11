@extends('layouts.app')

@section('title', isset($employee->id) ? 'Edit Employee' : 'New Employee')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ isset($employee->id) ? 'Edit Employee' : 'New Employee' }}</h1>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
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
            <form action="{{ isset($employee->id) ? route('employees.update', $employee) : route('employees.store') }}"
                  method="POST">
                @csrf
                @if(isset($employee->id))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" name="employee_id" class="form-control"
                               value="{{ old('employee_id', $employee->employee_id) }}"
                               placeholder="e.g. EMP-001" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $employee->name) }}"
                               placeholder="Full name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">CNIC</label>
                        <input type="text" name="cnic" class="form-control"
                               value="{{ old('cnic', $employee->cnic) }}"
                               placeholder="e.g. 42101-1234567-1">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $employee->email) }}"
                               placeholder="email@example.com">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $employee->phone) }}"
                               placeholder="03xx-xxxxxxx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
                        <input type="text" name="designation" class="form-control"
                               value="{{ old('designation', $employee->designation) }}"
                               placeholder="e.g. Accountant" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control"
                               value="{{ old('department', $employee->department) }}"
                               placeholder="e.g. Finance" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Joining Date <span class="text-danger">*</span></label>
                        <input type="date" name="joining_date" class="form-control"
                               value="{{ old('joining_date', isset($employee->id) ? $employee->joining_date->format('Y-m-d') : '') }}"
                               required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Basic Salary <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" name="basic_salary" class="form-control"
                                   value="{{ old('basic_salary', $employee->basic_salary) }}"
                                   step="0.01" min="0" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" class="form-control" rows="2"
                                  placeholder="Full address">{{ old('address', $employee->address) }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        {{ isset($employee->id) ? 'Update Employee' : 'Save Employee' }}
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection