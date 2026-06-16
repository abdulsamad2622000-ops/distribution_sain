@extends('layouts.app')

@section('title', 'CRM — Leads')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Leads</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('leads.pipeline') }}" class="btn btn-outline-primary">
                <i class="bi bi-kanban"></i> Pipeline View
            </a>
            <a href="{{ route('followups.index') }}" class="btn btn-outline-warning">
                <i class="bi bi-clock"></i> Follow-ups
            </a>
            <a href="{{ route('leads.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Lead
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        @php
            $statColors = [
                'new'         => ['bg' => 'primary',   'icon' => 'bi-star'],
                'contacted'   => ['bg' => 'info',      'icon' => 'bi-telephone'],
                'negotiation' => ['bg' => 'warning',   'icon' => 'bi-chat-dots'],
                'won'         => ['bg' => 'success',   'icon' => 'bi-trophy'],
                'lost'        => ['bg' => 'danger',    'icon' => 'bi-x-circle'],
            ];
        @endphp
        @foreach($stats as $key => $count)
        <div class="col-md-2 col-6 mb-3">
            <div class="card shadow-sm text-center py-3">
                <i class="bi {{ $statColors[$key]['icon'] }} text-{{ $statColors[$key]['bg'] }} fs-4"></i>
                <div class="fw-bold fs-5 mt-1">{{ $count }}</div>
                <small class="text-muted">{{ ucfirst($key) }}</small>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['new','contacted','negotiation','won','lost'] as $s)
                            <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="">All Priority</option>
                        @foreach(['low','medium','high'] as $p)
                            <option value="{{ $p }}" {{ $priority == $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
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
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Source</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Value (Rs.)</th>
                        <th>Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    @php
                        $statusColors = [
                            'new'         => 'primary',
                            'contacted'   => 'info',
                            'negotiation' => 'warning',
                            'won'         => 'success',
                            'lost'        => 'danger',
                        ];
                        $priorityColors = [
                            'low'    => 'secondary',
                            'medium' => 'warning',
                            'high'   => 'danger',
                        ];
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('leads.show', $lead) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $lead->name }}
                            </a>
                        </td>
                        <td>{{ $lead->company ?? '—' }}</td>
                        <td>{{ $lead->phone ?? '—' }}</td>
                        <td>{{ ucfirst($lead->source ?? '—') }}</td>
                        <td>
                            <span class="badge bg-{{ $priorityColors[$lead->priority] }}">
                                {{ ucfirst($lead->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $statusColors[$lead->status] }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td>Rs. {{ number_format($lead->estimated_value, 0) }}</td>
                        <td>{{ $lead->assignedUser?->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('leads.show', $lead) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('leads.edit', $lead) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('leads.destroy', $lead) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this lead?')">
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
                        <td colspan="9" class="text-center text-muted py-4">
                            No leads found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
        <div class="card-footer">
            {{ $leads->links() }}
        </div>
        @endif
    </div>
</div>
@endsection