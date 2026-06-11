@extends('layouts.app')

@section('title', 'Journal Entry — ' . $journalEntry->reference)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Journal Entry — {{ $journalEntry->reference }}</h1>
        <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Entry Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-4 fw-semibold text-muted">Reference</div>
                        <div class="col-8">{{ $journalEntry->reference }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-semibold text-muted">Date</div>
                        <div class="col-8">{{ $journalEntry->date->format('d M Y') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-semibold text-muted">Description</div>
                        <div class="col-8">{{ $journalEntry->description }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-semibold text-muted">Status</div>
                        <div class="col-8">
                            <span class="badge bg-success">{{ ucfirst($journalEntry->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Journal Lines</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Account</th>
                                <th>Type</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($journalEntry->lines as $line)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary me-1">{{ $line->account->code }}</span>
                                    {{ $line->account->name }}
                                </td>
                                <td>
                                    @if($line->type === 'debit')
                                        <span class="badge bg-primary">Debit</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Credit</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($line->type === 'debit')
                                        Rs. {{ number_format($line->amount, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($line->type === 'credit')
                                        Rs. {{ number_format($line->amount, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $line->description ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-semibold">
                            <tr>
                                <td colspan="2">Total</td>
                                <td class="text-end text-success">
                                    Rs. {{ number_format($journalEntry->total_debit, 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    Rs. {{ number_format($journalEntry->total_credit, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Debit</span>
                        <span class="fw-semibold text-success">
                            Rs. {{ number_format($journalEntry->total_debit, 2) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Credit</span>
                        <span class="fw-semibold text-danger">
                            Rs. {{ number_format($journalEntry->total_credit, 2) }}
                        </span>
                    </div>
                    <hr>
                    @if($journalEntry->total_debit === $journalEntry->total_credit)
                        <div class="alert alert-success py-2 text-center mb-0">
                            <i class="bi bi-check-circle"></i> Entry is Balanced
                        </div>
                    @else
                        <div class="alert alert-danger py-2 text-center mb-0">
                            <i class="bi bi-exclamation-circle"></i> Entry is NOT Balanced
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection