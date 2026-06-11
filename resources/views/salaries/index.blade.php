@extends('layouts.app')

@section('title', 'Salaries')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Salaries</h1>
        <a href="{{ route('salaries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Salary
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Month/Year</th>
                        <th>Basic</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $salary)
                    <tr>
                        <td>
                            <a href="{{ route('employees.show', $salary->employee) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $salary->employee->name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $salary->employee->designation }}</small>
                        </td>
                        <td>{{ date('F', mktime(0,0,0,$salary->month,1)) }} {{ $salary->year }}</td>
                        <td>Rs. {{ number_format($salary->basic_salary, 2) }}</td>
                        <td class="text-success">+ Rs. {{ number_format($salary->allowances, 2) }}</td>
                        <td class="text-danger">- Rs. {{ number_format($salary->deductions, 2) }}</td>
                        <td class="fw-semibold">Rs. {{ number_format($salary->net_salary, 2) }}</td>
                        <td>
                            @if($salary->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                                <br>
                                <small class="text-muted">{{ $salary->paid_date?->format('d M Y') }}</small>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($salary->status === 'pending')
                                <form action="{{ route('salaries.mark-paid', $salary) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success"
                                            onclick="return confirm('Mark as paid?')">
                                        <i class="bi bi-check-circle"></i> Pay
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('salaries.show', $salary) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('salaries.destroy', $salary) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No salary records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($salaries->hasPages())
        <div class="card-footer">
            {{ $salaries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection