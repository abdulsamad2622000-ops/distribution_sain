@extends('layouts.app')

@section('title', $employee->name)

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ $employee->name }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Employee Info --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Employee Info</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                             style="width:70px;height:70px;font-size:28px;">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                        <h5 class="mt-2 mb-0">{{ $employee->name }}</h5>
                        <small class="text-muted">{{ $employee->designation }}</small>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">ID</div>
                        <div class="col-7"><span class="badge bg-secondary">{{ $employee->employee_id }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Department</div>
                        <div class="col-7">{{ $employee->department }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Phone</div>
                        <div class="col-7">{{ $employee->phone ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Email</div>
                        <div class="col-7">{{ $employee->email ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">CNIC</div>
                        <div class="col-7">{{ $employee->cnic ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Joining</div>
                        <div class="col-7">{{ $employee->joining_date->format('d M Y') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Salary</div>
                        <div class="col-7 fw-semibold text-success">Rs. {{ number_format($employee->basic_salary, 2) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Status</div>
                        <div class="col-7">
                            @if($employee->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @if($employee->address)
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Address</div>
                        <div class="col-7">{{ $employee->address }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Recent Salaries --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Recent Salaries</h5>
                    <a href="{{ route('salaries.create') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus"></i> Add
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Month/Year</th>
                                <th>Basic</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSalaries as $salary)
                            <tr>
                                <td>{{ date('F', mktime(0,0,0,$salary->month,1)) }} {{ $salary->year }}</td>
                                <td>Rs. {{ number_format($salary->basic_salary, 2) }}</td>
                                <td class="fw-semibold">Rs. {{ number_format($salary->net_salary, 2) }}</td>
                                <td>
                                    @if($salary->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No salary records.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Attendance --}}
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Recent Attendance</h5>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-eye"></i> View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendance as $att)
                            <tr>
                                <td>{{ $att->date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $colors = [
                                            'present'  => 'success',
                                            'absent'   => 'danger',
                                            'half_day' => 'warning',
                                            'leave'    => 'info',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $att->status)) }}
                                    </span>
                                </td>
                                <td>{{ $att->check_in ?? '—' }}</td>
                                <td>{{ $att->check_out ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No attendance records.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection