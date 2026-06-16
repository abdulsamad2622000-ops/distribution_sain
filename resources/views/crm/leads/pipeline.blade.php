@extends('layouts.app')

@section('title', 'CRM — Pipeline')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Sales Pipeline</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-list"></i> List View
            </a>
            <a href="{{ route('leads.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Lead
            </a>
        </div>
    </div>

    @php
        $stageColors = [
            'new'         => 'primary',
            'contacted'   => 'info',
            'negotiation' => 'warning',
            'won'         => 'success',
            'lost'        => 'danger',
        ];
        $stageIcons = [
            'new'         => 'bi-star',
            'contacted'   => 'bi-telephone',
            'negotiation' => 'bi-chat-dots',
            'won'         => 'bi-trophy',
            'lost'        => 'bi-x-circle',
        ];
    @endphp

    <div class="row g-3">
        @foreach($pipeline as $stage => $leads)
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-{{ $stageColors[$stage] }} text-white text-center py-2">
                    <i class="bi {{ $stageIcons[$stage] }}"></i>
                    <span class="fw-semibold ms-1">{{ ucfirst($stage) }}</span>
                    <span class="badge bg-white text-{{ $stageColors[$stage] }} ms-1">
                        {{ $leads->count() }}
                    </span>
                </div>
                <div class="card-body p-2">
                    @forelse($leads as $lead)
                    <div class="card mb-2 border-{{ $stageColors[$stage] }}"
                         style="border-left: 3px solid !important;">
                        <div class="card-body p-2">
                            <div class="fw-semibold" style="font-size:12px;">
                                <a href="{{ route('leads.show', $lead) }}"
                                   class="text-decoration-none text-dark">
                                    {{ $lead->name }}
                                </a>
                            </div>
                            @if($lead->company)
                            <div class="text-muted" style="font-size:11px;">
                                <i class="bi bi-building"></i> {{ $lead->company }}
                            </div>
                            @endif
                            @if($lead->estimated_value > 0)
                            <div class="text-success fw-semibold mt-1" style="font-size:11px;">
                                Rs. {{ number_format($lead->estimated_value, 0) }}
                            </div>
                            @endif
                            @php
                                $priorityColors = [
                                    'low' => 'secondary', 'medium' => 'warning', 'high' => 'danger'
                                ];
                            @endphp
                            <div class="mt-1">
                                <span class="badge bg-{{ $priorityColors[$lead->priority] }}"
                                      style="font-size:9px;">
                                    {{ ucfirst($lead->priority) }}
                                </span>
                                @if($lead->expected_close_date)
                                <span class="text-muted" style="font-size:9px;">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $lead->expected_close_date->format('d M') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3" style="font-size:12px;">
                        No leads
                    </div>
                    @endforelse
                </div>
                <div class="card-footer p-1 text-center bg-light">
                    <small class="text-muted fw-semibold">
                        Rs. {{ number_format($leads->sum('estimated_value'), 0) }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection