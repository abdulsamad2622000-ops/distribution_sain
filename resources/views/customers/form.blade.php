@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Customer' : 'New Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ isset($customer) ? 'Edit Customer' : 'New Customer' }}</span>
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($customer)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $customer->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $customer->phone ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Area</label>
                            <input type="text" name="area" class="form-control"
                                value="{{ old('area', $customer->area ?? '') }}"
                                placeholder="e.g. Karachi, Lahore">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Opening Balance (PKR)</label>
                            <input type="number" name="balance" class="form-control"
value="{{ old('balance', $customer->balance ?? '') }}"
                                step="0.01" min="0">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            {{ isset($customer) ? 'Update Customer' : 'Create Customer' }}
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection