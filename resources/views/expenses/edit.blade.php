@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Expense</div>
    <div class="card-body">
        <form action="{{ route('expenses.update', $expense) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $expense->title) }}">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select">
                        <option value="general" {{ $expense->category == 'general' ? 'selected' : '' }}>General</option>
                        <option value="salary" {{ $expense->category == 'salary' ? 'selected' : '' }}>Salary</option>
                        <option value="fuel" {{ $expense->category == 'fuel' ? 'selected' : '' }}>Fuel</option>
                        <option value="rent" {{ $expense->category == 'rent' ? 'selected' : '' }}>Rent</option>
                        <option value="utility" {{ $expense->category == 'utility' ? 'selected' : '' }}>Utility</option>
                        <option value="maintenance" {{ $expense->category == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="other" {{ $expense->category == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount', $expense->amount) }}" step="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date</label>
                    <input type="date" name="expense_date" class="form-control"
                        value="{{ old('expense_date', $expense->expense_date) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection