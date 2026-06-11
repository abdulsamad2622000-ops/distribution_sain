@extends('layouts.app')

@section('title', isset($account->id) ? 'Edit Account' : 'New Account')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">{{ isset($account->id) ? 'Edit Account' : 'New Account' }}</h1>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($account->id)
                                ? route('accounts.update', $account)
                                : route('accounts.store') }}"
                  method="POST">
                @csrf
                @if(isset($account->id))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Account Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control"
                           value="{{ old('code', $account->code) }}"
                           placeholder="e.g. 1001" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Account Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $account->name) }}"
                           placeholder="e.g. Cash in Hand" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        @foreach(['asset', 'liability', 'equity', 'revenue', 'expense'] as $type)
                            <option value="{{ $type }}"
                                {{ old('type', $account->type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Optional description">{{ old('description', $account->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                               name="is_active" value="1" id="isActive"
                               {{ old('is_active', $account->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Active</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        {{ isset($account->id) ? 'Update Account' : 'Save Account' }}
                    </button>
                    <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection