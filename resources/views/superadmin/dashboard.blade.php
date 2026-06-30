@extends('layouts.superadmin')

@section('title', 'Platform Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#6c5ce7,#a29bfe);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="font-size:12px;opacity:.85;">Total Companies</div>
                    <h3 class="mb-0 fw-bold">{{ $totalCompanies }}</h3>
                </div>
                <i class="bi bi-buildings" style="font-size:32px;opacity:.4;"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#00b894,#55efc4);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="font-size:12px;opacity:.85;">Active</div>
                    <h3 class="mb-0 fw-bold">{{ $activeCompanies }}</h3>
                </div>
                <i class="bi bi-check-circle" style="font-size:32px;opacity:.4;"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#d63031,#ff7675);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="font-size:12px;opacity:.85;">Inactive / Suspended</div>
                    <h3 class="mb-0 fw-bold">{{ $inactiveCompanies }}</h3>
                </div>
                <i class="bi bi-pause-circle" style="font-size:32px;opacity:.4;"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#0984e3,#74b9ff);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="font-size:12px;opacity:.85;">Est. Monthly Revenue</div>
                    <h3 class="mb-0 fw-bold">{{ number_format($monthlyRevenue) }}</h3>
                </div>
                <i class="bi bi-cash-stack" style="font-size:32px;opacity:.4;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card"><div class="card-body text-center">
            <i class="bi bi-people text-primary" style="font-size:24px;"></i>
            <h4 class="mb-0 mt-2">{{ $totalUsers }}</h4>
            <small class="text-muted">Total Tenant Users</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card"><div class="card-body text-center">
            <i class="bi bi-box-seam text-primary" style="font-size:24px;"></i>
            <h4 class="mb-0 mt-2">{{ $totalPlans }}</h4>
            <small class="text-muted">Subscription Plans</small>
        </div></div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Recently Added Companies</span>
        <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Onboard Company
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Company</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCompanies as $company)
                    <tr>
                        <td class="ps-3">
                            <strong>{{ $company->name }}</strong><br>
                            <small class="text-muted">{{ $company->email }}</small>
                        </td>
                        <td>{{ $company->plan->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $company->status === 'active' ? 'success' : ($company->status === 'suspended' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>
                        <td>{{ $company->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No companies yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
