 
@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Customer</div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $customer->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Area</label>
                    <input type="text" name="area" class="form-control" value="{{ old('area', $customer->area) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Balance</label>
                    <input type="number" name="balance" class="form-control" value="{{ old('balance', $customer->balance) }}" step="0.01">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection