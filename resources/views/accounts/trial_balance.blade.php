@extends('layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Trial Balance</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('accounts.balance-sheet') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-bar-graph"></i> Balance Sheet
            </a>
            <a href="{{ route('accounts.income-statement') }}" class="btn btn-outline-success">
                <i class="bi bi-graph-up"></i> Income Statement
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-journal-check"></i> Trial Balance — {{ now()->format('d M Y') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th class="text-end">Debit (Rs.)</th>
                        <th class="text-end">Credit (Rs.)</th>
                        <th class="text-end">Balance (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    @if($account['debit'] > 0 || $account['credit'] > 0)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $account['code'] }}</span></td>
                        <td>{{ $account['name'] }}</td>
                        <td>
                            @php
                                $colors = [
                                    'asset'     => 'primary',
                                    'liability' => 'danger',
                                    'equity'    => 'success',
                                    'revenue'   => 'info',
                                    'expense'   => 'warning',
                                ];
                            @endphp
                            <span class="badge bg-{{ $colors[$account['type']] ?? 'secondary' }}">
                                {{ ucfirst($account['type']) }}
                            </span>
                        </td>
                        <td class="text-end">{{ number_format($account['debit'], 2) }}</td>
                        <td class="text-end">{{ number_format($account['credit'], 2) }}</td>
                        <td class="text-end fw-semibold
                            {{ $account['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format(abs($account['balance']), 2) }}
                            {{ $account['balance'] >= 0 ? 'Dr' : 'Cr' }}
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No transactions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="fw-bold">TOTAL</td>
                        <td class="text-end fw-bold">Rs. {{ number_format($totalDebit, 2) }}</td>
                        <td class="text-end fw-bold">Rs. {{ number_format($totalCredit, 2) }}</td>
                        <td class="text-end fw-bold">
                            @if(round($totalDebit, 2) === round($totalCredit, 2))
                                <span class="badge bg-success">Balanced ✓</span>
                            @else
                                <span class="badge bg-danger">Not Balanced ✗</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection