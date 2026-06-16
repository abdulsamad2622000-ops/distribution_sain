@extends('layouts.app')

@section('title', 'Customers')

@section('content')

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:12px;">TOTAL CUSTOMERS</div>
                <div class="fw-bold fs-3">{{ $totalCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <a href="{{ route('customers.index', ['outstanding' => 1]) }}" class="text-decoration-none">
            <div class="card text-center {{ request('outstanding') ? 'border-danger border-2' : '' }}">
                <div class="card-body">
                    <div class="text-muted mb-1" style="font-size:12px;">TOTAL RECEIVABLE</div>
                    <div class="fw-bold fs-3 text-danger">PKR {{ number_format($totalReceivable) }}</div>
                    <small class="text-muted">Outstanding from customers
</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('customers.index', ['outstanding' => 1]) }}" class="text-decoration-none">
            <div class="card text-center {{ request('outstanding') ? 'border-warning border-2' : '' }}">
                <div class="card-body">
                    <div class="text-muted mb-1" style="font-size:12px;">CUSTOMERS WITH DUES</div>
                    <div class="fw-bold fs-3 text-warning">{{ $customersWithDues }}</div>
                    <small class="text-muted">Have outstanding balance</small>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-people me-2"></i>Customers
            @if(request('outstanding'))
                <span class="badge bg-danger ms-2">Showing Outstanding Only</span>
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    Clear Filter
                </a>
            @endif
        </span>
       
        <div class="d-flex gap-2">
    <a href="{{ route('customers.index.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
    <button onclick="window.print()" class="btn btn-secondary btn-sm">
        <i class="bi bi-printer"></i> Print
    </button>
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Customer
    </a>
</div>
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
                            <span class="text-{{ $customer->balance > 0 ? 'danger' : 'success' }} fw-bold">
                                PKR {{ number_format($customer->balance) }}
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