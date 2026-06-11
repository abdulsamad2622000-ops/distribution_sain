@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Attendance</h1>
        <a href="{{ route('attendance.report') }}" class="btn btn-outline-primary">
            <i class="bi bi-bar-chart"></i> Monthly Report
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Select Date</label>
                    <input type="date" name="date" class="form-control"
                           value="{{ $date }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Load
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar-check"></i>
                Attendance — {{ date('d M Y', strtotime($date)) }}
            </h5>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        @php
                            $existing = $records[$employee->id] ?? null;
                            $status   = $existing?->status ?? 'present';
                        @endphp
                        <tr>
                            <td><span class="badge bg-secondary">{{ $employee->employee_id }}</span></td>
                            <td class="fw-semibold">{{ $employee->name }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @foreach(['present' => 'success', 'absent' => 'danger', 'half_day' => 'warning', 'leave' => 'info'] as $val => $color)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="attendance[{{ $employee->id }}]"
                                               value="{{ $val }}"
                                               id="{{ $val }}_{{ $employee->id }}"
                                               {{ $status === $val ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $val }}_{{ $employee->id }}">
                                            <span class="badge bg-{{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $val)) }}
                                            </span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No active employees found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($employees->count() > 0)
                <div class="p-3 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Attendance
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection