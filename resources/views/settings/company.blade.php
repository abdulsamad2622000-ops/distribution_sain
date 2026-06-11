@extends('layouts.app')

@section('title', 'Company Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-building me-2"></i>
                <strong>Company Settings</strong>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Company Identity</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name', $settings->company_name) }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tagline</label>
                            <input type="text" name="company_tagline" class="form-control"
                                value="{{ old('company_tagline', $settings->company_tagline) }}"
                                placeholder="e.g. Your Trusted Distribution Partner">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror"
                                value="{{ old('company_email', $settings->company_email) }}">
                            @error('company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="company_phone" class="form-control"
                                value="{{ old('company_phone', $settings->company_phone) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="company_address" class="form-control" rows="2">{{ old('company_address', $settings->company_address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="company_city" class="form-control"
                                value="{{ old('company_city', $settings->company_city) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tax Number (NTN/GST)</label>
                            <input type="text" name="tax_number" class="form-control"
                                value="{{ old('tax_number', $settings->tax_number) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control"
                                value="{{ old('registration_number', $settings->registration_number) }}">
                        </div>
                    </div>

                    <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Financial Settings</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror"
                                value="{{ old('currency_symbol', $settings->currency_symbol) }}" placeholder="PKR">
                            @error('currency_symbol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror"
                                value="{{ old('currency_code', $settings->currency_code) }}" placeholder="PKR">
                            @error('currency_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Prefix <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_prefix" class="form-control @error('invoice_prefix') is-invalid @enderror"
                                value="{{ old('invoice_prefix', $settings->invoice_prefix) }}"
                                placeholder="INV" maxlength="10">
                            <div class="form-text">e.g. INV → INV-00001</div>
                            @error('invoice_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tax / GST %</label>
                            <input type="number" name="tax_percentage" class="form-control"
                                value="{{ old('tax_percentage', $settings->tax_percentage) }}"
                                step="0.01" min="0" max="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Financial Year Starts</label>
                            <select name="financial_year_start" class="form-select">
                                @foreach(range(1,12) as $m)
                                    <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}"
                                        {{ old('financial_year_start', $settings->financial_year_start) == str_pad($m,2,'0',STR_PAD_LEFT) ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Company Logo</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            @if($settings->logo_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($settings->logo_path) }}"
                                        alt="Logo" class="img-thumbnail" style="max-height:80px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                                accept="image/png,image/jpeg">
                            <div class="form-text">PNG or JPG, max 2MB. Shown on invoices.</div>
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Settings
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection