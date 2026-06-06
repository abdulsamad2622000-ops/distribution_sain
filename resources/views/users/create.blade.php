@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-plus me-2"></i>Add User</div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePass('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')<div class="text-danger" style="font-size:12px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">System Role</label>
                            <select name="role" class="form-select">
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <small class="text-muted">Admin = full access. Staff = role-based access.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Assign Role (Permissions)</label>
                            <select name="role_id" class="form-select" onchange="showRolePerms(this)">
                                <option value="">-- No Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        data-perms="{{ $role->permissions->pluck('display_name')->join(', ') }}"
                                        data-desc="{{ $role->description }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Role Info -->
                        <div class="col-12" id="rolePermInfo" style="display:none;">
                            <div class="alert alert-info mb-0" style="font-size:12px;">
                                <strong>Permissions:</strong> <span id="rolePermList"></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Save User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-check me-2"></i>Available Roles</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Role</th>
                            <th>Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td class="ps-3">
                                <strong>{{ $role->display_name }}</strong>
                                @if($role->description)
                                <div style="font-size:11px;color:#6c757d;">{{ $role->description }}</div>
                                @endif
                            </td>
                            <td style="font-size:11px;">{{ $role->permissions->count() }} permissions</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">
                            No roles yet. <a href="{{ route('roles.create') }}">Create one</a>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="bi bi-eye"></i>';
    }
}

function showRolePerms(select) {
    const option = select.options[select.selectedIndex];
    const perms  = option.dataset.perms;
    const box    = document.getElementById('rolePermInfo');
    const list   = document.getElementById('rolePermList');

    if (perms) {
        list.textContent = perms;
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
</script>
@endsection