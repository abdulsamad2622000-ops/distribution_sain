 
@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Add Expense</div>
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="e.g. Fuel, Salary">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select">
                        <option value="general">General</option>
                        <option value="salary">Salary</option>
                        <option value="fuel">Fuel</option>
                        <option value="rent">Rent</option>
                        <option value="utility">Utility</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount') }}" step="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection