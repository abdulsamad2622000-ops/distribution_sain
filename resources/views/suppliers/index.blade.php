 
@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-truck me-2"></i>Suppliers</span>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Supplier
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><strong>{{ $supplier->name }}</strong></td>
                        <td>{{ $supplier->phone ?? 'N/A' }}</td>
                        <td>{{ $supplier->address ?? 'N/A' }}</td>
                        <td>PKR {{ number_format($supplier->balance) }}</td>
                        <td>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No suppliers found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection