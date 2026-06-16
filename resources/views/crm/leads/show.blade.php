@extends('layouts.app')

@section('title', $lead->name)

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ $lead->name }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Lead Info --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Lead Info</h5>
                </div>
                <div class="card-body">
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
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Status</div>
                        <div class="col-7">
                            <span class="badge bg-{{ $statusColors[$lead->status] }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Priority</div>
                        <div class="col-7">
                            <span class="badge bg-{{ $priorityColors[$lead->priority] }}">
                                {{ ucfirst($lead->priority) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Company</div>
                        <div class="col-7">{{ $lead->company ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Phone</div>
                        <div class="col-7">{{ $lead->phone ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Email</div>
                        <div class="col-7">{{ $lead->email ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Source</div>
                        <div class="col-7">{{ ucfirst($lead->source ?? '—') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Value</div>
                        <div class="col-7 fw-semibold text-success">
                            Rs. {{ number_format($lead->estimated_value, 0) }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Close Date</div>
                        <div class="col-7">{{ $lead->expected_close_date?->format('d M Y') ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Assigned</div>
                        <div class="col-7">{{ $lead->assignedUser?->name ?? '—' }}</div>
                    </div>
                    @if($lead->notes)
                    <hr>
                    <div class="text-muted small">{{ $lead->notes }}</div>
                    @endif
                </div>
            </div>

            {{-- Add Followup --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Schedule Follow-up</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('followups.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <div class="mb-2">
                            <select name="type" class="form-select form-select-sm" required>
                                @foreach(['call','email','meeting','whatsapp','other'] as $t)
                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="datetime-local" name="followup_date"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="notes" class="form-control form-control-sm"
                                      rows="2" placeholder="Notes"></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm w-100">
                            <i class="bi bi-plus-circle"></i> Schedule
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Log Interaction --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-chat-text"></i> Log Interaction</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('interactions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <select name="type" class="form-select form-select-sm" required>
                                    @foreach(['call','email','meeting','whatsapp','note','other'] as $t)
                                        <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" name="subject" class="form-control form-control-sm"
                                       placeholder="Subject">
                            </div>
                            <div class="col-md-5 mb-2">
                                <input type="datetime-local" name="interaction_date"
                                       class="form-control form-control-sm"
                                       value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-md-10 mb-2">
                                <textarea name="description" class="form-control form-control-sm"
                                          rows="2" placeholder="What happened?" required></textarea>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-dark btn-sm w-100">
                                    <i class="bi bi-save"></i> Log
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Followups --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Follow-ups</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lead->followups as $followup)
                            <tr>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($followup->type) }}</span></td>
                                <td>{{ $followup->followup_date->format('d M Y H:i') }}</td>
                                <td>{{ $followup->notes ?? '—' }}</td>
                                <td>
                                    @if($followup->status === 'done')
                                        <span class="badge bg-success">Done</span>
                                    @elseif($followup->status === 'missed')
                                        <span class="badge bg-danger">Missed</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($followup->status === 'pending')
                                    <form action="{{ route('followups.mark-done', $followup) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    @endif
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
                                <td colspan="5" class="text-center text-muted py-3">No follow-ups yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Interactions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Interaction History</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lead->interactions as $interaction)
                            <tr>
                                <td><span class="badge bg-secondary">{{ ucfirst($interaction->type) }}</span></td>
                                <td>{{ $interaction->subject ?? '—' }}</td>
                                <td>{{ Str::limit($interaction->description, 40) }}</td>
                                <td>{{ $interaction->interaction_date->format('d M Y H:i') }}</td>
                                <td>{{ $interaction->user->name }}</td>
                                <td>
                                    <form action="{{ route('interactions.destroy', $interaction) }}"
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
                                <td colspan="6" class="text-center text-muted py-3">No interactions yet.</td>
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