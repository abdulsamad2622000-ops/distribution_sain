@extends('layouts.app')

@section('title', isset($warehouse) ? 'Edit Warehouse' : 'New Warehouse')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ isset($warehouse) ? 'Edit Warehouse' : 'New Warehouse' }}</span>
                <a href="{{ route('warehouses.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ isset($warehouse) ? route('warehouses.update', $warehouse) : route('warehouses.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($warehouse)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warehouse Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $warehouse->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code', $warehouse->code ?? '') }}"
                                placeholder="e.g. WH01" maxlength="20">
                            <div class="form-text">Unique code, no spaces</div>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control"
                                value="{{ old('city', $warehouse->city ?? '') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $warehouse->address ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $warehouse->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $warehouse->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Incharge Name</label>
                            <input type="text" name="incharge_name" class="form-control"
                                value="{{ old('incharge_name', $warehouse->incharge_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Incharge Phone</label>
                            <input type="text" name="incharge_phone" class="form-control"
                                value="{{ old('incharge_phone', $warehouse->incharge_phone ?? '') }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            {{ isset($warehouse) ? 'Update Warehouse' : 'Create Warehouse' }}
                        </button>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection