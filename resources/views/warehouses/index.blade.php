@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Warehouses & Godowns</span>
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> New Warehouse
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>City</th>
                        <th>Incharge</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $warehouse)
                    <tr>
                        <td><strong>{{ $warehouse->name }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $warehouse->code }}</span></td>
                        <td>{{ $warehouse->city ?? '-' }}</td>
                        <td>{{ $warehouse->incharge_name ?? '-' }}</td>
                        <td>{{ $warehouse->incharge_phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'danger' }}">
                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('warehouses.show', $warehouse) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('warehouses.edit', $warehouse) }}"
                                class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('warehouses.destroy', $warehouse) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this warehouse?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No warehouses found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection