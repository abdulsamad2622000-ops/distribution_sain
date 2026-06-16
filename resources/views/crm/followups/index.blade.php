@extends('layouts.app')

@section('title', 'Follow-ups')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pending Follow-ups</h1>
        <a href="{{ route('leads.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Leads
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
                        <th>Lead</th>
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Notes</th>
                        <th>Assigned By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($followups as $followup)
                    @php
                        $isOverdue = $followup->followup_date->isPast();
                    @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td>
                            <a href="{{ route('leads.show', $followup->lead) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $followup->lead->name }}
                            </a>
                            @if($followup->lead->company)
                            <br><small class="text-muted">{{ $followup->lead->company }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($followup->type) }}
                            </span>
                        </td>
                        <td>
                            {{ $followup->followup_date->format('d M Y H:i') }}
                            @if($isOverdue)
                                <br><span class="badge bg-danger">Overdue</span>
                            @endif
                        </td>
                        <td>{{ $followup->notes ?? '—' }}</td>
                        <td>{{ $followup->user->name }}</td>
                        <td>
                            <form action="{{ route('followups.mark-done', $followup) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i> Done
                                </button>
                            </form>
                            <form action="{{ route('followups.destroy', $followup) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete?')">
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
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-check-circle text-success fs-3"></i>
                            <br>No pending follow-ups!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($followups->hasPages())
        <div class="card-footer">
            {{ $followups->links() }}
        </div>
        @endif
    </div>
</div>
@endsection