 
@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<form action="{{ route('roles.update', $role) }}" method="POST">
@csrf
@method('PUT')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Role — {{ $role->display_name }}</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                            value="{{ old('display_name', $role->display_name) }}">
                        @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Role Key</label>
                        <input type="text" class="form-control bg-light" value="{{ $role->name }}" disabled>
                        <small class="text-muted">Role key cannot be changed</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $role->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-key me-2"></i>Permissions</span>
                <div>
                    <button type="button" class="btn btn-sm btn-success" onclick="selectAll()">Select All</button>
                    <button type="button" class="btn btn-sm btn-secondary ms-1" onclick="deselectAll()">Deselect All</button>
                </div>
            </div>
            <div class="card-body">
                @foreach($permissions as $module => $perms)
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <strong class="text-capitalize" style="min-width:120px;">{{ $module }}</strong>
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                            onclick="toggleModule('{{ $module }}')">Toggle</button>
                    </div>
                    <div class="d-flex flex-wrap gap-2 ps-3">
                        @foreach($perms as $permission)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input perm-{{ $module }}"
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                id="perm_{{ $permission->id }}"
                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $permission->id }}"
                                style="font-size:13px;">
                                {{ $permission->display_name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @if(!$loop->last)<hr class="my-2">@endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Role Info</div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Role Key</td><td><code>{{ $role->name }}</code></td></tr>
                    <tr><td class="text-muted">Users</td><td>{{ $role->users()->count() }} users</td></tr>
                    <tr><td class="text-muted">Permissions</td><td>{{ count($rolePermissions) }} assigned</td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">Quick Presets</div>
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2"
                    onclick="applyPreset('salesman')">🛒 Salesman</button>
                <button type="button" class="btn btn-outline-info btn-sm w-100 mb-2"
                    onclick="applyPreset('accountant')">📊 Accountant</button>
                <button type="button" class="btn btn-outline-success btn-sm w-100 mb-2"
                    onclick="applyPreset('manager')">👔 Manager</button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-lg"></i> Update Role
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
const presets = {
    salesman:   ['dashboard', 'customers', 'sales', 'recoveries', 'products'],
    accountant: ['dashboard', 'customers', 'sales', 'recoveries', 'products', 'suppliers', 'expenses', 'reports'],
    manager:    ['dashboard', 'customers', 'sales', 'recoveries', 'products', 'suppliers', 'expenses', 'reports', 'users']
};

function selectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = true);
}

function deselectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
}

function toggleModule(module) {
    const checkboxes = document.querySelectorAll('.perm-' + module);
    const allChecked = [...checkboxes].every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

function applyPreset(preset) {
    deselectAll();
    presets[preset].forEach(module => {
        document.querySelectorAll('.perm-' + module).forEach(cb => cb.checked = true);
    });
}
</script>
@endsection