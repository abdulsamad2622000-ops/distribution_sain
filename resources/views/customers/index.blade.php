 
@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Customers</span>
        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Customer
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
                        <th>Area</th>
                        <th>Outstanding</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->area ?? 'N/A' }}</td>
                        <td>
                            <span class="text-{{ $customer->outstanding() > 0 ? 'danger' : 'success' }} fw-bold">
                                PKR {{ number_format($customer->outstanding()) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customers.ledger', $customer) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-journal-text"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No customers found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer">{{ $customers->links() }}</div>
    @endif
</div>
@endsection