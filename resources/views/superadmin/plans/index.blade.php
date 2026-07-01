@extends('layouts.superadmin')

@section('title', 'Subscription Plans')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Add Plan</div>
            <div class="card-body">
                <form action="{{ route('superadmin.plans.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Plan Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-7">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-5">
                            <label class="form-label">Cycle</label>
                            <select name="billing_cycle" class="form-select">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Max Users</label>
                            <input type="number" name="max_users" value="{{ old('max_users') }}" class="form-control" placeholder="∞">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Invoices</label>
                            <input type="number" name="max_invoices" value="{{ old('max_invoices') }}" class="form-control" placeholder="∞">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Features <small class="text-muted">(one per line)</small></label>
                        <textarea name="features" rows="3" class="form-control">{{ old('features') }}</textarea>
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> Save Plan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>All Plans</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Price</th>
                                <th>Limits</th>
                                <th>Companies</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td class="ps-3"><strong>{{ $plan->name }}</strong></td>
                                <td>{{ number_format($plan->price) }}<small class="text-muted">/{{ $plan->billing_cycle }}</small></td>
                                <td>
                                    <small class="text-muted">
                                        {{ $plan->max_users ?? '∞' }} users · {{ $plan->max_invoices ?? '∞' }} inv
                                    </small>
                                </td>
                                <td>{{ $plan->companies_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                        {{ $plan->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('superadmin.plans.toggle', $plan) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-secondary" title="Toggle">
                                            <i class="bi bi-toggle-{{ $plan->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this plan?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No plans yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
