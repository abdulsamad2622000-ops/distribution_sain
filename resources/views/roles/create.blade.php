@extends('layouts.app')

@section('title', 'Add Role')

@section('content')
<form action="{{ route('roles.store') }}" method="POST">
@csrf
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-plus me-2"></i>New Role</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                            value="{{ old('display_name') }}" placeholder="e.g. Sales Manager"
                            oninput="autoSlug(this.value)">
                        @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Role Key <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="roleKey" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="e.g. sales_manager">
                        <small class="text-muted">Lowercase, no spaces (auto-generated)</small>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                            placeholder="What can this role do?">{{ old('description') }}</textarea>
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
                            onclick="selectModule('{{ $module }}')">Select All</button>
                    </div>
                    <div class="d-flex flex-wrap gap-2 ps-3">
                        @foreach($perms as $permission)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input perm-{{ $module }}"
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                id="perm_{{ $permission->id }}"
                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
            <div class="card-header">Quick Presets</div>
            <div class="card-body">
                <p style="font-size:12px;color:#6c757d;">Click a preset to auto-select common permissions</p>
                <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2"
                    onclick="applyPreset('salesman')">
                    🛒 Salesman Preset
                </button>
                <button type="button" class="btn btn-outline-info btn-sm w-100 mb-2"
                    onclick="applyPreset('accountant')">
                    📊 Accountant Preset
                </button>
                <button type="button" class="btn btn-outline-success btn-sm w-100 mb-2"
                    onclick="applyPreset('manager')">
                    👔 Manager Preset
                </button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-lg"></i> Save Role
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
// All permissions data
const allPermissions = @json($permissions->flatten()->pluck('id', 'display_name'));

// Presets
const presets = {
    salesman: [
        'dashboard', 'customers', 'sales', 'recoveries', 'products'
    ],
    accountant: [
        'dashboard', 'customers', 'sales', 'recoveries', 'products',
        'suppliers', 'expenses', 'reports'
    ],
    manager: [
        'dashboard', 'customers', 'sales', 'recoveries', 'products',
        'suppliers', 'expenses', 'reports', 'users'
    ]
};

function autoSlug(val) {
    document.getElementById('roleKey').value = val.toLowerCase()
        .replace(/[^a-z0-9\s_]/g, '')
        .replace(/\s+/g, '_');
}

function selectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = true);
}

function deselectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
}

function selectModule(module) {
    document.querySelectorAll('.perm-' + module).forEach(cb => cb.checked = !cb.checked);
}

function applyPreset(preset) {
    deselectAll();
    const modules = presets[preset];
    modules.forEach(module => {
        document.querySelectorAll('.perm-' + module).forEach(cb => cb.checked = true);
    });
}
</script>
@endsection