@extends('layouts.app')

@section('title', 'Income Statement')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Income Statement</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('accounts.trial-balance') }}" class="btn btn-outline-secondary">
                <i class="bi bi-journal-check"></i> Trial Balance
            </a>
            <a href="{{ route('accounts.balance-sheet') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-bar-graph"></i> Balance Sheet
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Revenue --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Revenue</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account</th>
                                <th class="text-end">Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenues as $revenue)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $revenue['code'] }}</span></td>
                                <td>{{ $revenue['name'] }}</td>
                                <td class="text-end fw-semibold text-success">
                                    {{ number_format($revenue['balance'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No revenue found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="2" class="fw-bold">Total Revenue</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($totalRevenue, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Expenses --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-down-arrow"></i> Expenses</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account</th>
                                <th class="text-end">Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $expense['code'] }}</span></td>
                                <td>{{ $expense['name'] }}</td>
                                <td class="text-end fw-semibold text-danger">
                                    {{ number_format($expense['balance'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No expenses found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-danger">
                            <tr>
                                <td colspan="2" class="fw-bold">Total Expenses</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($totalExpenses, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Net Income Summary --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fs-6">Total Revenue</span>
                        <span class="fw-semibold text-success">Rs. {{ number_format($totalRevenue, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted fs-6">Total Expenses</span>
                        <span class="fw-semibold text-danger">Rs. {{ number_format($totalExpenses, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Net Income</span>
                        <span class="fw-bold fs-5 {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                            Rs. {{ number_format(abs($netIncome), 2) }}
                            {{ $netIncome >= 0 ? '(Profit)' : '(Loss)' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection