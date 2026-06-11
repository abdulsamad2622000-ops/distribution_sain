@extends('layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Monthly Attendance Report</h1>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.report') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Month</label>
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Year</label>
                    <input type="number" name="year" class="form-control"
                           value="{{ $year }}" min="2000" max="2100">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar3"></i>
                Report — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}
            </h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th class="text-center text-success">Present</th>
                        <th class="text-center text-danger">Absent</th>
                        <th class="text-center text-warning">Half Day</th>
                        <th class="text-center text-info">Leave</th>
                        <th class="text-center">Total Days</th>
                        <th class="text-center">Attendance %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    @php
                        $present  = $employee->attendance->where('status', 'present')->count();
                        $absent   = $employee->attendance->where('status', 'absent')->count();
                        $halfDay  = $employee->attendance->where('status', 'half_day')->count();
                        $leave    = $employee->attendance->where('status', 'leave')->count();
                        $total    = $employee->attendance->count();
                        $percent  = $total > 0 ? round(($present / $total) * 100) : 0;
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('employees.show', $employee) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $employee->name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $employee->designation }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $present }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger">{{ $absent }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">{{ $halfDay }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark">{{ $leave }}</span>
                        </td>
                        <td class="text-center fw-semibold">{{ $total }}</td>
                        <td class="text-center">
                            <div class="progress" style="height:20px;">
                                <div class="progress-bar
                                    {{ $percent >= 80 ? 'bg-success' : ($percent >= 60 ? 'bg-warning' : 'bg-danger') }}"
                                    style="width:{{ $percent }}%">
                                    {{ $percent }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No employees found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection