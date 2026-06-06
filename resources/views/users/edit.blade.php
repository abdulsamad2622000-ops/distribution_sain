@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit User — {{ $user->name }}</div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Leave blank to keep current">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePass('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">System Role</label>
                            <select name="role" class="form-select">
                                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Assign Role (Permissions)</label>
                            <select name="role_id" class="form-select" onchange="showRolePerms(this)">
                                <option value="">-- No Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        data-perms="{{ $role->permissions->pluck('display_name')->join(', ') }}"
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12" id="rolePermInfo" style="{{ $user->roleModel ? '' : 'display:none;' }}">
                            <div class="alert alert-info mb-0" style="font-size:12px;">
                                <strong>Permissions:</strong>
                                <span id="rolePermList">{{ $user->roleModel?->permissions->pluck('display_name')->join(', ') }}</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update User
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
            <div class="card-header">Current Permissions</div>
            <div class="card-body">
                @if($user->role === 'admin')
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-shield-fill-check"></i> <strong>Admin</strong> — Full system access
                    </div>
                @elseif($user->roleModel)
                    @php $perms = $user->roleModel->permissions->groupBy('module'); @endphp
                    @foreach($perms as $module => $modulePerms)
                    <div class="mb-2">
                        <strong class="text-capitalize">{{ $module }}</strong>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($modulePerms as $perm)
                            <span class="badge bg-success" style="font-size:10px;">{{ $perm->action }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">No role assigned</p>
                @endif
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