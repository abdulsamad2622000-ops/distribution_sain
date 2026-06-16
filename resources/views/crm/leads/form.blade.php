@extends('layouts.app')

@section('title', isset($lead->id) ? 'Edit Lead' : 'New Lead')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ isset($lead->id) ? 'Edit Lead' : 'New Lead' }}</h1>
        <a href="{{ route('leads.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ isset($lead->id) ? route('leads.update', $lead) : route('leads.store') }}"
                  method="POST">
                @csrf
                @if(isset($lead->id))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $lead->name) }}"
                               placeholder="Lead name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Company</label>
                        <input type="text" name="company" class="form-control"
                               value="{{ old('company', $lead->company) }}"
                               placeholder="Company name">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $lead->phone) }}"
                               placeholder="03xx-xxxxxxx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $lead->email) }}"
                               placeholder="email@example.com">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Source</label>
                        <select name="source" class="form-select">
                            <option value="">-- Select Source --</option>
                            @foreach(['call', 'website', 'referral', 'walk-in', 'social-media', 'other'] as $src)
                                <option value="{{ $src }}"
                                    {{ old('source', $lead->source) == $src ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $src)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            @foreach(['new', 'contacted', 'negotiation', 'won', 'lost'] as $st)
                                <option value="{{ $st }}"
                                    {{ old('status', $lead->status ?? 'new') == $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            @foreach(['low', 'medium', 'high'] as $pr)
                                <option value="{{ $pr }}"
                                    {{ old('priority', $lead->priority ?? 'medium') == $pr ? 'selected' : '' }}>
                                    {{ ucfirst($pr) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Estimated Value (Rs.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" name="estimated_value" class="form-control"
                                   value="{{ old('estimated_value', $lead->estimated_value ?? 0) }}"
                                   step="0.01" min="0" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Expected Close Date</label>
                        <input type="date" name="expected_close_date" class="form-control"
                               value="{{ old('expected_close_date', isset($lead->id) ? $lead->expected_close_date?->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Any additional notes">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        {{ isset($lead->id) ? 'Update Lead' : 'Save Lead' }}
                    </button>
                    <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection