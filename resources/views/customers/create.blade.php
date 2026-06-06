@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus me-2"></i>Add Customer</div>
    <div class="card-body">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Area</label>
                    <input type="text" name="area" class="form-control" value="{{ old('area') }}" placeholder="e.g. Gulshan, Defence">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Opening Balance</label>
                    <input type="number" name="balance" class="form-control" value="{{ old('balance', 0) }}" step="0.01">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection