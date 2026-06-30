@extends('layouts.superadmin')

@section('title', 'Companies')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-buildings me-2"></i>Client Companies</span>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Search name / email" style="width:180px;">
                <select name="status" class="form-select form-select-sm" style="width:130px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active"    @selected(request('status')=='active')>Active</option>
                    <option value="inactive"  @selected(request('status')=='inactive')>Inactive</option>
                    <option value="suspended" @selected(request('status')=='suspended')>Suspended</option>
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            </form>
            <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Onboard
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Company</th>
                        <th>Plan</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Subscription Ends</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $company->name }}</strong><br>
                            <small class="text-muted">{{ $company->email }}</small>
                        </td>
                        <td>
                            @if($company->plan)
                                <span class="badge bg-info text-dark">{{ $company->plan->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $company->users_count }}</td>
                        <td>
                            <span class="badge bg-{{ $company->status === 'active' ? 'success' : ($company->status === 'suspended' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>
                        <td>{{ $company->subscription_ends_at?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <form action="{{ route('superadmin.companies.toggle', $company) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-{{ $company->status === 'active' ? 'secondary' : 'success' }}"
                                        title="{{ $company->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi bi-{{ $company->status === 'active' ? 'pause' : 'play' }}-fill"></i>
                                </button>
                            </form>
                            <a href="{{ route('superadmin.companies.edit', $company) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this company?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No companies found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($companies->hasPages())
    <div class="card-footer">{{ $companies->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
