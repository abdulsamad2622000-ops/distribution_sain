@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Balance Sheet</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('accounts.trial-balance') }}" class="btn btn-outline-secondary">
                <i class="bi bi-journal-check"></i> Trial Balance
            </a>
            <a href="{{ route('accounts.income-statement') }}" class="btn btn-outline-success">
                <i class="bi bi-graph-up"></i> Income Statement
            </a>
        </div>
    </div>

    <div class="row">
        {{-- LEFT: Assets --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> Assets</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account</th>
                                <th class="text-end">Balance (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $asset['code'] }}</span></td>
                                <td>{{ $asset['name'] }}</td>
                                <td class="text-end fw-semibold text-primary">
                                    {{ number_format($asset['balance'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No assets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <td colspan="2" class="fw-bold">Total Assets</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($totalAssets, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT: Liabilities + Equity --}}
        <div class="col-md-6">
            {{-- Liabilities --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Liabilities</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account</th>
                                <th class="text-end">Balance (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($liabilities as $liability)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $liability['code'] }}</span></td>
                                <td>{{ $liability['name'] }}</td>
                                <td class="text-end fw-semibold text-danger">
                                    {{ number_format($liability['balance'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No liabilities found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-danger">
                            <tr>
                                <td colspan="2" class="fw-bold">Total Liabilities</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($totalLiabilities, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Equity --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-piggy-bank"></i> Equity</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account</th>
                                <th class="text-end">Balance (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equity as $eq)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $eq['code'] }}</span></td>
                                <td>{{ $eq['name'] }}</td>
                                <td class="text-end fw-semibold text-success">
                                    {{ number_format($eq['balance'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No equity found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="2" class="fw-bold">Total Equity</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($totalEquity, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Summary --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Liabilities + Equity</span>
                        <span class="fw-bold">
                            Rs. {{ number_format($totalLiabilities + $totalEquity, 2) }}
                        </span>
                    </div>
                    <hr>
                    @if(round($totalAssets, 2) === round($totalLiabilities + $totalEquity, 2))
                        <div class="alert alert-success py-2 text-center mb-0">
                            <i class="bi bi-check-circle"></i> Balance Sheet is Balanced ✓
                        </div>
                    @else
                        <div class="alert alert-warning py-2 text-center mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Balance Sheet is not balanced yet
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection