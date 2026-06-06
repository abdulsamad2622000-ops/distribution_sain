 
@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-shield-check me-2"></i>Roles & Permissions</span>
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Role
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Display Name</th>
                        <th>Role Key</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><strong>{{ $role->display_name }}</strong></td>
                        <td><code>{{ $role->name }}</code></td>
                        <td>{{ $role->description ?? 'N/A' }}</td>
                        <td><span class="badge bg-info">{{ $role->users_count }} users</span></td>
                        <td><span class="badge bg-secondary">{{ $role->permissions->count() }} permissions</span></td>
                        <td>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No roles found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection