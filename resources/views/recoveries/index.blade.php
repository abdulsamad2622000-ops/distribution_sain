@extends('layouts.app')

@section('title', 'Recovery')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cash-coin me-2"></i>Payment Recovery</span>
        <a href="{{ route('recoveries.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Record Payment
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Customer</th>
                        <th>Invoice #</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recoveries as $recovery)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><strong>{{ $recovery->customer->name ?? 'N/A' }}</strong></td>
                        <td>{{ $recovery->sale->invoice_no ?? 'N/A' }}</td>
                        <td class="text-success fw-bold">PKR {{ number_format($recovery->amount) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $recovery->payment_method)) }}</td>
                        <td>{{ $recovery->reference_no ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M Y') }}</td>
                        <td>{{ $recovery->user->name ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('recoveries.destroy', $recovery) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No recoveries found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recoveries->hasPages())
    <div class="card-footer">{{ $recoveries->links() }}</div>
    @endif
</div>
@endsection