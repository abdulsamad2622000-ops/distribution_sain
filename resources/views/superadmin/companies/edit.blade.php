@extends('layouts.superadmin')

@section('title', 'Edit Company')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Edit {{ $company->name }}</div>
    <div class="card-body">
        <form action="{{ route('superadmin.companies.update', $company) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $company->owner_name) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $company->email) }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" value="{{ old('address', $company->address) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Subscription Plan</label>
                    <select name="plan_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" @selected(old('plan_id', $company->plan_id)==$plan->id)>
                                {{ $plan->name }} ({{ number_format($plan->price) }}/{{ $plan->billing_cycle }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="active"    @selected(old('status', $company->status)=='active')>Active</option>
                        <option value="inactive"  @selected(old('status', $company->status)=='inactive')>Inactive</option>
                        <option value="suspended" @selected(old('status', $company->status)=='suspended')>Suspended</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trial Ends At</label>
                    <input type="date" name="trial_ends_at" value="{{ old('trial_ends_at', $company->trial_ends_at?->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subscription Ends At</label>
                    <input type="date" name="subscription_ends_at" value="{{ old('subscription_ends_at', $company->subscription_ends_at?->format('Y-m-d')) }}" class="form-control">
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="{{ route('superadmin.companies.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
