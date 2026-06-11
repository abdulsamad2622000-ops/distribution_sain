@extends('layouts.app')

@section('title', 'Journal Entries')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Journal Entries</h1>
        <a href="{{ route('journal-entries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Entry
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
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Total Debit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>
                            <a href="{{ route('journal-entries.show', $entry) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $entry->reference }}
                            </a>
                        </td>
                        <td>{{ $entry->date->format('d M Y') }}</td>
                        <td>{{ Str::limit($entry->description, 50) }}</td>
                        <td class="fw-semibold">
                            Rs. {{ number_format($entry->total_debit, 2) }}
                        </td>
                        <td>
                            <span class="badge bg-success">{{ ucfirst($entry->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('journal-entries.show', $entry) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('journal-entries.destroy', $entry) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this journal entry?')">
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
                            No journal entries found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($entries->hasPages())
        <div class="card-footer">
            {{ $entries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection