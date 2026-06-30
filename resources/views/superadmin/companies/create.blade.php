@extends('layouts.superadmin')

@section('title', 'Onboard Company')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-buildings me-2"></i>New Client Company</div>
    <div class="card-body">
        <form action="{{ route('superadmin.companies.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ old('owner_name') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email (login) <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Subscription Plan</label>
                    <select name="plan_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" @selected(old('plan_id')==$plan->id)>
                                {{ $plan->name }} ({{ number_format($plan->price) }}/{{ $plan->billing_cycle }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="active"    @selected(old('status','active')=='active')>Active</option>
                        <option value="inactive"  @selected(old('status')=='inactive')>Inactive</option>
                        <option value="suspended" @selected(old('status')=='suspended')>Suspended</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Admin Password <span class="text-danger">*</span></label>
                    <input type="text" name="admin_password" value="{{ old('admin_password') }}"
                           class="form-control @error('admin_password') is-invalid @enderror" placeholder="min 6 chars">
                    @error('admin_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Trial Ends At</label>
                    <input type="date" name="trial_ends_at" value="{{ old('trial_ends_at') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subscription Ends At</label>
                    <input type="date" name="subscription_ends_at" value="{{ old('subscription_ends_at') }}" class="form-control">
                </div>
            </div>

            <div class="alert alert-light border mt-3 mb-0" style="font-size:13px;">
                <i class="bi bi-info-circle text-primary"></i>
                Is company ka admin login automatically ban jayega — email upar wali aur password jo set kiya.
            </div>

            <div class="mt-3">
                <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Create Company</button>
                <a href="{{ route('superadmin.companies.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
