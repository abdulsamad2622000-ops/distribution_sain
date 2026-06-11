@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chart of Accounts</h1>
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Account
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
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $account->code }}</span></td>
                        <td>{{ $account->name }}</td>
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
                            <span class="badge bg-{{ $colors[$account->type] ?? 'secondary' }}">
                                {{ ucfirst($account->type) }}
                            </span>
                        </td>
                        <td>{{ $account->description ?? '—' }}</td>
                        <td>
                            @if($account->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('accounts.edit', $account) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('accounts.destroy', $account) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this account?')">
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
                            No accounts found. Create your first account.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($accounts->hasPages())
        <div class="card-footer">
            {{ $accounts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection